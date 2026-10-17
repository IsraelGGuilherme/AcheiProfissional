<?php

namespace App\Services;

use App\Models\AvaliacaoImagensModel;
use App\Models\AvaliacoesModel;
use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Fotos das avaliações (estilo "foto do cliente") e operações de moderação.
 *
 * excluirFoto() e excluirAvaliacao() podem ser usados pelo administrador
 * para remover conteúdo denunciado.
 */
class AvaliacaoService
{
    public const MAX_FOTOS = 4;

    /** Maior lado da imagem salva, em pixels. */
    public const LADO_MAXIMO = 1280;

    /** @return list<object> id, arquivo */
    public function fotosDaAvaliacao(int $avaliacaoId): array
    {
        return model(AvaliacaoImagensModel::class)
            ->select('id, arquivo')
            ->where('avaliacao_id', $avaliacaoId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Fotos de várias avaliações de uma vez.
     *
     * @param list<int> $avaliacaoIds
     * @return array<int, list<object>> avaliacao_id => fotos (só as que têm foto)
     */
    public function fotosPorAvaliacoes(array $avaliacaoIds): array
    {
        if (empty($avaliacaoIds)) {
            return [];
        }

        $linhas = model(AvaliacaoImagensModel::class)
            ->select('id, avaliacao_id, arquivo')
            ->whereIn('avaliacao_id', $avaliacaoIds)
            ->orderBy('id', 'ASC')
            ->findAll();

        $mapa = [];

        foreach ($linhas as $linha) {
            $mapa[(int) $linha->avaliacao_id][] = $linha;
        }

        return $mapa;
    }

    /**
     * Salva as imagens (já validadas) e vincula à avaliação. Retorna quantas foram salvas.
     *
     * @param list<UploadedFile> $arquivos
     */
    public function adicionarFotos(int $avaliacaoId, array $arquivos): int
    {
        $imagens = new ImagemUploadService();
        $model   = model(AvaliacaoImagensModel::class);
        $salvas  = 0;

        foreach ($arquivos as $arquivo) {
            $caminho = $imagens->salvar($arquivo, 'avaliacoes', null, self::LADO_MAXIMO);

            if ($caminho === null) {
                continue;
            }

            try {
                $model->insert(['avaliacao_id' => $avaliacaoId, 'arquivo' => $caminho]);
                $salvas++;
            } catch (\Throwable $e) {
                $imagens->apagar($caminho); // não deixa arquivo órfão

                throw $e;
            }
        }

        return $salvas;
    }

    /**
     * Remove fotos de UMA avaliação (ignora ids que não pertencem a ela). Retorna quantas foram removidas.
     *
     * @param list<int> $fotoIds
     */
    public function removerFotos(int $avaliacaoId, array $fotoIds): int
    {
        if (empty($fotoIds)) {
            return 0;
        }

        $model = model(AvaliacaoImagensModel::class);
        $fotos = $model->where('avaliacao_id', $avaliacaoId)->whereIn('id', $fotoIds)->findAll();

        $imagens = new ImagemUploadService();

        foreach ($fotos as $foto) {
            $model->delete($foto->id);
            $imagens->apagar($foto->arquivo);
        }

        return count($fotos);
    }

    /** Moderação: apaga uma foto (linha e arquivo), de qualquer avaliação. */
    public function excluirFoto(int $fotoId): bool
    {
        $model = model(AvaliacaoImagensModel::class);
        $foto  = $model->find($fotoId);

        if (!$foto) {
            return false;
        }

        $model->delete($fotoId);
        (new ImagemUploadService())->apagar($foto->arquivo);

        return true;
    }

    /** Moderação: apaga a avaliação inteira, com as fotos dela. */
    public function excluirAvaliacao(int $avaliacaoId): bool
    {
        $model = model(AvaliacoesModel::class);

        if (!$model->find($avaliacaoId)) {
            return false;
        }

        $fotos = $this->fotosDaAvaliacao($avaliacaoId);

        $model->delete($avaliacaoId); // as linhas de avaliacoes_imagens saem em cascata

        $imagens = new ImagemUploadService();

        foreach ($fotos as $foto) {
            $imagens->apagar($foto->arquivo);
        }

        return true;
    }
}
