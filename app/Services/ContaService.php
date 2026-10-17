<?php

namespace App\Services;

use App\Models\UsuariosModel;

/**
 * Operações sobre a conta de um usuário, usadas pela tela "Excluir conta" e
 * disponíveis para o painel do administrador (denúncias: bloquear ou excluir).
 *
 * Não existe etapa de ativação: o perfil fica público quando o cadastro termina.
 * "Desativar" uma conta é bloqueá-la (status BLOQUEADO): ela sai da busca, a página
 * pública deixa de abrir e o login é encerrado na próxima requisição.
 */
class ContaService
{
    /**
     * Exclusão definitiva da conta (profissional ou comum).
     *
     * O banco apaga em cascata perfil, categorias, horários, áreas, galeria, favoritos e avaliações recebidas.
     * Avaliações feitas pelo usuário ficam sem autor e as denúncias ficam sem vínculo com a conta.
     * enderecos.usuario_id é RESTRICT, por isso os endereços são apagados antes.
     * As imagens também saem do disco depois que o banco confirma.
     *
     * @throws \Throwable se a transação falhar
     */
    public function excluir(int $usuarioId): bool
    {
        $db      = db_connect();
        $usuario = model(UsuariosModel::class)->find($usuarioId);

        if (!$usuario) {
            return false;
        }

        $arquivos = [$usuario->foto_perfil];

        if ($usuario->tipo === 'PROFISSIONAL') {
            $capa       = $db->table('profissionais')->select('foto_capa')->where('id', $usuarioId)->get()->getRow();
            $arquivos[] = $capa->foto_capa ?? null;

            foreach ($db->table('profissional_imagens_galeria')->select('arquivo')->where('profissional_id', $usuarioId)->get()->getResult() as $linha) {
                $arquivos[] = $linha->arquivo;
            }
        }

        // Fotos de avaliações: as do contratante saem junto com a conta dele (a avaliação em si fica, sem nome);
        // as de avaliações recebidas por um profissional saem em cascata no banco, então só falta apagar os arquivos.
        $idsAvaliacoesDoUsuario = [];

        if ($usuario->tipo === 'COMUM') {
            $idsAvaliacoesDoUsuario = array_map(
                static fn ($l) => (int) $l->id,
                $db->table('avaliacoes')->select('id')->where('usuario_comum_id', $usuarioId)->get()->getResult()
            );

        }

        $consultaFotos = $db->table('avaliacoes_imagens ai')->select('ai.arquivo')->join('avaliacoes a', 'a.id = ai.avaliacao_id');

        if ($usuario->tipo === 'PROFISSIONAL') {
            $consultaFotos->where('a.profissional_id', $usuarioId);
        } elseif (!empty($idsAvaliacoesDoUsuario)) {
            $consultaFotos->whereIn('a.id', $idsAvaliacoesDoUsuario);
        } else {
            $consultaFotos = null;
        }

        if ($consultaFotos !== null) {
            foreach ($consultaFotos->get()->getResult() as $linha) {
                $arquivos[] = $linha->arquivo;
            }
        }

        $db->transStart();

        if (!empty($idsAvaliacoesDoUsuario)) {
            $db->table('avaliacoes_imagens')->whereIn('avaliacao_id', $idsAvaliacoesDoUsuario)->delete();
        }

        $db->table('enderecos')->where('usuario_id', $usuarioId)->delete();
        $db->table('usuarios')->where('id', $usuarioId)->delete();
        $db->transComplete();

        if (!$db->transStatus()) {
            throw new \RuntimeException('Transação de exclusão da conta falhou.');
        }

        $imagens = new ImagemUploadService();

        foreach (array_filter($arquivos) as $caminho) {
            $imagens->apagar($caminho);
        }

        return true;
    }

    /** Desativa a conta: some da busca e da página pública e não consegue mais navegar logada. */
    public function bloquear(int $usuarioId): bool
    {
        return (bool) model(UsuariosModel::class)->update($usuarioId, ['status' => 'BLOQUEADO']);
    }

    /** Reativa uma conta bloqueada. */
    public function desbloquear(int $usuarioId): bool
    {
        return (bool) model(UsuariosModel::class)->update($usuarioId, ['status' => 'ATIVO']);
    }
}
