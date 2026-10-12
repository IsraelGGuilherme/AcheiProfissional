<?php

namespace App\Controllers\Perfil;

use App\Controllers\BaseController;

/**
 * /perfil -> leva cada tipo de usuário para a sua tela de perfil.
 */
class Inicio extends BaseController
{
    public function index()
    {
        $tipo = session('usuario')->tipo;

        if ($tipo === 'PROFISSIONAL') {
            return redirect()->to('/perfil/profissional');
        }

        if ($tipo === 'COMUM') {
            return redirect()->to('/perfil/contratante');
        }

        return redirect()->to('/');
    }
}
