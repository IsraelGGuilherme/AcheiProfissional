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
     * Confere um arquivo enviado (usado quando vários arquivos chegam no mesmo campo).
     * Retorna a mensagem de erro, ou null se estiver tudo certo.
     */
    public function erroDoArquivo(UploadedFile $arquivo, string $rotulo): ?string
    {
        if (!$arquivo->isValid()) {
            return $rotulo . ' não foi enviada corretamente. Confira se ela tem no máximo 2MB.';
        }

        if ($arquivo->getSizeByUnit('kb') > self::MAX_KB) {
            return $rotulo . ' deve ter no máximo 2MB.';
        }

        if (!in_array($arquivo->getMimeType(), explode(',', self::MIMES), true)) {
            return $rotulo . ' deve ser JPG, PNG ou WEBP.';
        }

        if (@getimagesize($arquivo->getRealPath()) === false) {
            return $rotulo . ' precisa ser uma imagem.';
        }

        return null;
    }

    /**
     * Move o arquivo enviado e devolve o caminho relativo, ou null se não havia arquivo válido.
     * Se $caminhoAntigo for informado e a nova imagem foi salva, a antiga é apagada.
     * Se $ladoMaximo for informado, imagens maiores que isso (em pixels) são reduzidas e
     * fotos de celular giradas pelo EXIF são endireitadas. Isso nunca impede o salvamento.
     */
    public function salvar(?UploadedFile $arquivo, string $subpasta, ?string $caminhoAntigo = null, ?int $ladoMaximo = null): ?string
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

        if ($ladoMaximo !== null) {
            $this->ajustar($destino . '/' . $nome, $ladoMaximo);
        }

        if ($caminhoAntigo) {
            $this->apagar($caminhoAntigo);
        }

        return 'uploads/' . $subpasta . '/' . $nome;
    }

    /**
     * Reduz a imagem ao lado máximo e corrige a orientação (EXIF). Melhor esforço: se a extensão GD
     * não existir ou algo falhar, o arquivo original é mantido.
     */
    protected function ajustar(string $arquivo, int $ladoMaximo): void
    {
        try {
            if (!extension_loaded('gd')) {
                return;
            }

            $info = @getimagesize($arquivo);

            if (!$info) {
                return;
            }

            [$largura, $altura] = $info;

            $precisaOrientar = false;

            if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif            = @exif_read_data($arquivo);
                $precisaOrientar = isset($exif['Orientation']) && (int) $exif['Orientation'] !== 1;
            }

            $precisaReduzir = max($largura, $altura) > $ladoMaximo;

            if (!$precisaOrientar && !$precisaReduzir) {
                return;
            }

            $imagem = \Config\Services::image('gd')->withFile($arquivo);

            if ($precisaOrientar) {
                $imagem->reorient();
            }

            if ($precisaReduzir) {
                $escala = $ladoMaximo / max($largura, $altura);
                $imagem->resize(max(1, (int) round($largura * $escala)), max(1, (int) round($altura * $escala)), false);
            }

            $imagem->save($arquivo, 85);
        } catch (\Throwable $e) {
            log_message('warning', '[ImagemUpload] Não foi possível ajustar a imagem: {erro}', ['erro' => $e->getMessage()]);
        }
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
