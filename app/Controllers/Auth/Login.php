<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Services\CodigoVerificacaoService;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    protected UsuariosModel $usuariosModel;
    protected CodigoVerificacaoService $codigoVerificacaoService;

    public function __construct() {
        $this->usuariosModel = model(UsuariosModel::class);
        $this->codigoVerificacaoService = service('codigoVerificacaoService');
    }

    public function index()
    {
        return view('Auth/Login/index');
    }

    public function login()
    {
        $params = $this->request->getPost();

        $rules = [
            'email' => 'required|valid_email|max_length[255]',
            'senha' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        /** @var \App\Services\AuthService $authService */
        $authService = service('authService');

        $autenticado = $authService->login($params['email'], $params['senha']);

        if (!$autenticado) {
            return redirect()->back()->withInput()->with('error', 'Email ou senha incorretos');
        }

        $usuario = session('usuario');

        switch ($usuario->status) {
            case 'PENDENTE_EMAIL':
                return redirect()->to('verificaremail');
            case 'PENDENTE_PERFIL':
                if ($usuario->tipo === 'COMUM') {
                    return redirect()->to('usuariocomum/criarconta');
                } elseif ($usuario->tipo === 'PROFISSIONAL') {
                    return redirect()->to('usuarioprofissional/criarconta');
                }
                return redirect()->to('completarperfil');
            case 'BLOQUEADO':
                session()->destroy();
                return redirect()->to('login')->with('error', 'Sua conta foi bloqueada. Entre em contato com o suporte.');
            default:
                return redirect()->to('/');
        }
    }

    public function formEsqueciMinhaSenha()
    {
        return view('Auth/Login/formEsqueciMinhaSenha');
    }

    public function esqueciMinhaSenha()
    {
        $params = $this->request->getPost();

        $rules = [
            'email' => 'required|valid_email|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $usuario = $this->usuariosModel->where('email', $params['email'])->first();

        session()->set('recuperarConta', [
            'usuario_id' => $usuario ? $usuario->id : null,
            'email' => $params['email'],
        ]);

        return redirect()->to('recuperarconta');
    }

    public function formRecuperarConta()
    {
        $email = session('recuperarConta.email');

        if (!$email) {
            return redirect()->to('esqueciminhasenha')->with('error', 'E-mail de recuperação não encontrado. Por favor, tente novamente.');
        }

        return view('Auth/Login/formRecuperarConta', ['email' => $email]);
    }

    public function gerarCodigoRecuperacao()
    {
        $recuperarConta = session('recuperarConta');

        if ($recuperarConta && isset($recuperarConta['usuario_id'])) {
            $this->codigoVerificacaoService->enviarCodigoEmail(
                $recuperarConta['usuario_id'],
                'RECUPERAR_SENHA'
            );
        }

        return redirect()->to('recuperarconta')
            ->with('success', 'Código de recuperação enviado para o seu e-mail.');
    }

    public function validarCodigoRecuperacao()
    {
        try {
            $codigo = $this->request->getJSON()->codigo;

            $usuario = session('recuperarConta');

            if ($usuario) {
                $codigoValido = $this->codigoVerificacaoService->validarCodigo(
                    $usuario['usuario_id'],
                    'RECUPERAR_SENHA',
                    $codigo
                );

                if ($codigoValido['success']) {
                    session()->set('recuperarConta', array_merge($usuario, ['success' => true]));

                    return $this->response->setJSON([
                        'success' => true,
                        'message' => $codigoValido['message'],
                    ]);
                } else {
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false,
                        'message' => 'Código de verificação incorreto.',
                    ]);
                }
            } else {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Código de verificação incorreto.',
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', '[Register] Erro ao validar código de verificação: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Ocorreu um erro inesperado',
            ]);
        }
    }

    public function formRedefinirSenha()
    {
        $recuperarConta = session('recuperarConta');

        if (!$recuperarConta || !isset($recuperarConta['success']) || !$recuperarConta['success']) {
            return redirect()->to('esqueciminhasenha')->with('error', 'Você precisa validar o código de recuperação antes de redefinir a senha.');
        }

        return view('Auth/Login/formRedefinirSenha');
    }

    public function redefinirSenha()
    {
        $recuperarConta = session('recuperarConta');

        if (!$recuperarConta || !isset($recuperarConta['success']) || !$recuperarConta['success']) {
            return redirect()->to('esqueciminhasenha')->with('error', 'Você precisa validar o código de recuperação antes de redefinir a senha.');
        }

        $params = $this->request->getPost();

        $rules = [
            'senha'          => 'required|min_length[6]',
            'confirmarSenha' => 'required|matches[senha]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $usuarioId = $recuperarConta['usuario_id'];

        $this->usuariosModel->update($usuarioId, [
            'senha' => $params['senha'],
        ]);

        session()->regenerate();

        return redirect()->to('login')->with('success', 'Senha redefinida com sucesso. Faça login com sua nova senha.');
    }

    public function logout()
    {
        session()->destroy();
        
        return redirect()->to('/');
    }
}
