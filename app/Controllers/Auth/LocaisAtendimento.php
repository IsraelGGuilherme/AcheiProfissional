<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\LocalidadesModel;
use App\Models\ProfissionalLocaisAtendimentoModel;
use App\Models\UsuariosModel;

class LocaisAtendimento extends BaseController
{
    public function formLocaisAtendimento()
    {
        $localidadesModel = model(LocalidadesModel::class);
        $paises = $localidadesModel->where('tipo', 'PAIS')->findAll();
        
        return view('Auth/Register/locaisAtendimento', [
            'paises' => $paises
        ]);
    }

    public function cadastrarLocais()
    {
        $params = $this->request->getPost();
        
        $rules = [
            'locais' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Selecione pelo menos um local de atendimento.');
        }

        $locais = json_decode($params['locais'], true);
        
        if (!is_array($locais) || empty($locais)) {
            return redirect()->back()->with('error', 'Formato de locais inválido.');
        }

        $profissionalId = session('usuario')->id;
        $locaisModel = model(ProfissionalLocaisAtendimentoModel::class);

        try {
            $db = db_connect();
            $db->transStart();

            // Deleta locais anteriores (caso esteja refazendo/editando no futuro)
            $locaisModel->where('profissional_id', $profissionalId)->delete();

            // Insere os novos
            $dataInsert = [];
            foreach ($locais as $localidadeId) {
                $dataInsert[] = [
                    'profissional_id' => $profissionalId,
                    'localidade_id'   => $localidadeId
                ];
            }
            $locaisModel->insertBatch($dataInsert);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erro ao salvar os locais de atendimento.');
            }
        } catch (\Throwable $e) {
            log_message('error', '[LocaisAtendimento] Erro ao salvar locais: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()->with('error', 'Ocorreu um erro ao salvar as localidades.');
        }

        return redirect()->to('profissional/horarios');
    }
}
