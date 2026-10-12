<?php

namespace App\Controllers\Perfil;

use App\Models\UsuariosComunsModel;
use App\Models\UsuariosModel;

/**
 * "Meu perfil" do usuário comum (contratante): dados pessoais, foto e endereço.
 */
class Contratante extends PerfilBase
{
    private const VOLTAR = 'perfil/contratante';

    public function index()
    {
        helper('profissional');

        $id = $this->usuarioId();

        $totalAvaliacoes = db_connect()->table('avaliacoes')->where('usuario_comum_id', $id)->countAllResults();

        return view('Perfil/contratante', [
            'comum'           => model(UsuariosComunsModel::class)->find($id),
            'usuario'         => model(UsuariosModel::class)->find($id),
            'endereco'        => $this->dadosEndereco($id),
            'totalAvaliacoes' => $totalAvaliacoes,
        ]);
    }

    public function salvarDados()
    {
        $id     = $this->usuarioId();
        $voltar = base_url(self::VOLTAR);
        $post   = $this->request->getPost();

        $dados = [
            'nome'     => trim((string) ($post['nome'] ?? '')),
            'cpf'      => preg_replace('/\D/', '', (string) ($post['cpf'] ?? '')),
            'telefone' => preg_replace('/\D/', '', (string) ($post['telefone'] ?? '')),
        ];

        $rules = [
            'nome'     => 'required|min_length[3]|max_length[150]',
            'cpf'      => 'required|exact_length[11]',
            'telefone' => 'required|min_length[10]|max_length[11]',
        ];

        $mensagens = [
            'nome'     => ['required' => 'Informe seu nome.', 'min_length' => 'O nome deve ter ao menos 3 caracteres.', 'max_length' => 'O nome é muito longo.'],
            'cpf'      => ['required' => 'Informe o CPF.', 'exact_length' => 'O CPF deve ter 11 dígitos.'],
            'telefone' => ['required' => 'Informe o telefone.', 'min_length' => 'Telefone inválido.', 'max_length' => 'Telefone inválido.'],
        ];

        $erros = [];

        if (!$this->validateData($dados, $rules, $mensagens)) {
            $erros = $this->validator->getErrors();
        }

        $erros = array_merge($erros, $this->validarFotos(['foto_perfil' => 'A foto']));

        if (!empty($erros)) {
            return redirect()->to($voltar)->withInput()->with('error', implode(' ', $erros));
        }

        $usuarios = model(UsuariosModel::class);
        $usuario  = $usuarios->find($id);

        try {
            $novaFoto = $this->imagens->salvar($this->request->getFile('foto_perfil'), 'perfil', $usuario->foto_perfil ?? null);

            if ($novaFoto !== null) {
                $usuarios->update($id, ['foto_perfil' => $novaFoto]);
            } elseif ($this->request->getPost('remover_foto_perfil')) {
                $this->imagens->apagar($usuario->foto_perfil ?? null);
                $usuarios->update($id, ['foto_perfil' => null]);
            }

            model(UsuariosComunsModel::class)->update($id, $dados);
        } catch (\Throwable $e) {
            log_message('error', '[Perfil] Erro ao salvar dados do contratante: {error}', ['error' => $e->getMessage()]);

            return redirect()->to($voltar)->withInput()->with('error', 'Não foi possível salvar seus dados. Tente novamente.');
        }

        return redirect()->to($voltar)->with('success', 'Dados atualizados.');
    }

    public function salvarEnderecoPerfil()
    {
        return $this->salvarEndereco(base_url(self::VOLTAR) . '#localizacao');
    }
}
