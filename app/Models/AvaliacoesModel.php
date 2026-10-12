<?php

namespace App\Models;

use CodeIgniter\Model;

class AvaliacoesModel extends Model
{
    protected $table            = 'avaliacoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['profissional_id', 'usuario_comum_id', 'nota', 'comentario'];

    protected $useTimestamps = false; // created_at/updated_at são preenchidos pelo próprio banco
}
