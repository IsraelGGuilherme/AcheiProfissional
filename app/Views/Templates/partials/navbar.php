<?php
$currentUri = uri_string();
$activeNav  = $activeNav ?? null;
?>
<header class="navbar-custom">
    <div class="container navbar-inner-wrap">

        <a href="<?= base_url('/') ?>" class="brand">
            Achei Profissional
        </a>

        <button
            class="navbar-toggler-custom"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarPadrao"
            aria-controls="navbarPadrao"
            aria-expanded="false"
            aria-label="Abrir menu de navegação">
            <i class="bi bi-list"></i>
        </button>

        <nav class="collapse nav-collapse" id="navbarPadrao">
            <div class="nav-links-wrap">

                <a href="<?= base_url('/') ?>" class="nav-link-custom <?= ($activeNav === 'home' || url_is('/') || $currentUri === '') ? 'active' : '' ?>">
                    Buscar
                </a>

                <a href="<?= base_url('register/usuario') ?>" class="nav-link-custom <?= ($activeNav === 'contratante' || url_is('register/usuario*')) ? 'active' : '' ?>">
                    Sou Contratante
                </a>

                <a href="<?= base_url('register/profissional') ?>" class="nav-link-custom <?= ($activeNav === 'profissional' || url_is('register/profissional*')) ? 'active' : '' ?>">
                    Sou Profissional
                </a>

                <a href="<?= base_url('admin') ?>" class="nav-link-custom <?= ($activeNav === 'admin' || url_is('admin*')) ? 'active' : '' ?>">
                    Admin
                </a>
                <a href="<?= base_url('login') ?>" class="nav-link-custom nav-link-login <?= ($activeNav === 'login' || url_is('login*')) ? 'active' : '' ?>">
                    <?= session()->has('usuario') ? 'Logado' : 'Entrar' ?>
                </a>

            </div>
        </nav>

    </div>
</header>

