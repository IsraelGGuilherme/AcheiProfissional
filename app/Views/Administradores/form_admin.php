<?php $this->extend('Templates/admin') ?>

<?php $this->section('title') ?>Novo administrador - Achei Profissional<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url("assets/css/modules/admin.css") ?>">
<?php $this->endSection() ?>


<?php $this->section('content') ?>

<div class="container py-4" style="max-width: 560px;">
    <form method="post"
        action="<?= esc($action, 'attr') ?>"
        class="card border rounded-4 shadow-sm p-4"
        novalidate>

        <?= csrf_field() ?>

        <h1 class="h5 fw-bold mb-1">Novo administrador</h1>
        <p class="text-secondary small mb-3">Esse cadastro cria um usuário com permissão exclusivamente administrativa.</p>

        <div class="row g-3">

            <div class="col-12">
                <label for="email" class="form-label small fw-semibold mb-1">E-mail</label>
                <input type="email"
                    id="email"
                    name="email"
                    class="form-control form-control-sm"
                    placeholder="admin@exemplo.com"
                    value="<?= esc(old('email')) ?>"
                    autocomplete="off"
                    required>
            </div>

            <div class="col-sm-6">
                <label for="senha" class="form-label small fw-semibold mb-1">Senha</label>
                <input type="password"
                    id="senha"
                    name="senha"
                    class="form-control form-control-sm"
                    placeholder="Mínimo de 8 caracteres"
                    autocomplete="new-password"
                    minlength="8"
                    required>
            </div>

            <div class="col-sm-6">
                <label for="confirmar_senha" class="form-label small fw-semibold mb-1">Confirmar senha</label>
                <input type="password"
                    id="confirmar_senha"
                    name="confirmar_senha"
                    class="form-control form-control-sm"
                    placeholder="Repita a senha"
                    autocomplete="new-password"
                    required>
            </div>

            <div class="col-12 d-flex gap-2 pt-1">
                <button type="submit" class="btn btn-primary btn-sm px-4">Salvar administrador</button>
                <a href="<?= site_url('admin') ?>" class="btn btn-outline-secondary btn-sm">Cancelar</a>
            </div>

        </div>
    </form>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('assets/js/modules/admin.js') ?>"></script>
<?php $this->endSection() ?>