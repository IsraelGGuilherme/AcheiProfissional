<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\CategoriasModel;
use App\Models\ProfissionalCategoriaModel;

class Categorias extends BaseController
{
    public function formCategorias()
    {
        $categoriasModel = model(CategoriasModel::class);
        
        // Puxando as categorias em ordem alfabética
        $categorias = $categoriasModel->orderBy('nome', 'ASC')->findAll();

        return view('Auth/Register/categoriasProfissional', [
            'categorias' => $categorias
        ]);
    }

    public function cadastrarCategorias()
    {
        $params = $this->request->getPost();

        // TomSelect submete um array quando é multiple
        $rules = [
            'categorias' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Selecione pelo menos uma categoria.');
        }

        $categorias = $params['categorias'];
        
        if (!is_array($categorias)) {
            $categorias = [$categorias];
        }

        if (empty($categorias)) {
            return redirect()->back()->with('error', 'Formato inválido. Selecione pelo menos uma categoria.');
        }

        $profissionalId = session('usuario')->id;
        $profCategoriaModel = model(ProfissionalCategoriaModel::class);

        try {
            $db = db_connect();
            $db->transStart();

            // Remove as antigas para este profissional
            $profCategoriaModel->where('profissional_id', $profissionalId)->delete();

            // Insere as novas
            $dataInsert = [];
            foreach ($categorias as $catId) {
                $dataInsert[] = [
                    'profissional_id' => $profissionalId,
                    'categoria_id'    => $catId
                ];
            }
            
            $profCategoriaModel->insertBatch($dataInsert);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erro ao salvar categorias.');
            }
        } catch (\Throwable $e) {
            log_message('error', '[Categorias] Erro ao salvar categorias do profissional: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar as categorias.');
        }

        // Redireciona para o próximo passo no fluxo de cadastro (Endereço)
        return redirect()->to('cadastrarendereco');
    }
}
