<?php

namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Salva imagens enviadas (foto de perfil, foto de capa) em public/uploads/<subpasta>/.
 * O caminho relativo (ex.: "uploads/perfil/abc123.jpg") é o que vai para o banco.
 */
class ImagemUploadService
{
    public const MAX_KB = 2048; // 2 MB, igual ao texto exibido nos formulários

    public const MIMES = 'image/jpg,image/jpeg,image/png,image/webp';

    /**
     * Regra de validação pronta para o campo de arquivo (opcional).
     */
    public static function regra(string $campo): string
    {
        return 'is_image[' . $campo . ']|mime_in[' . $campo . ',' . self::MIMES . ']|max_size[' . $campo . ',' . self::MAX_KB . ']';
    }

    /**
     * Move o arquivo enviado e devolve o caminho relativo, ou null se não havia arquivo válido.
     * Se $caminhoAntigo for informado e a nova imagem foi salva, a antiga é apagada.
     */
    public function salvar(?UploadedFile $arquivo, string $subpasta, ?string $caminhoAntigo = null): ?string
    {
        if ($arquivo === null || !$arquivo->isValid() || $arquivo->hasMoved()) {
            return null;
        }

        $destino = FCPATH . 'uploads/' . $subpasta;

        if (!is_dir($destino) && !mkdir($destino, 0755, true) && !is_dir($destino)) {
            throw new \RuntimeException('Não foi possível criar a pasta de uploads.');
        }

        $nome = $arquivo->getRandomName();
        $arquivo->move($destino, $nome);

        if ($caminhoAntigo) {
            $this->apagar($caminhoAntigo);
        }

        return 'uploads/' . $subpasta . '/' . $nome;
    }

    /**
     * Apaga uma imagem previamente salva por este serviço (ignora qualquer caminho fora de uploads/).
     */
    public function apagar(?string $caminho): void
    {
        if (!$caminho || !str_starts_with($caminho, 'uploads/') || str_contains($caminho, '..')) {
            return;
        }

        $arquivo = FCPATH . $caminho;

        if (is_file($arquivo)) {
            @unlink($arquivo);
        }
    }
}
