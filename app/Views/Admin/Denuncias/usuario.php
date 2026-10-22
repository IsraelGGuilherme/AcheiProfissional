<?php $this->extend('Templates/admin') ?>

<?php $this->section('title') ?>Denúncias de <?= esc($denunciado->nome ?? 'conta excluída') ?> - Achei Profissional<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/modules/admin.css') ?>">
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<?php
$contaExcluida = $denunciado === null;
$nome          = $contaExcluida ? 'Conta excluída' : $denunciado->nome;
$rotuloTipo    = ['PROFISSIONAL' => 'Profissional', 'COMUM' => 'Contratante'];
$total         = count($denuncias);
$bloqueado     = ! $contaExcluida && $denunciado->status === 'BLOQUEADO';
$podeBloquear  = ! $contaExcluida && in_array($denunciado->tipo, ['PROFISSIONAL', 'COMUM'], true);
$retorno       = 'admin/denuncias/usuario/' . (int) ($denunciado->id ?? 0);
?>

<div class="container-admin mt-5">

    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $contaExcluida ? 'Denúncias de conta excluída' : 'Denúncias de ' . esc($nome) ?></h1>
            <div class="page-description">Somente as denúncias registradas contra este usuário.</div>
        </div>
        <a href="<?= site_url('admin/denuncias') ?>" class="btn-row text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Voltar às denúncias</a>
    </div>

    <!-- Resumo do usuário denunciado -->
    <div class="card-custom">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div class="user-main">
                <div class="avatar">
                    <?php if ($contaExcluida): ?>
                        <i class="bi bi-person-x"></i>
                    <?php else: ?>
                        <?= esc(iniciais_nome($nome)) ?>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="user-name">
                        <?= esc($nome) ?>
                        <?php if (! $contaExcluida): ?>
                            <span class="user-status st-tipo"><?= esc($rotuloTipo[$denunciado->tipo] ?? $denunciado->tipo) ?></span>
                            <span class="user-status <?= $denunciado->status === 'BLOQUEADO' ? 'status-reported' : 'status-approved' ?>"><?= esc($denunciado->status) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="user-meta">
                        <?= $contaExcluida ? 'Esta conta foi excluída; as denúncias foram mantidas para consulta.' : esc($denunciado->email) ?>
                    </div>
                </div>
            </div>
            <div class="row-actions">
                <?php if ($podeBloquear && $bloqueado): ?>
                    <form action="<?= site_url('admin/desbloqueio/' . (int) $denunciado->id) ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="retorno" value="<?= esc($retorno, 'attr') ?>">
                        <button type="submit" class="btn-row"><i class="bi bi-unlock me-1"></i>Desbloquear</button>
                    </form>
                <?php elseif ($podeBloquear): ?>
                    <button
                        type="button"
                        class="btn-row btn-row-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalBloqueio"
                        data-user-id="<?= (int) $denunciado->id ?>"
                        data-user-name="<?= esc($nome, 'attr') ?>">
                        <i class="bi bi-lock me-1"></i>Bloquear
                    </button>
                <?php endif; ?>
                <?php if (! $contaExcluida && ! empty($denunciado->slug)): ?>
                    <a href="<?= site_url('profissionais/' . $denunciado->slug) ?>" target="_blank" rel="noopener" class="btn-row text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Ver perfil público
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="resumo-contagem">
            <span class="admin-badge"><i class="bi bi-flag me-1"></i><?= $total ?> <?= $total === 1 ? 'denúncia' : 'denúncias' ?></span>
            <?php foreach ($statusRotulos as $valor => $rotulo): ?>
                <?php if (($contagem[$valor] ?? 0) > 0): ?>
                    <span class="user-status st-<?= strtolower($valor) ?> m-0"><?= esc($rotulo) ?>: <?= (int) $contagem[$valor] ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Denúncias -->
    <div class="card-custom">
        <div class="section-title mb-2">Denúncias recebidas</div>

        <div class="user-list">
            <?php foreach ($denuncias as $d): ?>
                <div class="denuncia-item">
                    <div class="denuncia-top">
                        <span class="denuncia-motivo"><?= esc($motivos[$d->motivo] ?? $d->motivo) ?></span>
                        <span class="user-status st-<?= strtolower($d->status) ?> m-0"><?= esc($statusRotulos[$d->status] ?? $d->status) ?></span>
                        <span class="denuncia-data">Enviada em <?= esc(date('d/m/Y H:i', strtotime($d->created_at))) ?></span>
                    </div>

                    <?php if (trim((string) $d->descricao) !== ''): ?>
                        <div class="denuncia-desc"><?= nl2br(esc($d->descricao)) ?></div>
                    <?php else: ?>
                        <div class="denuncia-desc vazia">O denunciante não informou uma descrição.</div>
                    <?php endif; ?>

                    <?php if (! empty($d->avaliacao_id)): ?>
                        <div class="denuncia-avaliacao">
                            <?php if (empty($d->avaliacao_existe)): ?>
                                <i class="bi bi-chat-square-text me-1"></i>Avaliação denunciada (já removida).
                            <?php else: ?>
                                <div class="fw-semibold mb-1">
                                    <i class="bi bi-chat-square-text me-1"></i>Avaliação denunciada
                                    <?php if (! empty($d->avaliacao_profissional)): ?> no perfil de <?= esc($d->avaliacao_profissional) ?><?php endif; ?>
                                    · <?= esc(estrelas_texto($d->avaliacao_nota)) ?>
                                </div>
                                <?php if (trim((string) $d->avaliacao_comentario) !== ''): ?>
                                    <div><?= nl2br(esc($d->avaliacao_comentario)) ?></div>
                                <?php else: ?>
                                    <div class="text-secondary fst-italic">Avaliação sem comentário.</div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="denuncia-meta">
                        Protocolo #<?= (int) $d->id ?> ·
                        Denunciante:
                        <?php if (empty($d->denunciante_nome)): ?>
                            usuário removido
                        <?php else: ?>
                            <?= esc($d->denunciante_nome) ?> (<?= esc(strtolower($rotuloTipo[$d->denunciante_tipo] ?? 'usuário')) ?>)
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if ($podeBloquear): ?>
    <?= $this->include('Admin/partials/modal_bloqueio', ['retorno' => $retorno]) ?>
<?php endif; ?>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('assets/js/modules/admin.js') ?>"></script>
<?php $this->endSection() ?>
