<?php

namespace App\Controllers;

use App\Models\DemoDataModel;

class Visual extends BaseController
{
    private DemoDataModel $dados;

    public function __construct()
    {
        $this->dados = new DemoDataModel();
    }

    public function busca(): string
    {
        return view('busca', $this->dados->busca());
    }

    public function login(): string
    {
        return view('login', [
            'titulo' => 'Login - Achei Profissional',
        ]);
    }

    public function perfilContratante(): string
    {
        return view('perfil_contratante', $this->dados->perfilContratante());
    }

    public function perfilProfissional(): string
    {
        return view('perfil_profissional', $this->dados->perfilProfissional());
    }

    public function admin(): string
    {
        return view('admin', $this->dados->administracao());
    }
}
