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

        $usuariosModel = model('UsuariosModel');
        $categoriaModel = model('CategoriasModel');

        // Mantém a listagem existente e calcula indicadores com dados reais.
        $usuarios = $usuariosModel->listarUsuarios();
        $totalUsuarios = $usuariosModel->countAllResults();
        $qtdProfissionais = $usuariosModel->where('tipo', 'PROFISSIONAL')->countAllResults();
        $qtdProfissionaisAtivos = $usuariosModel
            ->where('tipo', 'PROFISSIONAL')
            ->where('status', 'ATIVO')
            ->countAllResults();
        $qtdContratantes = $usuariosModel->where('tipo', 'COMUM')->countAllResults();
        $qtdBloqueados = $usuariosModel->where('status', 'BLOQUEADO')->countAllResults();
        $qtdPendentes = $usuariosModel->where('status', 'PENDENTE')->countAllResults();

        // O schema atual não possui tabela de vagas; o gráfico mostra novos cadastros.
        $resultadoCadastros = $usuariosModel->builder()
            ->select('DATE(created_at) AS dia, COUNT(*) AS total')
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime('-6 days')))
            ->groupBy('DATE(created_at)')
            ->orderBy('dia', 'ASC')
            ->get()
            ->getResultArray();

        $cadastrosPorDia = [];
        $totaisPorDia = [];
        foreach ($resultadoCadastros as $linha) {
            $totaisPorDia[$linha['dia']] = (int) $linha['total'];
        }

        for ($i = 6; $i >= 0; $i--) {
            $dia = date('Y-m-d', strtotime("-$i days"));
            $cadastrosPorDia[] = [
                'dia' => $dia,
                'rotulo' => date('d/m', strtotime($dia)),
                'total' => $totaisPorDia[$dia] ?? 0,
            ];
        }

        return view('Admin/main', [
            'users' => $usuarios,
            'total_usuarios' => $totalUsuarios,
            'qtd_profissionais' => $qtdProfissionais,
            'qtd_profissionais_ativos' => $qtdProfissionaisAtivos,
            'qtd_contratantes' => $qtdContratantes,
            'qtd_bloqueados' => $qtdBloqueados,
            'qtd_pendentes' => $qtdPendentes,
            'cadastros_por_dia' => $cadastrosPorDia,
            'categorias' => $categoriaModel->findAll(20),
            'qtd_denuncias_pendentes' => model('DenunciasModel')->where('status', 'PENDENTE')->countAllResults(),
        ]);
    }

    /**
     * Tela para onde voltar depois de bloquear/desbloquear (campo `retorno` do formulário).
     * Só aceita caminhos internos da área admin; qualquer outra coisa cai em /admin.
     */
    private function destinoRetorno(): string
    {
        $retorno = trim((string) $this->request->getPost('retorno'), '/');

        return preg_match('#^admin(/[A-Za-z0-9_-]+)*$#', $retorno) === 1
            ? site_url($retorno)
            : site_url('admin');
    }

    public function bloqueio_user()
    {
        $validator = service('validation');
        $data = $this->request->getPost();
        $destino = $this->destinoRetorno();

        $validator->setRules([
            'user_id' => 'required|integer',
            'motivo' => 'required|min_length[10]|max_length[255]'
        ]);

        if (!$validator->run($data)) {
            return redirect()->to($destino)
                ->withInput()
                ->with('error', $validator->getErrors());
        }

        $userService = service('usuarioService');
        $bloqueado = $userService->bloquear_usuario(
            (int) $data['user_id'],
            $data['motivo']
        );
        
        if (!$bloqueado) {
            return redirect()->to($destino)
                ->with('error', 'Não foi possível bloquear este usuário. Verifique se ele existe e se não é administrador.');
        }

        // O bloqueio vale mesmo que o e-mail falhe; só avisamos o admin.
        if (!$userService->emailBloqueioEnviado()) {
            return redirect()->to($destino)
                ->with('success', 'Usuário bloqueado, mas não foi possível enviar o e-mail de aviso para ele.');
        }

        return redirect()->to($destino)
            ->with('success', 'Usuário bloqueado com sucesso. Um e-mail de aviso foi enviado para ele.');
    }


    public function desbloquear_usuario(int $id)
    {
        $userService = service('usuarioService');
        $destino = $this->destinoRetorno();

        if (!$userService->desbloquear_usuario($id)) {
            return redirect()->to($destino)
                ->with('error', 'Não foi possível desbloquear este usuário. Verifique se ele está bloqueado.');
        }

        return redirect()->to($destino)
            ->with('success', 'Usuário desbloqueado com sucesso.');
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
