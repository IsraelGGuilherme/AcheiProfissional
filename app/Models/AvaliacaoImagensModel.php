<?php

namespace App\Models;

use CodeIgniter\Model;

class AvaliacaoImagensModel extends Model
{
    protected $table            = 'avaliacoes_imagens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['avaliacao_id', 'arquivo'];

    protected $useTimestamps = false; // created_at/updated_at são preenchidos pelo próprio banco
}
