<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Admin extends BaseController
{


    public function index()
    {
        helper('profissional');
        $data = [];

        $usuariosModel = model('UsuariosModel');

        $usuarios = $usuariosModel->listarUsuarios();


        /*
            trazer a quantidade de profissionais e usuarios comuns 
        */
        return view('Admin/main',
                        [
                            'users' => $usuarios,
                            'qtd_profissionais' => $usuariosModel->where('tipo','PROFISSIONAL')->countAllResults(),
                            'qtd_contratantes'   => $usuariosModel->where('tipo','COMUM')->countAllResults()
                        ]
                    );
    }

    public function bloqueio_user()
    {
        $data = $this->request->getPost();
        var_dump($data);

        


        die();
    }
}
