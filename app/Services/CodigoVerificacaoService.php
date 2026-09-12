<?php

namespace App\Services;

use App\Models\UsuarioCodigosEmailModel;
use App\Models\UsuariosModel;

class CodigoVerificacaoService
{
    protected UsuarioCodigosEmailModel $usuarioCodigosEmailModel;

    public function __construct() {
        $this->usuarioCodigosEmailModel = new UsuarioCodigosEmailModel();
    }

    /**
     * Gera um código de verificação numérico com o tamanho especificado.
     *
     * @param int $tamanho Quantidade de dígitos do código (padrão: 6).
     * @return string Código numérico formatado com zeros à esquerda.
     */
    public function gerarCodigo(int $tamanho = 6): string
    {
        $max = (10 ** $tamanho) - 1;
        $numero = random_int(0, $max);

        return sprintf("%0{$tamanho}d", $numero);
    }

    public function enviarCodigoEmail(int $usuario_id, string $tipo, string $email = ''): bool
    {
        $codigo = $this->gerarCodigo();

        $this->usuarioCodigosEmailModel->insert([
            'usuario_id' => $usuario_id,
            'finalidade' => $tipo,
            'codigo'     => $codigo,
        ]);

        if ($email === '') {
            $usuarioModel = model(UsuariosModel::class);
            $usuario = $usuarioModel->find($usuario_id);

            if (!$usuario) {
                log_message('error', '[CodigoVerificacaoService] Usuário não encontrado para ID: {id}', ['id' => $usuario_id]);
                return false;
            }

            $email = $usuario->email;
        }

        $emailService = new EmailService();

        if ($tipo === 'VERIFICAR_EMAIL') {
            return $emailService->enviarCodigoVerificacao($email, $codigo);
        }
        
        return $emailService->enviarCodigoRecuperacao($email, $codigo);
    }

    public function validarCodigo(int $usuario_id, string $tipo, string $codigo): array
    {
        $registro = $this->usuarioCodigosEmailModel
            ->where('usuario_id', $usuario_id)
            ->where('finalidade', $tipo)
            ->where('utilizado', false)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$registro) {
            return [
                'success' => false,
                'message' => 'Nenhum código de verificação encontrado para este usuário e finalidade.',
            ];
        }

        if ($registro->tentativas >= 5) {
            return [
                'success' => false,
                'message' => 'Número máximo de tentativas atingido. Por favor, solicite um novo código.',
            ];
        }

        if (!password_verify($codigo, $registro->codigo)) {
            // Incrementa o número de tentativas
            $this->usuarioCodigosEmailModel->update($registro->id, [
                'tentativas' => $registro->tentativas + 1,
            ]);
            return [
                'success' => false,
                'message' => 'Código de verificação incorreto, mais ' . (5 - ($registro->tentativas + 1)) . ' tentativa(s) restante(s).',
            ];
        }

        // Marca o código como utilizado
        $this->usuarioCodigosEmailModel->update($registro->id, [
            'utilizado' => true,
        ]);

        return [
            'success' => true,
            'message' => 'Código de verificação válido.',
        ];
    }
}
