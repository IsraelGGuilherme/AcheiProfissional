<?php

namespace App\Services;

use App\Models\UsuariosModel;
use App\Models\BloqueioModel;

class UsuarioService
{
    private $userModel;
    private $bloqueioModel;

    public function __construct()
    {
        $this->userModel = new UsuariosModel();
        $this->bloqueioModel =new BloqueioModel();
    }

    public function bloquear_usuario($userId,$dataDesbloquear,$motivo)
    {
        if ($this->userModel->where('id',$userId)->countAllResults() <= 0) {
            return false;
        }
                 
        if ($this->bloqueioModel->insert([
            'profissional_id' => $userId,
            'motivo' => $motivo,
            'dt_desbloqueio' => $dataDesbloquear
        ])  <= 0) {
            return false;
        }
        

        return true;
    }
}