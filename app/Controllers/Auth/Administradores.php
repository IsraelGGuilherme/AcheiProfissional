<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Administradores extends BaseController
{
    public function index()
    {
        return view('Administradores/lista_admin', [
            'admins' => model('UsuariosModel')->where('tipo', 'ADMIN')->orderBy('email', 'ASC')->findAll(),
        ]);
    }

    public function form_admin()
    {
        return view('Administradores/form_admin',
            [
                'action' => base_url('register/admin')
            ]
        );
    }

    public function form_admin_data($id = null)
    {
        $userModel = model('UsuariosModel');
        $admin = $userModel->find($id);

        if (!$admin || $admin->tipo !== 'ADMIN') {
            return redirect()->to('admin/administradores')->with('error', 'Administrador não encontrado.');
        }

        return view('Administradores/form_admin',
            [
                'admins' => $admin,
                'action' => base_url('admin/administradores/editar'),
            ]
        );
    }

    public function inserir()
    {
       $post = request()->getPost();
       var_dump($post);

       $this->validator->setRules([
            'email'     => 'required|email_valid',
            'tipo'      => 'required|in_list[ADMIN]',
            'status'    => 'required|in_list[ATIVO,INATIVO]'
       ]);
    }

    /**
     * Atualiza e-mail, status e (opcionalmente) a senha de um administrador.
     * A criação continua em Auth\Register::register_admin (já funcionava).
     */
    public function editar()
    {
        $post  = $this->request->getPost();
        $id    = (int) ($post['id'] ?? 0);
        $model = model('UsuariosModel');
        $admin = $model->find($id);
        $lista = 'admin/administradores';

        if (!$admin || $admin->tipo !== 'ADMIN') {
            return redirect()->to($lista)->with('error', 'Administrador não encontrado.');
        }

        $rules = [
            'email'           => "required|valid_email|max_length[191]|is_unique[usuarios.email,id,{$id}]",
            'status'          => 'required|in_list[ATIVO,BLOQUEADO]',
            'senha'           => 'permit_empty|min_length[8]',
            'confirmar_senha' => 'permit_empty|matches[senha]',
        ];
        $messages = [
            'email'           => ['is_unique' => 'Já existe uma conta cadastrada para esse e-mail.'],
            'confirmar_senha' => ['matches' => 'A senha e a confirmação da senha devem ser iguais.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        if ($post['status'] === 'BLOQUEADO' && $id === (int) session('usuario')->id) {
            return redirect()->back()->withInput()->with('error', 'Você não pode bloquear a sua própria conta.');
        }

        $dados = ['email' => $post['email'], 'status' => $post['status']];

        // Senha em branco = mantém a atual (o hash é feito pelo callback do UsuariosModel)
        if (!empty($post['senha'])) {
            $dados['senha'] = $post['senha'];
        }

        $model->update($id, $dados);

        return redirect()->to($lista)->with('success', 'Administrador atualizado com sucesso.');
    }

    public function excluir()
    {
        $id    = (int) $this->request->getPost('id');
        $model = model('UsuariosModel');
        $admin = $model->find($id);
        $lista = 'admin/administradores';

        if (!$admin || $admin->tipo !== 'ADMIN') {
            return redirect()->to($lista)->with('error', 'Administrador não encontrado.');
        }

        if ($id === (int) session('usuario')->id) {
            return redirect()->to($lista)->with('error', 'Você não pode excluir a sua própria conta.');
        }

        if ($model->where('tipo', 'ADMIN')->countAllResults() <= 1) {
            return redirect()->to($lista)->with('error', 'Não é possível excluir o último administrador.');
        }

        $model->delete($id);

        return redirect()->to($lista)->with('success', 'Administrador excluído com sucesso.');
    }
}
