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
        $this->bloqueioModel = new BloqueioModel();
    }

    /**
     * Registra o bloqueio e atualiza o status da conta.
     * Administradores não podem ser bloqueados por esta rotina.
     */
    public function bloquear_usuario($userId, $motivo): bool
    {
        $usuario = $this->userModel->find($userId);

        if (!$usuario || !in_array($usuario->tipo, ['COMUM', 'PROFISSIONAL'], true)) {
            return false;
        }

        $statusAnterior = $usuario->status;

        if (!$this->userModel->update($userId, ['status' => 'BLOQUEADO'])) {
            return false;
        }

        $bloqueioId = $this->bloqueioModel->insert([
            'profissional_id' => $userId,
            'motivo' => $motivo,
            'dt_desbloqueio' => null,
        ]);

        if (!$bloqueioId) {
            $this->userModel->update($userId, ['status' => $statusAnterior]);
            return false;
        }

        return true;
    }

    /** Desbloqueia uma conta de usuário comum ou profissional. */
    public function desbloquear_usuario($userId): bool
    {
        $usuario = $this->userModel->find($userId);

        if (!$usuario || !in_array($usuario->tipo, ['COMUM', 'PROFISSIONAL'], true)) {
            return false;
        }

        if ($usuario->status !== 'BLOQUEADO') {
            return false;
        }

        return (bool) $this->userModel->update($userId, ['status' => 'ATIVO']);
    }
}
