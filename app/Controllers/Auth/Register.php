<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\ProfissionaisModel;
use App\Models\UsuariosComunsModel;
use App\Models\UsuariosModel;
use App\Services\AuthService;
use App\Services\CodigoVerificacaoService;
use App\Services\ImagemUploadService;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

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
            'email' => 'required|valid_email|max_length[191]',
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
                $this->usuariosModel->update($usuario->id, [
                    'email_verificado_em' => date('Y-m-d H:i:s')
                ]);

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

    public function registerProfissionalForm()
    {
        return view('Auth/Register/registerProfissionalForm');
    }

    /**
     * Regras de validação das fotos do cadastro. São opcionais: o campo só é validado
     * se um arquivo foi realmente enviado.
     *
     * @param list<string> $campos
     * @return array<string, string>
     */
    protected function regrasFotosOpcionais(array $campos): array
    {
        $regras = [];

        foreach ($campos as $campo) {
            $arquivo = $this->request->getFile($campo);

            if ($arquivo !== null && $arquivo->getError() !== UPLOAD_ERR_NO_FILE) {
                $regras[$campo] = 'uploaded[' . $campo . ']|' . ImagemUploadService::regra($campo);
            }
        }

        return $regras;
    }

    /**
     * Grava as fotos enviadas no cadastro. Uma falha aqui não desfaz o cadastro
     * (a pessoa pode enviar a foto depois, em "Meu perfil").
     */
    protected function salvarFotosDoCadastro(int $usuarioId, bool $comCapa): void
    {
        try {
            $imagens = new ImagemUploadService();

            $fotoPerfil = $imagens->salvar($this->request->getFile('foto_perfil'), 'perfil');

            if ($fotoPerfil !== null) {
                $this->usuariosModel->update($usuarioId, ['foto_perfil' => $fotoPerfil]);
            }

            if ($comCapa) {
                $fotoCapa = $imagens->salvar($this->request->getFile('foto_capa'), 'capas');

                if ($fotoCapa !== null) {
                    $this->profissionaisModel->update($usuarioId, ['foto_capa' => $fotoCapa]);
                }
            }
        } catch (Throwable $e) {
            log_message('error', '[Register] Erro ao salvar fotos do cadastro: {error}', ['error' => $e->getMessage()]);
        }
    }

    public function registerUsuarioComumDados()
    {
        $params = $this->request->getPost();

        $rules = [
            'nome'     => 'required|max_length[255]',
            'cpf'      => 'required|exact_length[11]',
            'telefone' => 'required|max_length[20]',
        ];

        $rules += $this->regrasFotosOpcionais(['foto_perfil']);

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $insert = $params;
        $insert['id'] = session('usuario')->id;

        try {
            $db = db_connect();
            $db->transStart();

            $this->usuariosComunsModel->insert($insert);

            $db->transComplete();
        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error',
                'Ocorreu um erro inesperado'
            );
        }

        $this->salvarFotosDoCadastro((int) session('usuario')->id, false);

        return redirect()->to('cadastrarendereco');
    }

    public function registerProfissionalDados()
    {
        $params = $this->request->getPost();

        $rules = [
            'tipo_pessoa'      => 'required|in_list[PF,PJ]',
            'cpf_cnpj'         => 'required|max_length[18]',
            'nome'             => 'required|max_length[150]',
            'descricao'        => 'permit_empty|string',
            'telefone_contato' => 'permit_empty|max_length[20]',
            'whatsapp'         => 'permit_empty|max_length[20]',
            'instagram'        => 'permit_empty|max_length[100]',
            'site'             => 'permit_empty|max_length[255]',
        ];

        $rules += $this->regrasFotosOpcionais(['foto_perfil', 'foto_capa']);

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        helper('url');

        $slugOriginal = url_title($params['nome'], '-', true);
        $slug = $slugOriginal;
        $count = 1;
        while ($this->profissionaisModel->where('slug', $slug)->first()) {
            $slug = $slugOriginal . '-' . $count;
            $count++;
        }

        $insert = $params;
        $insert['id'] = session('usuario')->id;
        $insert['slug'] = $slug;
        
        // Remove non-numeric characters just to be safe (matching constraint sizes)
        $insert['cpf_cnpj'] = preg_replace('/\D/', '', $insert['cpf_cnpj']);
        if (!empty($insert['telefone_contato'])) {
            $insert['telefone_contato'] = preg_replace('/\D/', '', $insert['telefone_contato']);
        }
        if (!empty($insert['whatsapp'])) {
            $insert['whatsapp'] = preg_replace('/\D/', '', $insert['whatsapp']);
        }

        try {
            $db = db_connect();
            $db->transStart();

            $this->profissionaisModel->insert($insert);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Erro ao salvar os dados do profissional.');
            }
        } catch (Throwable $e) {
            log_message('error', '[Register] Erro ao cadastrar dados do profissional: {error} \nStack Trace: {trace}', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error',
                'Ocorreu um erro inesperado ao salvar os dados.'
            );
        }

        $this->salvarFotosDoCadastro((int) session('usuario')->id, true);

        return redirect()->to('profissional/categorias');
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
