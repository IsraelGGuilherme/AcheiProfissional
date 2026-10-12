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
}
