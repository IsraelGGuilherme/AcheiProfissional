<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\ProfissionalHorariosModel;
use App\Models\UsuariosModel;

class HorariosAtendimento extends BaseController
{
    public function formHorariosAtendimento()
    {
        return view('Auth/Register/horariosAtendimento');
    }

    public function cadastrarHorarios()
    {
        $params = $this->request->getPost();
        
        // Verifica se selecionou ao menos um dia
        if (!isset($params['dias_marcados']) || empty($params['dias_marcados'])) {
            return redirect()->back()->with('error', 'Selecione pelo menos um dia de atendimento.');
        }

        $diasMarcados = $params['dias_marcados'];
        $horariosInput = $params['horarios'] ?? [];

        $profissionalId = session('usuario')->id;
        $horariosModel = model(ProfissionalHorariosModel::class);
        $usuariosModel = model(UsuariosModel::class);

        try {
            $db = db_connect();
            $db->transStart();

            // Deleta horários anteriores para não duplicar se estiver editando no futuro
            $horariosModel->where('profissional_id', $profissionalId)->delete();

            $dataInsert = [];
            
            foreach ($diasMarcados as $dia) {
                if (isset($horariosInput[$dia]['abre']) && isset($horariosInput[$dia]['fecha'])) {
                    $aberturas = $horariosInput[$dia]['abre'];
                    $fechamentos = $horariosInput[$dia]['fecha'];
                    
                    for ($i = 0; $i < count($aberturas); $i++) {
                        $abre = $aberturas[$i] ?? null;
                        $fecha = $fechamentos[$i] ?? null;
                        
                        if (!empty($abre) && !empty($fecha)) {
                            // Validação simples de horário
                            if (strtotime($abre) >= strtotime($fecha)) {
                                throw new \Exception("No dia {$dia}, o horário de fechamento ({$fecha}) deve ser maior que o de abertura ({$abre}).");
                            }

                            $dataInsert[] = [
                                'profissional_id' => $profissionalId,
                                'dia_semana'      => $dia,
                                'abre'            => $abre,
                                'fecha'           => $fecha
                            ];
                        }
                    }
                }
            }
            
            if (empty($dataInsert)) {
                throw new \Exception('Nenhum turno de horário válido foi informado.');
            }

            $horariosModel->insertBatch($dataInsert);

            // Última etapa do cadastro, atualiza status do usuário para ATIVO
            $usuariosModel->update($profissionalId, [
                'status' => 'ATIVO'
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erro ao salvar os horários no banco de dados.');
            }
        } catch (\Exception $e) {
            log_message('error', '[HorariosAtendimento] Erro: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            log_message('error', '[HorariosAtendimento] Erro fatal: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()->with('error', 'Ocorreu um erro interno ao salvar os horários.');
        }

        return redirect()->to('/')->with('success', 'Cadastro finalizado com sucesso! Agora você já pode usar a plataforma.');
    }
}

