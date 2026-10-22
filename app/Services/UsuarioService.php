<?php

namespace App\Services;

use App\Models\UsuariosModel;
use App\Models\BloqueioModel;

class UsuarioService
{
    private $userModel;
    private $bloqueioModel;
    private bool $emailBloqueioEnviado = false;

    public function __construct()
    {
        $this->userModel = new UsuariosModel();
        $this->bloqueioModel = new BloqueioModel();
    }

    /** Informa se o e-mail de aviso da última chamada de bloquear_usuario() foi entregue ao servidor de e-mail. */
    public function emailBloqueioEnviado(): bool
    {
        return $this->emailBloqueioEnviado;
    }

    /**
     * Registra o bloqueio, atualiza o status da conta e avisa o usuário por e-mail.
     * Administradores não podem ser bloqueados por esta rotina.
     * Uma falha no envio do e-mail não desfaz o bloqueio (veja emailBloqueioEnviado()).
     */
    public function bloquear_usuario($userId, $motivo): bool
    {
        $this->emailBloqueioEnviado = false;

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

        $this->emailBloqueioEnviado = $this->notificarBloqueio((int) $userId, (string) $usuario->email, (string) $motivo);

        return true;
    }

    private function notificarBloqueio(int $userId, string $email, string $motivo): bool
    {
        if ($email === '') {
            return false;
        }

        try {
            return service('emailService')->enviarBloqueioConta(
                $email,
                $this->userModel->buscarNome($userId),
                $motivo
            );
        } catch (\Throwable $e) {
            log_message('error', '[UsuarioService] Erro ao enviar e-mail de bloqueio para o usuário {id}: {erro}', [
                'id'   => $userId,
                'erro' => $e->getMessage(),
            ]);

            return false;
        }
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
