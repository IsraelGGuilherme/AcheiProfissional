<?php

namespace App\Services;

use App\Models\UsuarioCodigosEmailModel;
use App\Models\UsuariosModel;

class AuthService
{
    protected UsuariosModel $usuariosModel;
    protected UsuarioCodigosEmailModel $usuarioCodigosEmailModel;

    public function __construct()
    {
        $this->usuariosModel = model(UsuariosModel::class);
        $this->usuarioCodigosEmailModel = model(UsuarioCodigosEmailModel::class);
    }

    public function login(string $email, string $senha): bool
    {
        $usuario = $this->usuariosModel->where('email', $email)->first();

        if (!$usuario || !password_verify($senha, $usuario->senha)) {
            return false;
        }

        session()->regenerate();
        session()->set('usuario', $usuario);

        return true;
    }

    public function estaLogado(bool $semPendencias = true): bool
    {
        if (!$semPendencias) {
            return session()->has('usuario');
        }
        
        $usuario = session('usuario');
        return $usuario && $usuario->status === 'ATIVO';
    }

    public function montarSessaoUsuario(string $email): void
    {
        $usuario = $this->usuariosModel->where('email', $email)->first();

        if (!$usuario) {
            throw new \RuntimeException("Usuário não encontrado para email: {$email}");
        }

        session()->regenerate();

        session()->set('usuario', $usuario);
    }
}