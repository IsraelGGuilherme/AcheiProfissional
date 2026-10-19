<?php $this->extend('Templates/admin') ?>

<?php $this->section('title') ?>Administração - Achei Profissional<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url("assets/css/modules/admin.css") ?>">
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<div id="telaAdministradores" class="container-admin mt-5">

    <div class="page-header">
        <div>
            <h1 class="page-title">Administradores</h1>
            <div class="page-description">Gerencie exclusivamente as contas que possuem acesso administrativo.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('admin') ?>" data-admin-view="dashboard" class="btn-row text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Voltar</a>

            <a class="btn-admin" href="<?= site_url('admin/administradores/novo') ?>">
                <i class="bi bi-person-plus me-1"></i>
                Novo administrador
            </a>
        </div>
    </div>

    <div class="card-custom">
        <div class="filter-bar">
            <input class="form-control filter-search" placeholder="Pesquisar administrador pelo nome ou e-mail...">
            <span class="admin-badge"><i class="bi bi-shield-check me-1"></i><?= count($admins) ?> administrador(es)</span>
        </div>
        <div class="user-list">

            <?php foreach ($admins as $a): ?>
                <div class="admin-row" data-busca="<?= esc(mb_strtolower($a->email), 'attr') ?>">
                    <div class="user-main">
                        <div class="avatar"><?= esc(strtoupper(mb_substr($a->email, 0, 2))) ?></div>
                        <div>
                            <div class="admin-name"><?= esc($a->email) ?></div>
                            <div class="admin-email"><?= esc(ucfirst(mb_strtolower($a->status ?? ''))) ?></div>
                        </div>
                    </div>
                    <div class="row-actions">
                        <span class="admin-tag">Administrador</span>
                        <a class="btn-row text-decoration-none" href="<?= site_url('admin/administradores/editar/' . (int) $a->id) ?>">Editar</a>
                        <button type="button" class="btn-row btn-row-danger" data-bs-toggle="modal" data-bs-target="#modalExcluirAdmin"
                                data-id="<?= (int) $a->id ?>" data-email="<?= esc($a->email, 'attr') ?>">
                            <i class="bi bi-trash me-1"></i>Excluir
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="modal fade modal-admin" id="modalExcluirAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content">
            <form method="post" action="<?= site_url('admin/administradores/excluir') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="excluirAdminId">
                <div class="modal-header">
                    <h5 class="modal-title fs-6"><i class="bi bi-trash me-1"></i> Excluir administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body small">Excluir <strong id="excluirAdminEmail"></strong>? Essa ação não pode ser desfeita.</div>
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
<script src="<?= base_url('assets/js/modules/admin.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('modalExcluirAdmin').addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget; if (!b) return;
    document.getElementById('excluirAdminId').value = b.dataset.id;
    document.getElementById('excluirAdminEmail').textContent = b.dataset.email;
  });
  // Filtro da barra de pesquisa (no navegador, pela lista já carregada)
  const busca = document.querySelector('.filter-search');
  busca.addEventListener('input', () => {
    const t = busca.value.trim().toLowerCase();
    document.querySelectorAll('.admin-row').forEach(r => r.classList.toggle('d-none', !r.dataset.busca.includes(t)));
  });
});
</script>
<?php $this->endSection() ?>