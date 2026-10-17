<?php

namespace App\Controllers\Perfil;

use App\Services\GaleriaService;

/**
 * Galeria de trabalhos do profissional: adicionar foto com legenda, editar legenda e remover.
 */
class Galeria extends PerfilBase
{
    private const VOLTAR = 'perfil/profissional#galeria';

    protected GaleriaService $galeria;

    public function __construct()
    {
        parent::__construct();

        $this->galeria = new GaleriaService();
    }

    public function adicionar()
    {
        $voltar = base_url(self::VOLTAR);
        $id     = $this->usuarioId();

        if ($this->galeria->total($id) >= GaleriaService::LIMITE) {
            return redirect()->to($voltar)->with('error', 'Você atingiu o limite de ' . GaleriaService::LIMITE . ' fotos. Remova uma para adicionar outra.');
        }

        $arquivo = $this->request->getFile('foto');

        if ($arquivo === null || $arquivo->getError() === UPLOAD_ERR_NO_FILE) {
            return redirect()->to($voltar)->with('error', 'Escolha uma foto para adicionar.');
        }

        $erros = $this->validarFotos(['foto' => 'A foto']);

        if (!empty($erros)) {
            return redirect()->to($voltar)->with('error', implode(' ', $erros));
        }

        $legenda = trim((string) $this->request->getPost('descricao'));

        if (mb_strlen($legenda) > GaleriaService::LEGENDA_MAX) {
            return redirect()->to($voltar)->with('error', 'A legenda pode ter no máximo ' . GaleriaService::LEGENDA_MAX . ' caracteres.');
        }

        try {
            $fotoId = $this->galeria->adicionar($id, $arquivo, $legenda);
        } catch (\Throwable $e) {
            log_message('error', '[Galeria] Erro ao adicionar foto: {error}', ['error' => $e->getMessage()]);

            return redirect()->to($voltar)->with('error', 'Não foi possível salvar a foto. Tente novamente.');
        }

        if ($fotoId === null) {
            return redirect()->to($voltar)->with('error', 'Não foi possível salvar a foto. Tente novamente.');
        }

        return redirect()->to($voltar)->with('success', 'Foto adicionada à sua galeria.');
    }

    public function legenda(int $fotoId)
    {
        $voltar = base_url(self::VOLTAR);

        if (!$this->galeria->pertenceA($fotoId, $this->usuarioId())) {
            return redirect()->to($voltar)->with('error', 'Foto não encontrada.');
        }

        $legenda = trim((string) $this->request->getPost('descricao'));

        if (mb_strlen($legenda) > GaleriaService::LEGENDA_MAX) {
            return redirect()->to($voltar)->with('error', 'A legenda pode ter no máximo ' . GaleriaService::LEGENDA_MAX . ' caracteres.');
        }

        $this->galeria->atualizarLegenda($fotoId, $legenda);

        return redirect()->to($voltar)->with('success', 'Legenda atualizada.');
    }

    public function remover(int $fotoId)
    {
        $voltar = base_url(self::VOLTAR);

        if (!$this->galeria->pertenceA($fotoId, $this->usuarioId())) {
            return redirect()->to($voltar)->with('error', 'Foto não encontrada.');
        }

        $this->galeria->remover($fotoId);

        return redirect()->to($voltar)->with('success', 'Foto removida.');
    }
}
