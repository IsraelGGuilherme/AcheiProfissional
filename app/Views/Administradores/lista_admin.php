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
            <span class="admin-badge"><i class="bi bi-shield-check me-1"></i>3 administradores</span>
        </div>
        <div class="user-list">

            <div class="admin-row">
                <div class="user-main">
                    <div class="avatar">JC</div>
                    <div>
                        <div class="admin-name">Juliana Costa</div>
                        <div class="admin-email">juliana@acheiprofissional.com.br</div>
                    </div>
                </div>
                <div class="row-actions">
                    <span class="admin-tag">Administrador</span>
                    <button class="btn-row">Editar</button>
                    <button class="btn-row btn-row-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('assets/js/modules/admin.js') ?>"></script>
<?php $this->endSection() ?>