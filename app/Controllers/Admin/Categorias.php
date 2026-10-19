<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * CRUD de categorias (painel admin).
 * Controller novo: não altera nada do fluxo de cadastro (Auth\Categorias).
 */
class Categorias extends BaseController
{
    private const LISTA = 'admin/categorias';

    public function index()
    {
        $busca = trim((string) $this->request->getGet('q'));

        $builder = db_connect()->table('categorias c')
            ->select('c.id, c.nome, c.slug, COUNT(pc.id) AS qtd_profissionais')
            ->join('profissional_categoria pc', 'pc.categoria_id = c.id', 'left')
            ->groupBy('c.id, c.nome, c.slug')
            ->orderBy('c.nome', 'ASC');

        if ($busca !== '') {
            $builder->like('c.nome', $busca);
        }

        return view('Admin/Categorias/lista', [
            'categorias' => $builder->get()->getResultObject(),
            'busca'      => $busca,
        ]);
    }

    public function criar()
    {
        $nome = $this->normalizarNome($this->request->getPost('nome'));

        if (! $this->validarNome($nome)) {
            return redirect()->to(self::LISTA)->withInput()->with('error', $this->validator->getErrors());
        }

        model('CategoriasModel')->insert([
            'nome' => $nome,
            'slug' => $this->gerarSlug($nome),
        ]);

        return redirect()->to(self::LISTA)->with('success', 'Categoria criada com sucesso.');
    }

    public function editar(int $id)
    {
        $model     = model('CategoriasModel');
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to(self::LISTA)->with('error', 'Categoria não encontrada.');
        }

        $nome = $this->normalizarNome($this->request->getPost('nome'));

        if (! $this->validarNome($nome, $id)) {
            return redirect()->to(self::LISTA)->withInput()->with('error', $this->validator->getErrors());
        }

        // O slug só muda se o nome mudar de fato (evita quebrar links já compartilhados à toa)
        $dados = ['nome' => $nome];
        if (mb_strtolower($nome) !== mb_strtolower($categoria->nome)) {
            $dados['slug'] = $this->gerarSlug($nome, $id);
        }

        $model->update($id, $dados);

        return redirect()->to(self::LISTA)->with('success', 'Categoria atualizada com sucesso.');
    }

    public function excluir(int $id)
    {
        $model     = model('CategoriasModel');
        $categoria = $model->find($id);

        if (! $categoria) {
            return redirect()->to(self::LISTA)->with('error', 'Categoria não encontrada.');
        }

        // A FK é CASCADE: apagar uma categoria em uso tiraria a categoria dos profissionais sem aviso.
        $emUso = model('ProfissionalCategoriaModel')->where('categoria_id', $id)->countAllResults();

        if ($emUso > 0) {
            return redirect()->to(self::LISTA)->with(
                'error',
                "A categoria \"{$categoria->nome}\" está em uso por {$emUso} profissional(is) e não pode ser excluída."
            );
        }

        $model->delete($id);

        return redirect()->to(self::LISTA)->with('success', 'Categoria excluída com sucesso.');
    }

    private function normalizarNome($nome): string
    {
        return trim(preg_replace('/\s+/', ' ', (string) $nome));
    }

    private function validarNome(string $nome, ?int $ignorarId = null): bool
    {
        $unico = $ignorarId ? "is_unique[categorias.nome,id,{$ignorarId}]" : 'is_unique[categorias.nome]';

        return $this->validateData(['nome' => $nome], [
            'nome' => [
                'label'  => 'Nome',
                'rules'  => "required|min_length[2]|max_length[100]|{$unico}",
                'errors' => ['is_unique' => 'Já existe uma categoria com esse nome.'],
            ],
        ]);
    }

    private function gerarSlug(string $nome, ?int $ignorarId = null): string
    {
        helper('text');

        $base = url_title(convert_accented_characters($nome), '-', true) ?: 'categoria';
        $slug = $base;
        $i    = 2;
        $model = model('CategoriasModel');

        while (true) {
            $q = $model->where('slug', $slug);
            if ($ignorarId) {
                $q->where('id !=', $ignorarId);
            }
            if (! $q->first()) {
                return $slug;
            }
            $slug = $base . '-' . $i++;
        }
    }
}
