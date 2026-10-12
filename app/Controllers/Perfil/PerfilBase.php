<?php

namespace App\Controllers\Perfil;

use App\Controllers\BaseController;
use App\Models\EnderecosModel;
use App\Services\ImagemUploadService;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Pedaços comuns às telas "Meu perfil" (profissional e contratante):
 * leitura/gravação do endereço e utilitários de upload de foto.
 */
abstract class PerfilBase extends BaseController
{
    protected ImagemUploadService $imagens;

    public function __construct()
    {
        $this->imagens = new ImagemUploadService();
    }

    protected function usuarioId(): int
    {
        return (int) session('usuario')->id;
    }


    /**
     * Valida as fotos enviadas (somente os campos que realmente trouxeram arquivo).
     *
     * @param array<string, string> $rotulos campo => nome para a mensagem (ex.: 'foto_perfil' => 'A foto de perfil')
     * @return array<string, string> erros por campo (vazio se estiver tudo certo)
     */
    protected function validarFotos(array $rotulos): array
    {
        $rules     = [];
        $mensagens = [];

        foreach ($rotulos as $campo => $rotulo) {
            $arquivo = $this->request->getFile($campo);

            if ($arquivo === null || $arquivo->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $rules[$campo]     = 'uploaded[' . $campo . ']|' . ImagemUploadService::regra($campo);
            $mensagens[$campo] = [
                'uploaded' => $rotulo . ' não foi enviada. Confira se o arquivo tem no máximo 2MB e tente novamente.',
                'is_image' => $rotulo . ' precisa ser uma imagem.',
                'mime_in'  => $rotulo . ' deve ser JPG, PNG ou WEBP.',
                'max_size' => $rotulo . ' deve ter no máximo 2MB.',
            ];
        }

        if (empty($rules)) {
            return [];
        }

        return $this->validate($rules, $mensagens) ? [] : $this->validator->getErrors();
    }

    /**
     * Dados para preencher os selects Estado > Cidade > Bairro do formulário de endereço.
     *
     * @return array{endereco: ?object, ufs: array, ufId: ?int, cidades: array, bairros: array}
     */
    protected function dadosEndereco(int $usuarioId): array
    {
        $db = db_connect();

        $endereco = model(EnderecosModel::class)
            ->where('usuario_id', $usuarioId)
            ->orderBy('id', 'DESC')
            ->first();

        $ufs = $db->table('localidades')
            ->select('id, nome, sigla')
            ->where('tipo', 'UF')
            ->orderBy('nome', 'ASC')
            ->get()
            ->getResult();

        $ufId    = null;
        $cidades = [];
        $bairros = [];

        if ($endereco) {
            $cidade = $db->table('localidades')->select('id, localidade_pai_id')->where('id', $endereco->cidade_id)->get()->getRow();

            if ($cidade) {
                $ufId = (int) $cidade->localidade_pai_id;

                $cidades = $db->table('localidades')
                    ->select('id, nome')
                    ->where('tipo', 'MUNICIPIO')
                    ->where('localidade_pai_id', $ufId)
                    ->orderBy('nome', 'ASC')
                    ->get()
                    ->getResult();

                $bairros = $db->table('localidades')
                    ->select('id, nome')
                    ->where('tipo', 'BAIRRO')
                    ->where('localidade_pai_id', $cidade->id)
                    ->orderBy('nome', 'ASC')
                    ->get()
                    ->getResult();
            }
        }

        return [
            'endereco' => $endereco,
            'ufs'      => $ufs,
            'ufId'     => $ufId,
            'cidades'  => $cidades,
            'bairros'  => $bairros,
        ];
    }

    /**
     * Valida e grava o endereço do usuário logado (atualiza o existente ou cria um novo).
     * $voltarPara é a URL (com âncora) usada para devolver o usuário à tela.
     */
    protected function salvarEndereco(string $voltarPara): RedirectResponse
    {
        $post = $this->request->getPost();

        $bairroId = $post['bairro_id'] ?? '';

        $dados = [
            'cep'          => preg_replace('/\D/', '', (string) ($post['cep'] ?? '')),
            'logradouro'   => trim((string) ($post['logradouro'] ?? '')),
            'numero'       => trim((string) ($post['numero'] ?? '')),
            'complemento'  => trim((string) ($post['complemento'] ?? '')),
            'cidade_id'    => (int) ($post['cidade_id'] ?? 0),
            'bairro_id'    => ctype_digit((string) $bairroId) && (int) $bairroId > 0 ? (int) $bairroId : null,
            'bairro_outro' => trim((string) ($post['bairro_outro'] ?? '')),
        ];

        $rules = [
            'cep'          => 'required|exact_length[8]',
            'logradouro'   => 'required|max_length[150]',
            'numero'       => 'required|max_length[20]',
            'complemento'  => 'permit_empty|max_length[100]',
            'cidade_id'    => 'required|is_natural_no_zero',
            'bairro_outro' => 'permit_empty|max_length[150]',
        ];

        $mensagens = [
            'cep'        => ['required' => 'Informe o CEP.', 'exact_length' => 'O CEP deve ter 8 dígitos.'],
            'logradouro' => ['required' => 'Informe o logradouro.', 'max_length' => 'O logradouro é muito longo.'],
            'numero'     => ['required' => 'Informe o número.', 'max_length' => 'O número é muito longo.'],
            'cidade_id'  => ['required' => 'Selecione a cidade.', 'is_natural_no_zero' => 'Selecione a cidade.'],
        ];

        if (!$this->validateData($dados, $rules, $mensagens)) {
            return redirect()->to($voltarPara)->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $db = db_connect();

        $cidade = $db->table('localidades')->select('id')->where('id', $dados['cidade_id'])->where('tipo', 'MUNICIPIO')->get()->getRow();

        if (!$cidade) {
            return redirect()->to($voltarPara)->withInput()->with('error', 'Selecione uma cidade válida.');
        }

        if ($dados['bairro_id'] !== null) {
            $bairro = $db->table('localidades')
                ->select('id')
                ->where('id', $dados['bairro_id'])
                ->where('tipo', 'BAIRRO')
                ->where('localidade_pai_id', $dados['cidade_id'])
                ->get()
                ->getRow();

            if (!$bairro) {
                return redirect()->to($voltarPara)->withInput()->with('error', 'O bairro escolhido não pertence à cidade selecionada.');
            }

            $dados['bairro_outro'] = null;
        } elseif ($dados['bairro_outro'] === '') {
            return redirect()->to($voltarPara)->withInput()->with('error', 'Selecione o bairro ou informe o nome dele.');
        }

        $dados['complemento'] = $dados['complemento'] !== '' ? $dados['complemento'] : null;

        $model     = model(EnderecosModel::class);
        $existente = $model->where('usuario_id', $this->usuarioId())->orderBy('id', 'DESC')->first();

        try {
            if ($existente) {
                // Endereço mudou: as coordenadas antigas deixam de valer.
                $mudou = $existente->cep !== $dados['cep']
                    || $existente->logradouro !== $dados['logradouro']
                    || (string) $existente->numero !== $dados['numero']
                    || (int) $existente->cidade_id !== $dados['cidade_id'];

                if ($mudou) {
                    $dados['latitude']  = null;
                    $dados['longitude'] = null;
                }

                $model->update($existente->id, $dados);
            } else {
                $model->insert($dados + ['usuario_id' => $this->usuarioId()]);
            }
        } catch (\Throwable $e) {
            log_message('error', '[Perfil] Erro ao salvar endereço: {error}', ['error' => $e->getMessage()]);

            return redirect()->to($voltarPara)->withInput()->with('error', 'Não foi possível salvar o endereço. Tente novamente.');
        }

        return redirect()->to($voltarPara)->with('success', 'Endereço atualizado.');
    }
}
