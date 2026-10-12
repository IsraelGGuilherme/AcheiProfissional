<?php

namespace App\Filters;

use App\Models\UsuariosModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Exige usuário logado (e, opcionalmente, de determinado tipo).
 *
 * Uso nas rotas:
 *   ['filter' => 'auth']                  -> qualquer usuário logado
 *   ['filter' => 'auth:PROFISSIONAL']     -> só profissionais
 *   ['filter' => 'auth:COMUM,PROFISSIONAL']
 *
 * O fluxo de cadastro incompleto continua sendo tratado pelo
 * RegistrationFlowFilter (global), que roda antes deste.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $sessao = session();

        if (!$sessao->has('usuario')) {
            return redirect()->to('/login')->with('error', 'Faça login para continuar.');
        }

        $usuario = $sessao->get('usuario');

        // Confere no banco: a sessão pode estar desatualizada (ex.: conta bloqueada depois do login).
        $usuarioDb = model(UsuariosModel::class)->find($usuario->id);

        if (!$usuarioDb || $usuarioDb->status === 'BLOQUEADO') {
            $sessao->destroy();

            return redirect()->to('/login')->with('error', 'Sua conta não está disponível.');
        }

        if (!empty($arguments) && !in_array($usuarioDb->tipo, $arguments, true)) {
            return redirect()->to('/')->with('error', 'Você não tem permissão para acessar essa página.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
