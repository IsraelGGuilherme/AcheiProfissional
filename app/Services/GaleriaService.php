<?php

namespace App\Services;

use App\Models\GaleriaModel;
use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Galeria de trabalhos do profissional (fotos com legenda).
 * A leitura para exibição fica em BuscaProfissionaisService::galeriaDoProfissional().
 *
 * remover() pode ser usado pelo administrador para tirar uma foto denunciada.
 */
class GaleriaService
{
    public const LIMITE = 12;

    /** Maior lado da imagem salva, em pixels (fotos de celular são reduzidas). */
    public const LADO_MAXIMO = 1600;

    public const LEGENDA_MAX = 300;

    public function total(int $profissionalId): int
    {
        return model(GaleriaModel::class)->where('profissional_id', $profissionalId)->countAllResults();
    }

    public function pertenceA(int $fotoId, int $profissionalId): bool
    {
        return model(GaleriaModel::class)->where('id', $fotoId)->where('profissional_id', $profissionalId)->first() !== null;
    }

    /**
     * Salva o arquivo e cria a linha na galeria. Retorna o id da foto, ou null se o arquivo não pôde ser salvo.
     */
    public function adicionar(int $profissionalId, UploadedFile $arquivo, ?string $legenda): ?int
    {
        $caminho = (new ImagemUploadService())->salvar($arquivo, 'galeria', null, self::LADO_MAXIMO);

        if ($caminho === null) {
            return null;
        }

        $legenda = $legenda !== null ? trim($legenda) : null;

        try {
            $id = model(GaleriaModel::class)->insert([
                'profissional_id' => $profissionalId,
                'arquivo'         => $caminho,
                'descricao'       => $legenda !== '' ? $legenda : null,
            ]);
        } catch (\Throwable $e) {
            (new ImagemUploadService())->apagar($caminho); // não deixa arquivo órfão

            throw $e;
        }

        return (int) $id;
    }

    public function atualizarLegenda(int $fotoId, ?string $legenda): bool
    {
        $legenda = $legenda !== null ? trim($legenda) : null;

        return (bool) model(GaleriaModel::class)->update($fotoId, ['descricao' => $legenda !== '' ? $legenda : null]);
    }

    /** Apaga a linha e o arquivo da foto. */
    public function remover(int $fotoId): bool
    {
        $model = model(GaleriaModel::class);
        $foto  = $model->find($fotoId);

        if (!$foto) {
            return false;
        }

        $model->delete($fotoId);
        (new ImagemUploadService())->apagar($foto->arquivo);

        return true;
    }
}
