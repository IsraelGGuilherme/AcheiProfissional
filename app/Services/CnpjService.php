<?php

namespace App\Services;

/**
 * Validação local e consulta de CNPJ na API pública OpenCNPJ (https://api.opencnpj.org).
 *
 * - Aceita CNPJ numérico e alfanumérico (12 caracteres [0-9A-Z] + 2 dígitos verificadores).
 * - O usuário escolhe a base de onde os dados são puxados (receita, cno ou rntrc). Se a base escolhida
 *   estiver indisponível ou não trouxer dados da empresa, as outras são tentadas e a resposta informa
 *   qual base respondeu de fato.
 * - A consulta depende de uma API externa: ela não substitui a validação local dos dígitos verificadores.
 *
 * Configuração opcional no .env:
 *   opencnpj.baseUrl = https://api.opencnpj.org
 *   opencnpj.timeout = 6   (segundos por tentativa)
 */
class CnpjService
{
    /** Bases disponíveis => rótulo mostrado ao usuário. A ordem é a canônica da OpenCNPJ. */
    public const DATASETS = [
        'receita' => 'Receita Federal',
        'cno'     => 'CNO — Cadastro Nacional de Obras',
        'rntrc'   => 'RNTRC — Transportadores',
    ];

    private const SITUACOES_POR_CODIGO = [
        '1' => 'NULA', '2' => 'ATIVA', '3' => 'SUSPENSA', '4' => 'INAPTA', '8' => 'BAIXADA',
    ];

    /** Tempo (em segundos) que um resultado definitivo fica em cache. */
    private const CACHE_OK = 21600;        // 6 horas
    private const CACHE_NAO_ENCONTRADO = 300; // 5 minutos

    protected string $baseUrl;
    protected int $timeout;

    public function __construct(?string $baseUrl = null, ?int $timeout = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? (string) env('opencnpj.baseUrl', 'https://api.opencnpj.org'), '/');
        $this->timeout = $timeout ?? (int) env('opencnpj.timeout', 6);
    }

    // ------------------------------------------------------------------
    // Validação local
    // ------------------------------------------------------------------

    /** Remove máscara e deixa só [0-9A-Z]. */
    public static function normalizar(?string $valor): string
    {
        return strtoupper(preg_replace('/[^0-9A-Za-z]/', '', (string) $valor));
    }

    /** 12ABC34501DE35 => 12.ABC.345/01DE-35 (se não tiver 14 caracteres, devolve como veio). */
    public static function formatar(?string $valor): string
    {
        $c = self::normalizar($valor);

        if (strlen($c) !== 14) {
            return (string) $valor;
        }

        return sprintf('%s.%s.%s/%s-%s', substr($c, 0, 2), substr($c, 2, 3), substr($c, 5, 3), substr($c, 8, 4), substr($c, 12, 2));
    }

    /**
     * Confere formato e dígitos verificadores (módulo 11). Cada caractere vale seu código ASCII menos 48,
     * o que mantém o resultado dos CNPJs numéricos e cobre os alfanuméricos.
     */
    public static function valido(?string $valor): bool
    {
        $c = self::normalizar($valor);

        if (!preg_match('/^[0-9A-Z]{12}[0-9]{2}$/', $c)) {
            return false;
        }

        return self::digito(substr($c, 0, 12)) === (int) $c[12]
            && self::digito(substr($c, 0, 13)) === (int) $c[13];
    }

    private static function digito(string $base): int
    {
        $pesos = [2, 3, 4, 5, 6, 7, 8, 9];
        $soma  = 0;

        for ($i = strlen($base) - 1, $p = 0; $i >= 0; $i--, $p++) {
            $soma += (ord($base[$i]) - 48) * $pesos[$p % 8];
        }

        $resto = $soma % 11;

        return $resto < 2 ? 0 : 11 - $resto;
    }

    // ------------------------------------------------------------------
    // Consulta
    // ------------------------------------------------------------------

    /**
     * @return array<string, mixed> sempre com: sucesso (bool), mensagem, codigo
     *   (ok | inativo | invalido | nao_encontrado | sem_dados | indisponivel).
     *   Quando a empresa é encontrada: cnpj, nome, fantasia, situacao, ativa, mei, endereco, dataset, dataset_nome,
     *   consta_no_dataset e fonte_alternativa.
     */
    public function consultar(?string $cnpjInformado, string $dataset = 'receita'): array
    {
        $cnpj = self::normalizar($cnpjInformado);

        if (!self::valido($cnpj)) {
            return $this->erro('invalido', 'CNPJ inválido. Confira os números digitados.');
        }

        $dataset = isset(self::DATASETS[$dataset]) ? $dataset : 'receita';

        $chaveCache = 'cnpj_' . $cnpj . '_' . $dataset;
        $emCache    = cache($chaveCache);

        if (is_array($emCache)) {
            return $emCache;
        }

        // Base escolhida primeiro; as demais só entram se ela falhar ou vier sem dados da empresa.
        $ordem = array_merge([$dataset], array_values(array_diff(array_keys(self::DATASETS), [$dataset])));

        $indisponiveis  = 0;
        $naoEncontrados = 0;

        foreach ($ordem as $base) {
            $resposta = $this->buscar($cnpj, $base);

            // Sem conexão (timeout, DNS, recusada): tentar outra base no mesmo servidor não ajuda e só demoraria mais.
            if ($resposta['status'] === 'rede') {
                return $this->erro('indisponivel', 'O serviço de consulta de CNPJ está indisponível agora. Tente novamente em instantes.');
            }

            if ($resposta['status'] === 'indisponivel') {
                $indisponiveis++;

                continue;
            }

            if ($resposta['status'] === 'nao_encontrado') {
                $naoEncontrados++;

                continue;
            }

            $empresa = $this->extrairEmpresa($resposta['json'], $base);

            if ($empresa === null) {
                // Ajuda a diagnosticar se a OpenCNPJ mudar o formato da resposta.
                log_message('warning', '[CNPJ] Resposta da base {base} sem dados da empresa. Chaves recebidas: {chaves}', [
                    'base'   => $base,
                    'chaves' => implode(',', array_keys($resposta['json'])),
                ]);

                continue;
            }

            $resultado = $this->montarResultado($cnpj, $empresa, $resposta['json'], $base, $dataset);

            cache()->save($chaveCache, $resultado, self::CACHE_OK);

            return $resultado;
        }

        if ($indisponiveis === count($ordem)) {
            return $this->erro('indisponivel', 'O serviço de consulta de CNPJ está indisponível agora. Tente novamente em instantes.');
        }

        if ($naoEncontrados > 0) {
            $resultado = $this->erro('nao_encontrado', 'CNPJ não encontrado na base pública.');
            cache()->save($chaveCache, $resultado, self::CACHE_NAO_ENCONTRADO);

            return $resultado;
        }

        return $this->erro('sem_dados', 'Não encontramos dados desta empresa nas bases consultadas.');
    }

    /**
     * @return array{status: string, json?: array} status: ok | nao_encontrado | indisponivel (a base respondeu
     *         com erro ou lixo) | rede (não foi possível nem conectar/receber)
     */
    protected function buscar(string $cnpj, string $dataset): array
    {
        try {
            $cliente = \Config\Services::curlrequest([
                'timeout'         => $this->timeout,
                'connect_timeout' => min(4, $this->timeout),
                'http_errors'     => false,
                'headers'         => ['Accept' => 'application/json', 'User-Agent' => 'AcheiProfissional/1.0'],
            ]);

            $resposta = $cliente->get($this->baseUrl . '/' . rawurlencode($cnpj), ['query' => ['datasets' => $dataset]]);
        } catch (\Throwable $e) {
            log_message('warning', '[CNPJ] Falha de rede ao consultar {dataset}: {erro}', ['dataset' => $dataset, 'erro' => $e->getMessage()]);

            return ['status' => 'rede'];
        }

        $codigo = $resposta->getStatusCode();

        if ($codigo === 404) {
            return ['status' => 'nao_encontrado'];
        }

        if ($codigo !== 200) {
            log_message('warning', '[CNPJ] OpenCNPJ respondeu HTTP {codigo} para {dataset}', ['codigo' => $codigo, 'dataset' => $dataset]);

            return ['status' => 'indisponivel'];
        }

        $json = json_decode((string) $resposta->getBody(), true);

        if (!is_array($json)) {
            return ['status' => 'indisponivel'];
        }

        return ['status' => 'ok', 'json' => $json];
    }

    /**
     * Procura, na resposta da API, o bloco com os dados cadastrais da empresa
     * (na raiz, em "data"/"empresa" ou dentro da chave da própria base).
     */
    protected function extrairEmpresa(array $json, string $base): ?array
    {
        $candidatos = [$json, $json['data'] ?? null, $json['empresa'] ?? null, $json[$base] ?? null, $json['receita'] ?? null];

        foreach ($candidatos as $candidato) {
            if (is_array($candidato) && $this->primeiro($candidato, ['razao_social', 'razaoSocial', 'nome_empresarial', 'nome']) !== null) {
                return $candidato;
            }
        }

        return null;
    }

    protected function montarResultado(string $cnpj, array $empresa, array $json, string $baseRespondeu, string $baseEscolhida): array
    {
        $situacao = $this->normalizarSituacao($this->primeiro($empresa, ['situacao_cadastral', 'situacaoCadastral', 'situacao']));
        $ativa    = $situacao === 'ATIVA';

        $end = is_array($empresa['endereco'] ?? null) ? $empresa['endereco'] : $empresa;

        $logradouro = trim(implode(' ', array_filter([
            $this->primeiro($end, ['tipo_logradouro', 'tipoLogradouro']),
            $this->primeiro($end, ['logradouro']),
        ])));

        $resultado = [
            'sucesso'           => $ativa,
            'codigo'            => $ativa ? 'ok' : 'inativo',
            'mensagem'          => $ativa
                ? 'CNPJ ativo na ' . self::DATASETS[$baseRespondeu] . '.'
                : 'CNPJ encontrado, mas a situação cadastral é ' . ($situacao ?: 'desconhecida') . '.',
            'cnpj'              => self::formatar($cnpj),
            'nome'              => (string) $this->primeiro($empresa, ['razao_social', 'razaoSocial', 'nome_empresarial', 'nome']),
            'fantasia'          => (string) ($this->primeiro($empresa, ['nome_fantasia', 'nomeFantasia', 'fantasia']) ?? ''),
            'situacao'          => $situacao,
            'ativa'             => $ativa,
            'mei'               => $this->paraBooleano($this->primeiro($empresa, ['opcao_mei', 'opcaoMei', 'mei'])),
            'endereco'          => [
                'logradouro'  => $logradouro,
                'numero'      => (string) ($this->primeiro($end, ['numero']) ?? ''),
                'complemento' => (string) ($this->primeiro($end, ['complemento']) ?? ''),
                'bairro'      => (string) ($this->primeiro($end, ['bairro']) ?? ''),
                'municipio'   => (string) ($this->primeiro($end, ['municipio', 'cidade']) ?? ''),
                'uf'          => (string) ($this->primeiro($end, ['uf']) ?? ''),
                'cep'         => (string) ($this->primeiro($end, ['cep']) ?? ''),
            ],
            'dataset'           => $baseRespondeu,
            'dataset_nome'      => self::DATASETS[$baseRespondeu],
            // Para cno/rntrc: a empresa também consta nessa base? (a API traz a chave da base com dados ou vazia)
            'consta_no_dataset' => $baseRespondeu === 'receita' ? true : !empty($json[$baseRespondeu]),
            'fonte_alternativa' => $baseRespondeu !== $baseEscolhida,
        ];

        if ($resultado['fonte_alternativa']) {
            $resultado['mensagem'] .= ' (A base escolhida, ' . self::DATASETS[$baseEscolhida] . ', não trouxe os dados da empresa.)';
        }

        return $resultado;
    }

    protected function normalizarSituacao($valor): string
    {
        if ($valor === null || $valor === '') {
            return '';
        }

        if (is_numeric($valor)) {
            return self::SITUACOES_POR_CODIGO[(string) (int) $valor] ?? (string) $valor;
        }

        $texto = strtoupper(trim((string) $valor));

        return strtr($texto, ['Á' => 'A', 'Ã' => 'A', 'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ç' => 'C']);
    }

    /** "S"/"Sim"/true => true, "N"/"Não"/false => false, ausente => null. */
    protected function paraBooleano($valor): ?bool
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        if (is_bool($valor)) {
            return $valor;
        }

        return in_array(strtoupper(trim((string) $valor)), ['S', 'SIM', 'TRUE', '1'], true);
    }

    /** Primeiro valor não vazio entre as chaves informadas. */
    protected function primeiro(array $dados, array $chaves)
    {
        foreach ($chaves as $chave) {
            if (isset($dados[$chave]) && $dados[$chave] !== '' && !is_array($dados[$chave])) {
                return $dados[$chave];
            }
        }

        return null;
    }

    protected function erro(string $codigo, string $mensagem): array
    {
        return ['sucesso' => false, 'codigo' => $codigo, 'mensagem' => $mensagem];
    }
}
