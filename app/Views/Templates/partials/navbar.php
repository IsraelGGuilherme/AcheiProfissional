<?php

$currentUri = uri_string();
$activeNav  = $activeNav ?? null;
$estaLogado = session()->has('usuario');
$usuarioSessao = $estaLogado ? session('usuario') : null;
$ehAdmin = $usuarioSessao && ($usuarioSessao->tipo ?? null) === 'ADMIN';

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
                <a href="<?= base_url('quemsomos') ?>" class="nav-link-custom <?= ($activeNav === 'quemsomos' || url_is('quemsomos*')) ? 'active' : '' ?>">
                    Quem Somos
                </a>
                <?php if ($estaLogado): ?>
                    <a href="<?= base_url('perfil') ?>" class="nav-link-custom <?= ($activeNav === 'perfil' || url_is('perfil*')) ? 'active' : '' ?>">
                        Meu perfil
                    </a>
                    <?php if ($ehAdmin): ?>
                        <a href="<?= site_url('admin') ?>" class="nav-link-custom <?= url_is('admin*') ? 'active' : '' ?>">
                            <i class="bi bi-shield-lock me-1"></i> Administração
                        </a>
                    <?php endif; ?>
                    <a href="<?= base_url('logout') ?>" class="nav-link-custom nav-link-logout">
                        Sair
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="nav-link-custom nav-link-login <?= ($activeNav === 'login' || url_is('login*')) ? 'active' : '' ?>">
                        Entrar
                    </a>
                <?php endif; ?>

            </div>
        </nav>

    </div>
</header>

