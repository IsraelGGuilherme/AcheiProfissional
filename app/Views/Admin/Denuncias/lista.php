<?php $this->extend('Templates/admin') ?>

<?php $this->section('title') ?>Denúncias - Achei Profissional<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/modules/admin.css') ?>">
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<?php
$rotuloTipo = ['PROFISSIONAL' => 'Profissional', 'COMUM' => 'Contratante'];
?>

<div class="container-admin mt-5">

    <div class="page-header">
        <div>
            <h1 class="page-title">Denúncias</h1>
            <div class="page-description">Denúncias recebidas, agrupadas por usuário denunciado. Abra um usuário para ver todas as denúncias contra ele.</div>
        </div>
        <a href="<?= site_url('admin') ?>" class="btn-row text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
    </div>

    <div class="card-custom">
        <form method="get" class="filter-bar">
            <input class="form-control filter-search" name="q" value="<?= esc($busca) ?>" placeholder="Pesquisar usuário denunciado pelo nome...">
            <span class="admin-badge"><i class="bi bi-flag me-1"></i><?= count($grupos) ?> usuário(s) · <?= (int) $totalDenuncias ?> denúncia(s)</span>
        </form>

        <div class="user-list">
            <?php if (empty($grupos)): ?>
                <div class="p-3 text-secondary small">Nenhuma denúncia encontrada.</div>
            <?php endif; ?>

            <?php foreach ($grupos as $g): ?>
                <?php
                $contaExcluida = empty($g->tipo);
                $total         = (int) $g->total;
                $pendentes     = (int) $g->pendentes;
                $emAnalise     = (int) $g->em_analise;
                $bloqueado     = ($g->status_conta ?? '') === 'BLOQUEADO';
                // Só contas de profissionais/contratantes que ainda existem podem ser bloqueadas.
                $podeBloquear  = ! $contaExcluida && in_array($g->tipo, ['PROFISSIONAL', 'COMUM'], true);
                ?>
                <div class="user-row">
                    <div class="user-main">
                        <div class="avatar">
                            <?php if ($contaExcluida): ?>
                                <i class="bi bi-person-x"></i>
                            <?php else: ?>
                                <?= esc(iniciais_nome($g->nome)) ?>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="user-name">
                                <?= $contaExcluida ? 'Conta excluída' . ($g->denunciado_id ? ' (ID ' . (int) $g->denunciado_id . ')' : '') : esc($g->nome) ?>
                                <?php if (! $contaExcluida): ?>
                                    <span class="user-status st-tipo"><?= esc($rotuloTipo[$g->tipo] ?? $g->tipo) ?></span>
                                    <?php if ($bloqueado): ?>
                                        <span class="user-status status-reported">BLOQUEADO</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="user-meta">
                                <?= $total ?> <?= $total === 1 ? 'denúncia' : 'denúncias' ?>
                                <?php if ($pendentes > 0): ?> · <span class="text-warning-emphasis fw-semibold"><?= $pendentes ?> pendente(s)</span><?php endif; ?>
                                <?php if ($emAnalise > 0): ?> · <?= $emAnalise ?> em análise<?php endif; ?>
                                · Última em <?= esc(date('d/m/Y H:i', strtotime($g->ultima_em))) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-actions">
                        <?php if ($podeBloquear && $bloqueado): ?>
                            <form action="<?= site_url('admin/desbloqueio/' . (int) $g->denunciado_id) ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="retorno" value="admin/denuncias">
                                <button type="submit" class="btn-row"><i class="bi bi-unlock me-1"></i>Desbloquear</button>
                            </form>
                        <?php elseif ($podeBloquear): ?>
                            <button
                                type="button"
                                class="btn-row btn-row-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBloqueio"
                                data-user-id="<?= (int) $g->denunciado_id ?>"
                                data-user-name="<?= esc($g->nome, 'attr') ?>">
                                <i class="bi bi-lock me-1"></i>Bloquear
                            </button>
                        <?php endif; ?>
                        <a href="<?= site_url('admin/denuncias/usuario/' . (int) $g->denunciado_id) ?>" class="btn-row text-decoration-none">
                            <i class="bi bi-flag me-1"></i>Visualizar denúncias
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->include('Admin/partials/modal_bloqueio', ['retorno' => 'admin/denuncias']) ?>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('assets/js/modules/admin.js') ?>"></script>
<?php $this->endSection() ?>
