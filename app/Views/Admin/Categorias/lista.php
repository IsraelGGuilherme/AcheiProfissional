<?php $this->extend('Templates/admin') ?>

<?php $this->section('title') ?>Categorias - Achei Profissional<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/modules/admin.css') ?>">
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="container-admin mt-5">

    <div class="page-header">
        <div>
            <h1 class="page-title">Categorias</h1>
            <div class="page-description">Crie, edite e remova as categorias que os profissionais podem escolher.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('admin') ?>" class="btn-row text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
            <button type="button" class="btn-admin" data-bs-toggle="modal" data-bs-target="#modalCategoria"
                    data-acao="<?= site_url('admin/categorias/criar') ?>" data-titulo="Nova categoria" data-nome="">
                <i class="bi bi-plus-lg me-1"></i>Nova categoria
            </button>
        </div>
    </div>

    <div class="card-custom">
        <form method="get" class="filter-bar">
            <input class="form-control filter-search" name="q" value="<?= esc($busca) ?>" placeholder="Pesquisar categoria pelo nome...">
            <span class="admin-badge"><i class="bi bi-tags me-1"></i><?= count($categorias) ?> categoria(s)</span>
        </form>

        <div class="user-list">
            <?php if (empty($categorias)): ?>
                <div class="p-3 text-secondary small">Nenhuma categoria encontrada.</div>
            <?php endif; ?>

            <?php foreach ($categorias as $c): ?>
                <div class="admin-row">
                    <div class="user-main">
                        <div class="avatar"><i class="bi bi-tag"></i></div>
                        <div>
                            <div class="admin-name"><?= esc($c->nome) ?></div>
                            <div class="admin-email"><?= esc($c->slug) ?> · <?= (int) $c->qtd_profissionais ?> profissional(is)</div>
                        </div>
                    </div>
                    <div class="row-actions">
                        <button type="button" class="btn-row" data-bs-toggle="modal" data-bs-target="#modalCategoria"
                                data-acao="<?= site_url('admin/categorias/editar/' . (int) $c->id) ?>"
                                data-titulo="Editar categoria" data-nome="<?= esc($c->nome, 'attr') ?>">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </button>
                        <button type="button" class="btn-row btn-row-danger" data-bs-toggle="modal" data-bs-target="#modalExcluirCategoria"
                                data-acao="<?= site_url('admin/categorias/excluir/' . (int) $c->id) ?>"
                                data-nome="<?= esc($c->nome, 'attr') ?>">
                            <i class="bi bi-trash me-1"></i>Excluir
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal: criar / editar -->
<div class="modal fade modal-admin" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content">
            <form method="post" action="" id="formCategoria">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fs-6" id="tituloModalCategoria">Nova categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <label for="nomeCategoria" class="form-label small fw-semibold mb-1">Nome</label>
                    <input type="text" class="form-control" id="nomeCategoria" name="nome" maxlength="100" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-admin">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: excluir -->
<div class="modal fade modal-admin" id="modalExcluirCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content">
            <form method="post" action="" id="formExcluirCategoria">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fs-6"><i class="bi bi-trash me-1"></i> Excluir categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body small">
                    Excluir <strong id="nomeExcluirCategoria"></strong>? Essa ação não pode ser desfeita.
                    Categorias em uso por profissionais não podem ser excluídas.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-admin">Confirmar exclusão</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
// Preenche os modais de categoria com o item clicado (arquivo próprio da tela, sem tocar no admin.js)
document.addEventListener('DOMContentLoaded', () => {
  const modalCat = document.getElementById('modalCategoria');
  modalCat.addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget; if (!b) return;
    document.getElementById('formCategoria').action = b.dataset.acao;
    document.getElementById('tituloModalCategoria').textContent = b.dataset.titulo;
    document.getElementById('nomeCategoria').value = b.dataset.nome;
  });
  modalCat.addEventListener('shown.bs.modal', () => document.getElementById('nomeCategoria').focus());

  document.getElementById('modalExcluirCategoria').addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget; if (!b) return;
    document.getElementById('formExcluirCategoria').action = b.dataset.acao;
    document.getElementById('nomeExcluirCategoria').textContent = b.dataset.nome;
  });
});
</script>
<?php $this->endSection() ?>
