<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DenunciasModel;

/**
 * Análise de denúncias (painel admin).
 *  - index():   denúncias agrupadas por usuário denunciado;
 *  - usuario(): todas as denúncias de um único usuário.
 * O bloqueio/desbloqueio é feito por Admin\Admin::bloqueio_user() e ::desbloquear_usuario().
 */
class Denuncias extends BaseController
{
    private const LISTA = 'admin/denuncias';

    public function index()
    {
        helper('profissional');

        $busca  = trim((string) $this->request->getGet('q'));
        $grupos = model(DenunciasModel::class)->agruparPorDenunciado($busca);

        return view('Admin/Denuncias/lista', [
            'grupos'         => $grupos,
            'totalDenuncias' => array_sum(array_map(static fn ($g) => (int) $g->total, $grupos)),
            'busca'          => $busca,
        ]);
    }

    /**
     * @param int $id id do usuário denunciado; 0 = denúncias de contas que já foram excluídas
     */
    public function usuario(int $id)
    {
        helper('profissional');

        $model = model(DenunciasModel::class);

        // Pode ser null mesmo com denúncias: a conta pode ter sido excluída depois.
        $denunciado = $id > 0 ? $model->buscarDenunciado($id) : null;
        $denuncias  = $model->listarDoDenunciado($id > 0 ? $id : null);

        if (empty($denuncias)) {
            return redirect()->to(self::LISTA)->with('error', 'Nenhuma denúncia encontrada para este usuário.');
        }

        $contagem = array_fill_keys(array_keys(DenunciasModel::STATUS), 0);
        foreach ($denuncias as $d) {
            $contagem[$d->status] = ($contagem[$d->status] ?? 0) + 1;
        }

        return view('Admin/Denuncias/usuario', [
            'denunciado'    => $denunciado,
            'denuncias'     => $denuncias,
            'contagem'      => $contagem,
            'motivos'       => DenunciasModel::MOTIVOS,
            'statusRotulos' => DenunciasModel::STATUS,
        ]);
    }
}
