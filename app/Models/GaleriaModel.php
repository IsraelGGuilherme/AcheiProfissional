<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriaModel extends Model
{
    protected $table            = 'profissional_imagens_galeria';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['profissional_id', 'arquivo', 'descricao', 'ordem'];

    protected $useTimestamps = false; // created_at/updated_at são preenchidos pelo próprio banco
}
