<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\EnderecosModel;
use App\Models\LocalidadesModel;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\ResponseInterface;

class Enderecos extends BaseController
{
    protected LocalidadesModel $localidadesModel;
    protected EnderecosModel $enderecosModel;
    protected UsuariosModel $usuariosModel;

    public function __construct() {
        $this->localidadesModel = model(LocalidadesModel::class);
        $this->enderecosModel = model(EnderecosModel::class);
        $this->usuariosModel = model(UsuariosModel::class);
    }

    public function formCadastrarEndereco()
    {
        return view('Auth/Enderecos/formCadastrarEndereco', [
            'paises' => $this->localidadesModel->where('tipo', 'pais')->findAll(),
        ]);
    }

    public function cadastrarEndereco()
    {
        $params = $this->request->getPost();

        $params['usuario_id'] = (int) session()->get('usuario')->id;

        $rules = [
            'cep' => 'required|exact_length[8]',
            'logradouro' => 'required|max_length[255]',
            'numero' => 'required|max_length[20]',
            'complemento' => 'permit_empty|max_length[255]',
            'cidade_id' => 'required|integer',
            'bairro_id' => 'permit_empty|integer',
            'bairro_outro' => 'permit_empty|max_length[150]',
            'latitude' => 'permit_empty|decimal',
            'longitude' => 'permit_empty|decimal',
        ];

        if (empty($params['bairro_id']) || $params['bairro_id'] === 'outro') {
            $params['bairro_id'] = null;
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        try {
            $db = db_connect();
            $db->transStart();

            $this->enderecosModel->insert($params);

            $this->usuariosModel->update($params['usuario_id'],[
                'status' => 'ATIVO'
            ]);
            $db->transComplete();
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()
                ->with(
                    'error',
                    'Ocorreu um erro ao cadastrar o endereço. Por favor, tente novamente mais tarde.'
                );
        }

        return redirect('/')->with('success', 'Endereço cadastrado com sucesso!');
    }

    public function getFilhos($paisId): ResponseInterface
    {
        $filhos = $this->localidadesModel
            ->where('localidade_pai_id', $paisId)
            ->findAll();

        return $this->response->setJSON($filhos);
    }
}
