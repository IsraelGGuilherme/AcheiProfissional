<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\ProfissionaisModel;
use App\Models\UsuariosComunsModel;
use App\Models\UsuariosModel;
use App\Services\AuthService;
use App\Services\CodigoVerificacaoService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;

class Register extends BaseController
{
    protected UsuariosModel $usuariosModel;
    protected UsuariosComunsModel $usuariosComunsModel;
    protected ProfissionaisModel $profissionaisModel;
    protected AuthService $authService;
    protected CodigoVerificacaoService $codigoVerificacaoService;

    public function __construct() {
        $this->usuariosModel = model(UsuariosModel::class);
        $this->usuariosComunsModel = model(UsuariosComunsModel::class);
        $this->profissionaisModel = model(ProfissionaisModel::class);
        $this->authService = service('authService');
        $this->codigoVerificacaoService = service('codigoVerificacaoService');
    }

    public function index()
    {
        return view('Auth/Register/index');
    }

    public function registerUsuarioComum()
    {
        return view('Auth/Register/registerUsuarioComum');
    }

    public function registerProfissional()
    {
        return view('Auth/Register/registerProfissional');
    }

    public function register()
    {
        $params = $this->request->getPost();

        $rules = [
            'email' => 'required|valid_email|max_length[255]',
            'senha' => 'required',
            'tipo'  => 'required|in_list[COMUM,PROFISSIONAL]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        try {
            $this->usuariosModel->save($params);
        } catch (DatabaseException $e) {
            $redirect = redirect()->back()->withInput();

            if ($e->getCode() === 1062) {
                return $redirect->with(
                    'error',
                    'Já existe uma conta cadastrada para esse email'
                );
            }

            log_message('error', '[Register] Erro ao cadastrar usuário: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return $redirect->with(
                'error',
                'Ocorreu um erro inesperado'
            );
        }

        $this->authService->montarSessaoUsuario($params['email']);

        return redirect()->to('verificaremail');
    }

    public function formValidarEmail()
    {
        $estaLogado = $this->authService->estaLogado(false);

        if (!$estaLogado) {
            return redirect()->to('register');
        }

        return view('Auth/Register/formValidarEmail', [
            'email' => session('usuario')->email,
            'tipo'  => session('usuario')->tipo,
        ]);
    }

    public function gerarCodigoValidacaoEmail()
    {
        try {
            if ($this->usuarioEncontrado()['success'] === false) {
                return $this->usuarioEncontrado()['redirect'];
            }

            $this->codigoVerificacaoService->enviarCodigoEmail(session('usuario')->id, 'VERIFICAR_EMAIL');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Código de verificação enviado para o e-mail informado.',
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Register] Erro ao gerar código de verificação: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Ocorreu um erro inesperado',
            ]);
        }
    }

    public function validarCodigoValidacaoEmail()
    {
        try {
            if ($this->usuarioEncontrado()['success'] === false) {
                return $this->usuarioEncontrado()['redirect'];
            }

            $codigo = $this->request->getJSON()->codigo;

            $usuario = session('usuario');

            $codigoValido = $this->codigoVerificacaoService->validarCodigo(
                $usuario->id,
                'VERIFICAR_EMAIL',
                $codigo
            );

            if ($codigoValido['success']) {
                $this->usuariosModel->update($usuario->id, ['status' => 'PENDENTE_PERFIL']);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => $codigoValido['message'],
                ]);
            } else {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => $codigoValido['message'],
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

    public function formEsqueciMinhaSenha()
    {
        return view('Auth/Register/formEsqueciMinhaSenha');
    }

    public function registerUsuarioComumForm()
    {
        return view('Auth/Register/registerUsuarioComumForm');
    }

    public function registerUsuarioComumDados()
    {
        $params = $this->request->getPost();

        $rules = [
            'nome'     => 'required|max_length[255]',
            'cpf'      => 'required|exact_length[11]',
            'telefone' => 'required|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $insert = $params;
        $insert['id'] = session('usuario')->id;

        $this->usuariosComunsModel->insert($insert);

        return redirect()->to('cadastrarendereco');
    }

    private function usuarioEncontrado(): array
    {
        if ($this->authService->estaLogado(false)) {
            return [
                'success' => true,
            ];
        }
        return [
            'success' => false,
            'redirect' => $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Usuário não encontrado',
            ]),
        ];
    }
}
