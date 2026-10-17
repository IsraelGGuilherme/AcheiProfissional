<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email', 'senha', 'tipo', 'status', 'email_verificado_em', 'foto_perfil'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['criptografarSenha'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['criptografarSenha'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function criptografarSenha(array $data): array
    {
        if (isset($data['data']['senha'])) {
            $data['data']['senha'] = password_hash($data['data']['senha'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function listarUsuarios()
    {
        $sql = "
            SELECT 
                u.id,
                u.tipo,
                u.status,
                COALESCE(uc.nome, p.nome) AS nome,

                CASE
                    WHEN u.tipo = 'PROFISSIONAL' THEN (
                        SELECT c.nome
                        FROM profissional_categoria pc
                        INNER JOIN categorias c
                            ON c.id = pc.categoria_id
                        WHERE pc.profissional_id = u.id
                        LIMIT 1
                    )
                END AS categoria,

                CASE
                    WHEN u.tipo = 'PROFISSIONAL' THEN (
                        SELECT AVG(av.nota)
                        FROM avaliacoes av
                        WHERE av.profissional_id = u.id
                    )
                END AS media_notas,

                CASE
                    WHEN u.tipo = 'COMUM' THEN (
                        SELECT COUNT(*)
                        FROM denuncias dn
                        WHERE dn.usuario_id = u.id
                    )
                END AS qtd_denucias

            FROM usuarios u
            LEFT JOIN usuarios_comuns uc
                ON uc.id = u.id
            LEFT JOIN profissionais p
                ON p.id = u.id;
            ";

        return $this->db->query($sql)->getResultObject();
    }
}
