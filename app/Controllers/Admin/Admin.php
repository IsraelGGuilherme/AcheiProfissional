<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;

class Admin extends BaseController
{


    public function index()
    {
        helper('profissional');
        $data = [];

        $usuariosModel = model('UsuariosModel');
        $categoriaModel = model('CategoriasModel');
        
        $usuarios = $usuariosModel->listarUsuarios();


        /*
            trazer a quantidade de profissionais e usuarios comuns 
        */
        return view(
            'Admin/main',
            [
                'users' => $usuarios,
                'qtd_profissionais' => $usuariosModel->where('tipo', 'PROFISSIONAL')->countAllResults(),
                'qtd_contratantes'  => $usuariosModel->where('tipo', 'COMUM')->countAllResults(),
                'categorias' => $categoriaModel->findAll(20)
            ]
        );
    }

    public function bloqueio_user()
    {
        $validator = service('validation');
        $data = $this->request->getPost();

        $validator->setRules([
            'tempo_bloqueio' => 'required|in_list[1H,24H,3D,7D,15D,1M,PERSONALIZADO]',
            'user_id' => 'required|integer',
            'data_personalizada' => 'permit_empty|valid_date[Y-m-d\TH:i]',
            'motivo' => 'required|min_length[10]'
        ]);

        if (!$validator->run($data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $validator->getErrors());
        }
        $dataAtual = Time::parse(Time::now());

        switch ($data['tempo_bloqueio']) {
            case '1H':
                $dataAtual->addHours(1);
                break;
            case '24H':
                $dataAtual->addDays(1);
                break;
            case '3D':
                $dataAtual->addDays(3);
                break;
            case '7D':
                $dataAtual->addDays(7);
                break;
            case '15D':
                $dataAtual->addDays(15);
                break;
            case '1M':
                $dataAtual->addMonths(1);
                break;
            // Quando selecionado personalizado no select pega a data do campo datetime no front-end
            case 'PERSONALIZADO':
                $dataAtual = Time::parse($data['data_personalizada']);
                break;
        }

        $dataDesbloqueio = $dataAtual->toDateTimeString();
        $userService = service('usuarioService');
        $userService->bloquear_usuario($data['user_id'], $dataDesbloqueio, $data['motivo']);
        return redirect()->to(site_url('admin'))
            ->with('success', 'Usuário bloqueado com sucesso.');
    }

    public function excluir_usuario(int $id)
    {
        $usuariosModel = model('UsuariosModel');
        $usuario = $usuariosModel->find($id);

        if (! $usuario || ! in_array($usuario->tipo, ['COMUM', 'PROFISSIONAL'], true)) {
            return redirect()->to(site_url('admin'))
                ->with('error', ['Usuário não encontrado.']);
        }

        $usuariosModel->delete($id);

        return redirect()->to(site_url('admin'))
            ->with('success', 'Usuário removido com sucesso.');
    }
}
