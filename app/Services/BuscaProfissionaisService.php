<?php

namespace App\Services;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\BaseConnection;

/**
 * Consultas de leitura sobre profissionais: busca da home, perfil público
 * e listas auxiliares (categorias, cidades com profissionais, bairros).
 *
 * Só aparecem profissionais com cadastro concluído (usuarios.status = 'ATIVO').
 */
class BuscaProfissionaisService
{
    public const POR_PAGINA = 10;

    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? db_connect();
    }

    /**
     * @param array{categoria?:int,cidade?:int,bairro?:int,nota?:int} $filtros
     * @return array{itens: list<object>, total: int, pagina: int, paginas: int}
     */
    public function buscar(array $filtros, int $pagina = 1, int $porPagina = self::POR_PAGINA): array
    {
        $pagina = max(1, $pagina);

        $total = (int) $this->base($filtros)->select('COUNT(*) AS n', false)->get()->getRow()->n;

        $paginas = max(1, (int) ceil($total / $porPagina));
        $pagina  = min($pagina, $paginas);

        $itens = $this->base($filtros)
            ->select('p.id, p.nome, p.slug, p.descricao, p.whatsapp, u.foto_perfil')
            ->select('(SELECT ROUND(AVG(a.nota), 1) FROM avaliacoes a WHERE a.profissional_id = p.id) AS media', false)
            ->select('(SELECT COUNT(*) FROM avaliacoes a2 WHERE a2.profissional_id = p.id) AS total_avaliacoes', false)
            ->select("(SELECT GROUP_CONCAT(c.nome ORDER BY c.nome SEPARATOR '|') FROM profissional_categoria pc JOIN categorias c ON c.id = pc.categoria_id WHERE pc.profissional_id = p.id) AS categorias", false)
            ->select('cid.nome AS cidade_nome, uf.sigla AS uf, COALESCE(bai.nome, e.bairro_outro) AS bairro_nome')
            ->orderBy('COALESCE(media, 0)', 'DESC', false)
            ->orderBy('total_avaliacoes', 'DESC')
            ->orderBy('p.nome', 'ASC')
            ->limit($porPagina, ($pagina - 1) * $porPagina)
            ->get()
            ->getResult();

        foreach ($itens as $item) {
            $item->categorias = $item->categorias !== null ? explode('|', $item->categorias) : [];
        }

        return [
            'itens'   => $itens,
            'total'   => $total,
            'pagina'  => $pagina,
            'paginas' => $paginas,
        ];
    }

    /**
     * Perfil público completo, a partir do slug. Retorna null se não existir ou não estiver ativo.
     */
    public function perfilPorSlug(string $slug): ?object
    {
        $perfil = $this->db->table('profissionais p')
            ->select('p.*, u.foto_perfil, u.email')
            ->select('(SELECT ROUND(AVG(a.nota), 1) FROM avaliacoes a WHERE a.profissional_id = p.id) AS media', false)
            ->select('(SELECT COUNT(*) FROM avaliacoes a2 WHERE a2.profissional_id = p.id) AS total_avaliacoes', false)
            ->select('cid.nome AS cidade_nome, uf.sigla AS uf, COALESCE(bai.nome, e.bairro_outro) AS bairro_nome')
            ->join('usuarios u', "u.id = p.id AND u.tipo = 'PROFISSIONAL' AND u.status = 'ATIVO'")
            ->join('enderecos e', 'e.id = (SELECT MAX(e2.id) FROM enderecos e2 WHERE e2.usuario_id = p.id)', 'left')
            ->join('localidades cid', 'cid.id = e.cidade_id', 'left')
            ->join('localidades uf', 'uf.id = cid.localidade_pai_id', 'left')
            ->join('localidades bai', 'bai.id = e.bairro_id', 'left')
            ->where('p.slug', $slug)
            ->get()
            ->getRow();

        if ($perfil === null) {
            return null;
        }

        $perfil->categorias = $this->categoriasDoProfissional((int) $perfil->id);
        $perfil->locais     = $this->locaisDoProfissional((int) $perfil->id);
        $perfil->horarios   = $this->horariosDoProfissional((int) $perfil->id);

        return $perfil;
    }

    /** @return list<object> id, nome */
    public function categoriasDoProfissional(int $profissionalId): array
    {
        return $this->db->table('profissional_categoria pc')
            ->select('c.id, c.nome')
            ->join('categorias c', 'c.id = pc.categoria_id')
            ->where('pc.profissional_id', $profissionalId)
            ->orderBy('c.nome', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Áreas de atuação com rótulo legível ("Centro, Muriaé - MG", "Muriaé - MG", "Minas Gerais").
     *
     * @return list<object> id (da linha de profissional_locais_atendimento), localidade_id, tipo, rotulo
     */
    public function locaisDoProfissional(int $profissionalId): array
    {
        $linhas = $this->db->table('profissional_locais_atendimento pl')
            ->select('pl.id, pl.localidade_id, l.tipo, l.nome, l.sigla, pai.nome AS pai_nome, pai.sigla AS pai_sigla, pai.tipo AS pai_tipo, avo.sigla AS avo_sigla')
            ->join('localidades l', 'l.id = pl.localidade_id')
            ->join('localidades pai', 'pai.id = l.localidade_pai_id', 'left')
            ->join('localidades avo', 'avo.id = pai.localidade_pai_id', 'left')
            ->where('pl.profissional_id', $profissionalId)
            ->orderBy('l.tipo', 'ASC')
            ->orderBy('l.nome', 'ASC')
            ->get()
            ->getResult();

        foreach ($linhas as $linha) {
            $linha->rotulo = match ($linha->tipo) {
                'BAIRRO'    => $linha->nome . ', ' . $linha->pai_nome . ($linha->avo_sigla ? ' - ' . $linha->avo_sigla : ''),
                'MUNICIPIO' => $linha->nome . ($linha->pai_sigla ? ' - ' . $linha->pai_sigla : ''),
                default     => $linha->nome,
            };
        }

        return $linhas;
    }

    /**
     * Horários agrupados por dia, na ordem da semana.
     *
     * @return array<string, list<object>> DIA => [ {abre, fecha}, ... ]
     */
    public function horariosDoProfissional(int $profissionalId): array
    {
        $linhas = $this->db->table('profissional_horarios')
            ->select('dia_semana, abre, fecha')
            ->where('profissional_id', $profissionalId)
            ->orderBy('abre', 'ASC')
            ->get()
            ->getResult();

        $ordem = ['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SABADO', 'DOMINGO'];

        $agrupado = [];
        foreach ($ordem as $dia) {
            foreach ($linhas as $linha) {
                if ($linha->dia_semana === $dia) {
                    $agrupado[$dia][] = (object) [
                        'abre'  => substr($linha->abre, 0, 5),
                        'fecha' => substr($linha->fecha, 0, 5),
                    ];
                }
            }
        }

        return $agrupado;
    }

    /** @return list<object> id, nome */
    public function categorias(): array
    {
        return $this->db->table('categorias')->select('id, nome')->orderBy('nome', 'ASC')->get()->getResult();
    }

    /**
     * Cidades que têm ao menos um profissional ativo (pelo endereço ou por área de atuação).
     *
     * @return list<object> id, nome, uf
     */
    public function cidadesComProfissionais(): array
    {
        $sql = "
            SELECT DISTINCT c.id, c.nome, uf.sigla AS uf
            FROM localidades c
            JOIN localidades uf ON uf.id = c.localidade_pai_id
            WHERE c.tipo = 'MUNICIPIO'
              AND c.id IN (
                  SELECT e.cidade_id
                  FROM enderecos e
                  JOIN usuarios u ON u.id = e.usuario_id AND u.tipo = 'PROFISSIONAL' AND u.status = 'ATIVO'
                  UNION
                  SELECT pl.localidade_id
                  FROM profissional_locais_atendimento pl
                  JOIN usuarios u ON u.id = pl.profissional_id AND u.status = 'ATIVO'
              )
            ORDER BY c.nome ASC
        ";

        return $this->db->query($sql)->getResult();
    }

    /** Cidade do endereço cadastrado do usuário (profissional ou contratante), ou null. */
    public function cidadeDoUsuario(int $usuarioId): ?object
    {
        return $this->db->table('enderecos e')
            ->select('c.id, c.nome, uf.sigla AS uf')
            ->join('localidades c', "c.id = e.cidade_id AND c.tipo = 'MUNICIPIO'")
            ->join('localidades uf', 'uf.id = c.localidade_pai_id', 'left')
            ->where('e.usuario_id', $usuarioId)
            ->orderBy('e.id', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
    }

    public function cidadePorId(int $cidadeId): ?object
    {
        return $this->db->table('localidades c')
            ->select('c.id, c.nome, uf.sigla AS uf')
            ->join('localidades uf', 'uf.id = c.localidade_pai_id', 'left')
            ->where('c.id', $cidadeId)
            ->where('c.tipo', 'MUNICIPIO')
            ->get()
            ->getRow();
    }

    /** @return list<object> id, nome */
    public function bairrosDaCidade(int $cidadeId): array
    {
        return $this->db->table('localidades')
            ->select('id, nome')
            ->where('tipo', 'BAIRRO')
            ->where('localidade_pai_id', $cidadeId)
            ->orderBy('nome', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Monta FROM + JOINs + WHERE comuns à contagem e à listagem.
     */
    protected function base(array $filtros): BaseBuilder
    {
        $b = $this->db->table('profissionais p')
            ->join('usuarios u', "u.id = p.id AND u.tipo = 'PROFISSIONAL' AND u.status = 'ATIVO'")
            ->join('enderecos e', 'e.id = (SELECT MAX(e2.id) FROM enderecos e2 WHERE e2.usuario_id = p.id)', 'left')
            ->join('localidades cid', 'cid.id = e.cidade_id', 'left')
            ->join('localidades uf', 'uf.id = cid.localidade_pai_id', 'left')
            ->join('localidades bai', 'bai.id = e.bairro_id', 'left');

        $categoria = (int) ($filtros['categoria'] ?? 0);
        $cidade    = (int) ($filtros['cidade'] ?? 0);
        $bairro    = (int) ($filtros['bairro'] ?? 0);
        $nota      = (int) ($filtros['nota'] ?? 0);

        if ($categoria > 0) {
            $b->where("EXISTS (SELECT 1 FROM profissional_categoria pc WHERE pc.profissional_id = p.id AND pc.categoria_id = {$categoria})", null, false);
        }

        if ($bairro > 0) {
            // Atende o bairro: mora nele ou cadastrou o bairro, a cidade ou o estado dele como área de atuação.
            $b->where(
                "(e.bairro_id = {$bairro} OR EXISTS (
                    SELECT 1 FROM profissional_locais_atendimento pl
                    WHERE pl.profissional_id = p.id
                      AND pl.localidade_id IN (
                          {$bairro},
                          (SELECT localidade_pai_id FROM localidades WHERE id = {$bairro}),
                          (SELECT c2.localidade_pai_id FROM localidades b2 JOIN localidades c2 ON c2.id = b2.localidade_pai_id WHERE b2.id = {$bairro})
                      )
                ))",
                null,
                false
            );
        } elseif ($cidade > 0) {
            // Atende a cidade: mora nela ou cadastrou a cidade, um bairro dela ou o estado como área de atuação.
            $b->where(
                "(e.cidade_id = {$cidade} OR EXISTS (
                    SELECT 1 FROM profissional_locais_atendimento pl
                    JOIN localidades l ON l.id = pl.localidade_id
                    WHERE pl.profissional_id = p.id
                      AND (l.id = {$cidade}
                           OR l.localidade_pai_id = {$cidade}
                           OR l.id = (SELECT localidade_pai_id FROM localidades WHERE id = {$cidade}))
                ))",
                null,
                false
            );
        }

        if ($nota >= 1 && $nota <= 5) {
            $b->where("(SELECT AVG(a3.nota) FROM avaliacoes a3 WHERE a3.profissional_id = p.id) >= {$nota}", null, false);
        }

        return $b;
    }
}
