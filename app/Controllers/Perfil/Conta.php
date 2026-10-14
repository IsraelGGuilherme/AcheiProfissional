<?php

namespace App\Controllers\Perfil;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Services\ImagemUploadService;

/**
 * Exclusão da própria conta (profissional ou usuário comum).
 *
 * A exclusão é definitiva e exige a senha atual. O banco cuida do resto em cascata:
 * perfil, categorias, horários, áreas de atuação, favoritos e avaliações recebidas somem junto.
 * Avaliações feitas por um contratante continuam no perfil do profissional, sem identificação do autor,
 * e as denúncias ficam registradas (sem vínculo com a conta apagada) para o administrador.
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

        $db = db_connect();

        // Arquivos que precisam ser apagados do disco depois que o banco confirmar a exclusão.
        $arquivos = [$usuario->foto_perfil];

        if ($usuario->tipo === 'PROFISSIONAL') {
            $capa = $db->table('profissionais')->select('foto_capa')->where('id', $id)->get()->getRow();
            $arquivos[] = $capa->foto_capa ?? null;

            foreach ($db->table('profissional_imagens_galeria')->select('arquivo')->where('profissional_id', $id)->get()->getResult() as $linha) {
                $arquivos[] = $linha->arquivo;
            }
        }

        try {
            $db->transStart();

            // enderecos.usuario_id é RESTRICT: precisa sair antes do usuário.
            $db->table('enderecos')->where('usuario_id', $id)->delete();
            $db->table('usuarios')->where('id', $id)->delete();

            $db->transComplete();

            if (!$db->transStatus()) {
                throw new \RuntimeException('Transação de exclusão falhou.');
            }
        } catch (\Throwable $e) {
            log_message('error', '[Conta] Erro ao excluir a conta {id}: {error}', ['id' => $id, 'error' => $e->getMessage()]);

            return redirect()->to($voltar)->with('error', 'Não foi possível excluir sua conta agora. Tente novamente.');
        }

        $imagens = new ImagemUploadService();

        foreach (array_filter($arquivos) as $caminho) {
            $imagens->apagar($caminho);
        }

        // Encerra o login mantendo a sessão ativa para exibir a mensagem na próxima tela.
        $sessao->regenerate(true);

        foreach (array_keys($sessao->get()) as $chave) {
            $sessao->remove($chave);
        }

        return redirect()->to('/')->with('success', 'Sua conta foi excluída.');
    }
}
