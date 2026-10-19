<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Administradores extends BaseController
{
    public function index()
    {
        return view('Administradores/lista_admin');
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

        if (!$admin) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }

        return view('Administradores/form_admin',
            [
                'admins' => $admin,
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
}
