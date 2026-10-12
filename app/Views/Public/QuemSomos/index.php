<?= $this->extend('Templates/public') ?>

<?= $this->section('title') ?>Quem somos - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/modules/profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="page-narrow">

    <section class="ap-card">

        <h1 class="ap-title">Quem somos</h1>

        <p class="ap-text">
            O <strong>Achei Profissional</strong> é um projeto de extensão universitária que aproxima moradores de
            Muriaé-MG e região de profissionais autônomos de confiança, como eletricistas, encanadores, diaristas e pedreiros.
        </p>

        <p class="ap-text">
            Aqui você pesquisa por categoria, cidade e bairro, confere as avaliações de outras pessoas e chama o profissional
            direto pelo WhatsApp. Para quem trabalha por conta própria, é uma vitrine gratuita para ser encontrado por mais clientes.
        </p>

        <div class="ap-actions">
            <a href="<?= base_url('/') ?>" class="ap-btn ap-btn-primary">Buscar profissionais</a>
            <a href="<?= base_url('register') ?>" class="ap-btn ap-btn-outline">Criar conta</a>
        </div>

    </section>

</main>

<?= $this->endSection() ?>
