<?php

namespace App\Controllers\Perfil;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Services\ContaService;

/**
 * Exclusão da própria conta (profissional ou usuário comum).
 *
 * A exclusão é definitiva e exige a senha atual. A regra de exclusão fica em ContaService
 * (o mesmo serviço pode ser usado pelo administrador).
 */
class Conta extends BaseController
{
    public function excluir()
    {
        $voltar = base_url('perfil');
        $sessao = session();
        $id     = (int) $sessao->get('usuario')->id;

        if (!$this->request->getPost('confirmar')) {
            return redirect()->to($voltar)->with('error', 'Marque a confirmação para excluir sua conta.');
        }

        $senha   = (string) $this->request->getPost('senha');
        $usuario = model(UsuariosModel::class)->find($id);

        if (!$usuario || $senha === '' || !password_verify($senha, $usuario->senha)) {
            return redirect()->to($voltar)->with('error', 'Senha incorreta. Sua conta não foi excluída.');
        }

        try {
            (new ContaService())->excluir($id);
        } catch (\Throwable $e) {
            log_message('error', '[Conta] Erro ao excluir a conta {id}: {error}', ['id' => $id, 'error' => $e->getMessage()]);

            return redirect()->to($voltar)->with('error', 'Não foi possível excluir sua conta agora. Tente novamente.');
        }

        // Encerra o login mantendo a sessão ativa para exibir a mensagem na próxima tela.
        $sessao->regenerate(true);

        foreach (array_keys($sessao->get()) as $chave) {
            $sessao->remove($chave);
        }

        return redirect()->to('/')->with('success', 'Sua conta foi excluída.');
    }
}
