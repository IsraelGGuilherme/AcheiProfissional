<?php

namespace App\Controllers;

use App\Services\CnpjService;

/**
 * POST /api/cnpj/validar  (campos: cnpj e, opcionalmente, dataset = receita | cno | rntrc)
 *
 * Resposta JSON: sucesso, mensagem, codigo e, quando a empresa é encontrada, nome, fantasia, situacao,
 * endereco, dataset... Sempre devolve "csrf" com o novo token, porque o CSRF desta aplicação é
 * regenerado a cada requisição e a página precisa atualizar os formulários.
 */
class Cnpj extends BaseController
{
    /** Consultas por minuto por usuário autenticado ou por IP durante o cadastro. */
    private const LIMITE_POR_MINUTO = 10;

    public function validar()
    {
        $usuario = session('usuario');
        $identificador = is_object($usuario) && isset($usuario->id)
            ? 'usuario_' . $usuario->id
            : 'ip_' . hash('sha256', $this->request->getIPAddress());

        if (!service('throttler')->check('cnpj_' . $identificador, self::LIMITE_POR_MINUTO, MINUTE)) {
            return $this->responder([
                'sucesso'  => false,
                'codigo'   => 'limite',
                'mensagem' => 'Muitas consultas seguidas. Aguarde um minuto e tente de novo.',
            ], 429);
        }

        $dataset   = (string) $this->request->getPost('dataset');
        $resultado = (new CnpjService())->consultar((string) $this->request->getPost('cnpj'), $dataset !== '' ? $dataset : 'receita');

        $status = match ($resultado['codigo']) {
            'invalido'      => 422,
            'indisponivel'  => 503,
            default         => 200,
        };

        return $this->responder($resultado, $status);
    }

    private function responder(array $dados, int $status)
    {
        $dados['csrf'] = csrf_hash();

        return $this->response->setStatusCode($status)->setJSON($dados);
    }
}
