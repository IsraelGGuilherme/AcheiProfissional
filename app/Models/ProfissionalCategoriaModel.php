<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfissionalCategoriaModel extends Model
{
    protected $table            = 'profissional_categoria';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['profissional_id', 'categoria_id'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
