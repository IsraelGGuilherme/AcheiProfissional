<?php

namespace App\Controllers\Perfil;

use App\Models\ProfissionaisModel;
use App\Models\ProfissionalCategoriaModel;
use App\Models\ProfissionalLocaisAtendimentoModel;
use App\Models\UsuariosModel;
use App\Services\BuscaProfissionaisService;
use App\Services\GaleriaService;

/**
 * "Meu perfil" do profissional: dados, contato, fotos, categorias, endereço e áreas de atuação.
 */
class Profissional extends PerfilBase
{
    private const VOLTAR = 'perfil/profissional';

    public function index()
    {
        helper('profissional');

        $id    = $this->usuarioId();
        $busca = new BuscaProfissionaisService();

        $profissional = model(ProfissionaisModel::class)->find($id);
        $usuario      = model(UsuariosModel::class)->find($id);

        $nota = db_connect()->table('avaliacoes')
            ->select('ROUND(AVG(nota), 1) AS media, COUNT(*) AS total', false)
            ->where('profissional_id', $id)
            ->get()
            ->getRow();

        $minhasCategorias = $busca->categoriasDoProfissional($id);

        return view('Perfil/profissional', [
            'profissional'     => $profissional,
            'usuario'          => $usuario,
            'nota'             => $nota,
            'categorias'       => $busca->categorias(),
            'idsSelecionados'  => array_map(static fn ($c) => (int) $c->id, $minhasCategorias),
            'minhasCategorias' => $minhasCategorias,
            'locais'           => $busca->locaisDoProfissional($id),
            'horarios'         => $busca->horariosDoProfissional($id),
            'galeria'          => $busca->galeriaDoProfissional($id),
            'limiteGaleria'    => GaleriaService::LIMITE,
            'endereco'         => $this->dadosEndereco($id),
        ]);
    }

    public function salvarDados()
    {
        $id      = $this->usuarioId();
        $voltar  = base_url(self::VOLTAR);
        $post    = $this->request->getPost();

        $tipoPessoa = (string) ($post['tipo_pessoa'] ?? '');
        $documento  = preg_replace('/\D/', '', (string) ($post['cpf_cnpj'] ?? ''));
        $tamanhoDoc = $tipoPessoa === 'PJ' ? 14 : 11;

        $site = trim((string) ($post['site'] ?? ''));
        if ($site !== '' && !preg_match('#^https?://#i', $site)) {
            $site = 'https://' . $site;
        }

        $dados = [
            'tipo_pessoa'      => $tipoPessoa,
            'cpf_cnpj'         => $documento,
            'nome'             => trim((string) ($post['nome'] ?? '')),
            'descricao'        => trim((string) ($post['descricao'] ?? '')),
            'telefone_contato' => preg_replace('/\D/', '', (string) ($post['telefone_contato'] ?? '')),
            'whatsapp'         => preg_replace('/\D/', '', (string) ($post['whatsapp'] ?? '')),
            'instagram'        => ltrim(trim((string) ($post['instagram'] ?? '')), '@'),
            'site'             => $site,
        ];

        $rules = [
            'tipo_pessoa'      => 'required|in_list[PF,PJ]',
            'cpf_cnpj'         => 'required|exact_length[' . $tamanhoDoc . ']',
            'nome'             => 'required|min_length[3]|max_length[150]',
            'descricao'        => 'permit_empty|max_length[2000]',
            'telefone_contato' => 'permit_empty|min_length[10]|max_length[11]',
            'whatsapp'         => 'permit_empty|min_length[10]|max_length[11]',
            'instagram'        => 'permit_empty|max_length[100]',
            'site'             => 'permit_empty|max_length[255]|valid_url_strict',
        ];

        $mensagens = [
            'tipo_pessoa'      => ['required' => 'Escolha Pessoa Física ou Jurídica.', 'in_list' => 'Escolha Pessoa Física ou Jurídica.'],
            'cpf_cnpj'         => ['required' => 'Informe o CPF ou CNPJ.', 'exact_length' => $tipoPessoa === 'PJ' ? 'O CNPJ deve ter 14 dígitos.' : 'O CPF deve ter 11 dígitos.'],
            'nome'             => ['required' => 'Informe o nome.', 'min_length' => 'O nome deve ter ao menos 3 caracteres.', 'max_length' => 'O nome é muito longo.'],
            'descricao'        => ['max_length' => 'A descrição pode ter no máximo 2000 caracteres.'],
            'telefone_contato' => ['min_length' => 'Telefone inválido.', 'max_length' => 'Telefone inválido.'],
            'whatsapp'         => ['min_length' => 'WhatsApp inválido.', 'max_length' => 'WhatsApp inválido.'],
            'instagram'        => ['max_length' => 'O Instagram é muito longo.'],
            'site'             => ['valid_url_strict' => 'Informe um site válido.', 'max_length' => 'O endereço do site é muito longo.'],
        ];

        $erros = [];

        if (!$this->validateData($dados, $rules, $mensagens)) {
            $erros = $this->validator->getErrors();
        }

        $erros = array_merge($erros, $this->validarFotos([
            'foto_perfil' => 'A foto de perfil',
            'foto_capa'   => 'A foto de capa',
        ]));

        if (!empty($erros)) {
            return redirect()->to($voltar)->withInput()->with('error', implode(' ', $erros));
        }

        $profissionais = model(ProfissionaisModel::class);
        $usuarios      = model(UsuariosModel::class);

        $atual   = $profissionais->find($id);
        $usuario = $usuarios->find($id);

        foreach (['telefone_contato', 'whatsapp', 'instagram', 'site', 'descricao'] as $campo) {
            $dados[$campo] = $dados[$campo] !== '' ? $dados[$campo] : null;
        }

        try {
            // Foto de capa
            $novaCapa = $this->imagens->salvar($this->request->getFile('foto_capa'), 'capas', $atual->foto_capa ?? null);

            if ($novaCapa !== null) {
                $dados['foto_capa'] = $novaCapa;
            } elseif ($this->request->getPost('remover_foto_capa')) {
                $this->imagens->apagar($atual->foto_capa ?? null);
                $dados['foto_capa'] = null;
            }

            // Foto de perfil (fica em usuarios)
            $novaFoto = $this->imagens->salvar($this->request->getFile('foto_perfil'), 'perfil', $usuario->foto_perfil ?? null);

            if ($novaFoto !== null) {
                $usuarios->update($id, ['foto_perfil' => $novaFoto]);
            } elseif ($this->request->getPost('remover_foto_perfil')) {
                $this->imagens->apagar($usuario->foto_perfil ?? null);
                $usuarios->update($id, ['foto_perfil' => null]);
            }

            // O slug não muda ao editar o nome, para não quebrar links já compartilhados.
            $profissionais->update($id, $dados);
        } catch (\Throwable $e) {
            log_message('error', '[Perfil] Erro ao salvar dados do profissional: {error}', ['error' => $e->getMessage()]);

            return redirect()->to($voltar)->withInput()->with('error', 'Não foi possível salvar seus dados. Tente novamente.');
        }

        return redirect()->to($voltar)->with('success', 'Dados atualizados.');
    }

    public function salvarCategorias()
    {
        $id     = $this->usuarioId();
        $voltar = base_url(self::VOLTAR) . '#categorias';

        $ids = array_values(array_unique(array_filter(
            array_map('intval', (array) $this->request->getPost('categorias')),
            static fn ($v) => $v > 0
        )));

        if (empty($ids)) {
            return redirect()->to($voltar)->with('error', 'Selecione ao menos uma categoria.');
        }

        $validas = db_connect()->table('categorias')->select('id')->whereIn('id', $ids)->get()->getResultArray();

        if (count($validas) !== count($ids)) {
            return redirect()->to($voltar)->with('error', 'Categoria inválida.');
        }

        $db    = db_connect();
        $model = model(ProfissionalCategoriaModel::class);

        $db->transStart();
        $model->where('profissional_id', $id)->delete();

        foreach ($ids as $categoriaId) {
            $model->insert(['profissional_id' => $id, 'categoria_id' => $categoriaId]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->to($voltar)->with('error', 'Não foi possível salvar as categorias.');
        }

        return redirect()->to($voltar)->with('success', 'Categorias atualizadas.');
    }

    public function salvarEnderecoPerfil()
    {
        return $this->salvarEndereco(base_url(self::VOLTAR) . '#localizacao');
    }

    public function adicionarLocal()
    {
        $id     = $this->usuarioId();
        $voltar = base_url(self::VOLTAR) . '#atuacao';

        $localidadeId = (int) $this->request->getPost('localidade_id');

        if ($localidadeId <= 0) {
            return redirect()->to($voltar)->with('error', 'Selecione o estado, a cidade ou o bairro que deseja adicionar.');
        }

        $existe = db_connect()->table('localidades')->select('id')->where('id', $localidadeId)->whereIn('tipo', ['UF', 'MUNICIPIO', 'BAIRRO'])->get()->getRow();

        if (!$existe) {
            return redirect()->to($voltar)->with('error', 'Localidade inválida.');
        }

        $model = model(ProfissionalLocaisAtendimentoModel::class);

        if ($model->where('profissional_id', $id)->where('localidade_id', $localidadeId)->first()) {
            return redirect()->to($voltar)->with('error', 'Essa localidade já foi adicionada.');
        }

        $model->insert(['profissional_id' => $id, 'localidade_id' => $localidadeId]);

        return redirect()->to($voltar)->with('success', 'Área de atuação adicionada.');
    }

    public function removerLocal(int $linhaId)
    {
        $id     = $this->usuarioId();
        $voltar = base_url(self::VOLTAR) . '#atuacao';

        $model = model(ProfissionalLocaisAtendimentoModel::class);

        $linha = $model->where('id', $linhaId)->where('profissional_id', $id)->first();

        if (!$linha) {
            return redirect()->to($voltar)->with('error', 'Área de atuação não encontrada.');
        }

        if ($model->where('profissional_id', $id)->countAllResults() <= 1) {
            return redirect()->to($voltar)->with('error', 'Mantenha ao menos uma área de atuação.');
        }

        $model->delete($linhaId);

        return redirect()->to($voltar)->with('success', 'Área de atuação removida.');
    }
}
