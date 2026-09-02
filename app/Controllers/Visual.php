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

    public function perfil(string $slug): string
    {
        $profissional = $this->dados->profissionalPorSlug($slug);

        if ($profissional === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Profissional não encontrado.');
        }

        return view('profissional_detalhe', ['profissional' => $profissional]);
    }

    public function login(): string
    {
        return view('login', ['titulo' => 'Login - Achei Profissional']);
    }

    public function cadastro(): string
    {
        return view('cadastro');
    }

    public function recuperarSenha(): string
    {
        return view('recuperar_senha');
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

    public function categorias(): string
    {
        return view('categorias');
    }

    public function denuncias(): string
    {
        return view('denuncias');
    }
}
