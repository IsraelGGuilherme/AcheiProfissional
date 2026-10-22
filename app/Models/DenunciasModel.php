<?php

namespace App\Models;

use CodeIgniter\Model;

class DenunciasModel extends Model
{
    protected $table            = 'denuncias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['profissional_id', 'usuario_id', 'avaliacao_id', 'motivo', 'descricao', 'status'];

    protected $useTimestamps = false; // created_at/updated_at são preenchidos pelo próprio banco

    /** Valor do enum denuncias.motivo => rótulo exibido no painel admin */
    public const MOTIVOS = [
        'CONTEUDO_INADEQUADO' => 'Conteúdo inadequado',
        'INFORMACAO_FALSA'    => 'Informações falsas ou enganosas',
        'SPAN'                => 'Spam',
        'FRAUDE'              => 'Contato ou anúncio suspeito',
        'OFENSA'              => 'Comportamento inadequado',
        'OUTRO'               => 'Outro motivo',
    ];

    /** Valor do enum denuncias.status => rótulo */
    public const STATUS = [
        'PENDENTE'   => 'Pendente',
        'EM_ANALISE' => 'Em análise',
        'RESOLVIDA'  => 'Resolvida',
        'REJEITADA'  => 'Rejeitada',
    ];

    /**
     * Quem é o usuário denunciado em cada linha de `denuncias`:
     *  - denúncia de avaliação (avaliacao_id preenchido): o autor da avaliação;
     *  - denúncia de perfil: o profissional (profissional_id).
     * Exige o alias `d` (denuncias) e `a` (avaliacoes, LEFT JOIN).
     * NULL = a conta denunciada foi excluída.
     */
    private const DENUNCIADO = 'CASE WHEN d.avaliacao_id IS NOT NULL THEN a.usuario_comum_id ELSE d.profissional_id END';

    /**
     * Uma linha por usuário denunciado, com a contagem de denúncias.
     * Os que têm denúncias pendentes aparecem primeiro; depois, os mais recentes.
     *
     * @param string $busca parte do nome do denunciado
     */
    public function agruparPorDenunciado(string $busca = ''): array
    {
        $where = $busca !== ''
            ? "WHERE COALESCE(uc.nome, p.nome) LIKE '%" . $this->db->escapeLikeString($busca) . "%' ESCAPE '!'"
            : '';

        $denunciado = self::DENUNCIADO;

        $sql = "
            SELECT
                g.denunciado_id,
                g.total,
                g.pendentes,
                g.em_analise,
                g.ultima_em,
                u.tipo,
                u.status AS status_conta,
                COALESCE(uc.nome, p.nome) AS nome
            FROM (
                SELECT
                    x.denunciado_id,
                    COUNT(*) AS total,
                    SUM(x.status = 'PENDENTE') AS pendentes,
                    SUM(x.status = 'EM_ANALISE') AS em_analise,
                    MAX(x.created_at) AS ultima_em
                FROM (
                    SELECT d.status, d.created_at, {$denunciado} AS denunciado_id
                    FROM denuncias d
                    LEFT JOIN avaliacoes a ON a.id = d.avaliacao_id
                ) x
                GROUP BY x.denunciado_id
            ) g
            LEFT JOIN usuarios u ON u.id = g.denunciado_id
            LEFT JOIN usuarios_comuns uc ON uc.id = u.id
            LEFT JOIN profissionais p ON p.id = u.id
            {$where}
            ORDER BY g.pendentes DESC, g.ultima_em DESC
        ";

        return $this->db->query($sql)->getResultObject();
    }

    /** Dados básicos do usuário denunciado (null se a conta não existe mais). */
    public function buscarDenunciado(int $usuarioId): ?object
    {
        $sql = "
            SELECT
                u.id,
                u.email,
                u.tipo,
                u.status,
                COALESCE(uc.nome, p.nome) AS nome,
                p.slug
            FROM usuarios u
            LEFT JOIN usuarios_comuns uc ON uc.id = u.id
            LEFT JOIN profissionais p ON p.id = u.id
            WHERE u.id = ?
        ";

        return $this->db->query($sql, [$usuarioId])->getRow();
    }

    /**
     * Todas as denúncias contra um usuário, da mais recente para a mais antiga.
     * Passe null para listar as denúncias cuja conta denunciada foi excluída.
     */
    public function listarDoDenunciado(?int $usuarioId): array
    {
        $condicao = $usuarioId === null
            ? self::DENUNCIADO . ' IS NULL'
            : self::DENUNCIADO . ' = ?';

        $sql = "
            SELECT
                d.id,
                d.motivo,
                d.descricao,
                d.status,
                d.created_at,
                d.avaliacao_id,
                d.usuario_id AS denunciante_id,
                ud.tipo AS denunciante_tipo,
                COALESCE(ucd.nome, pd.nome) AS denunciante_nome,
                a.id AS avaliacao_existe,
                a.nota AS avaliacao_nota,
                a.comentario AS avaliacao_comentario,
                pa.nome AS avaliacao_profissional
            FROM denuncias d
            LEFT JOIN avaliacoes a ON a.id = d.avaliacao_id
            LEFT JOIN profissionais pa ON pa.id = a.profissional_id
            LEFT JOIN usuarios ud ON ud.id = d.usuario_id
            LEFT JOIN usuarios_comuns ucd ON ucd.id = ud.id
            LEFT JOIN profissionais pd ON pd.id = ud.id
            WHERE {$condicao}
            ORDER BY d.created_at DESC, d.id DESC
        ";

        return $this->db->query($sql, $usuarioId === null ? [] : [$usuarioId])->getResultObject();
    }
}
