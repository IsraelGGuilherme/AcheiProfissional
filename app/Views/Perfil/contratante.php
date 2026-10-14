<?= $this->extend('Templates/public') ?>

<?= $this->section('title') ?>Meu perfil - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <meta name="base-url" content="<?= esc(base_url('/'), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/modules/profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main class="page-narrow">

    <div class="ap-page-header">
        <div>
            <h1>Meu perfil</h1>
            <p class="ap-muted" style="margin:3px 0 0;">Mantenha seus dados atualizados.</p>
        </div>
        <span class="ap-badge ok">Conta ativa</span>
    </div>

    <?= $this->include('Templates/partials/flash') ?>

    <!-- ======================================
    DADOS PESSOAIS
    ======================================= -->

    <section class="ap-card">

        <h2>Dados pessoais</h2>

        <form method="post" action="<?= base_url('perfil/contratante') ?>" enctype="multipart/form-data" novalidate>

            <?= csrf_field() ?>

            <div class="ap-foto-area">

                <div class="ap-avatar ap-avatar-sm">
                    <?php if ($usuario->foto_perfil): ?>
                        <img src="<?= esc(url_foto($usuario->foto_perfil), 'attr') ?>" alt="Sua foto" id="previaFoto">
                    <?php else: ?>
                        <img src="" alt="" id="previaFoto" style="display:none">
                        <span id="inicialFoto"><?= esc(iniciais_nome($comum->nome)) ?></span>
                    <?php endif; ?>
                </div>

                <div class="ap-foto-controles">
                    <input type="file" class="ap-input" name="foto_perfil" accept="image/png,image/jpeg,image/webp" data-preview="#previaFoto">
                    <span class="ap-help">JPG, PNG ou WEBP até 2MB.</span>
                    <?php if ($usuario->foto_perfil): ?>
                        <label class="ap-check"><input type="checkbox" name="remover_foto_perfil" value="1"> Remover foto atual</label>
                    <?php endif; ?>
                </div>

            </div>

            <div class="ap-field">
                <label for="nome">Nome completo</label>
                <input type="text" class="ap-input" id="nome" name="nome" maxlength="150" value="<?= esc(old('nome', $comum->nome), 'attr') ?>" required>
            </div>

            <div class="ap-form-row">

                <div class="ap-field">
                    <label for="cpf">CPF</label>
                    <input type="text" class="ap-input" id="cpf" name="cpf" inputmode="numeric" data-mask="cpf" maxlength="14" value="<?= esc(old('cpf', formata_documento($comum->cpf)), 'attr') ?>" required>
                </div>

                <div class="ap-field">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="ap-input" id="telefone" name="telefone" inputmode="tel" data-mask="phone" maxlength="15" value="<?= esc(old('telefone', formata_telefone($comum->telefone)), 'attr') ?>" required>
                </div>

            </div>

            <div class="ap-field">
                <label for="email">E-mail</label>
                <input type="email" class="ap-input" id="email" value="<?= esc($usuario->email, 'attr') ?>" readonly>
                <div class="ap-help">O e-mail de acesso não pode ser alterado por aqui.</div>
            </div>

            <button type="submit" class="ap-btn ap-btn-primary">Salvar dados</button>

        </form>

    </section>

    <!-- ======================================
    ENDEREÇO
    ======================================= -->

    <section class="ap-card" id="localizacao">

        <h2>Meu endereço</h2>

        <?= $this->setVar('action', base_url('perfil/contratante/endereco'))->setVar('endereco', $endereco)->include('Perfil/partials/endereco') ?>

    </section>

    <!-- ======================================
    ATIVIDADE
    ======================================= -->

    <section class="ap-card">

        <h2>Minhas avaliações</h2>

        <p>
            Você já avaliou <strong><?= (int) $totalAvaliacoes ?></strong>
            <?= (int) $totalAvaliacoes === 1 ? 'profissional' : 'profissionais' ?>.
            Para avaliar ou atualizar uma avaliação, abra o perfil do profissional na busca.
        </p>

        <a href="<?= base_url('/') ?>" class="ap-btn ap-btn-outline">Buscar profissionais</a>

    </section>

    <?= $this->setVar('tipoConta', 'COMUM')->include('Perfil/partials/excluir_conta') ?>

</main>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/modules/perfil.js') ?>"></script>
<?= $this->endSection() ?>
