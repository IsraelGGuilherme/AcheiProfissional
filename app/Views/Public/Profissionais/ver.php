<?= $this->extend('Templates/public') ?>

<?= $this->section('title') ?><?= esc($profissional->nome) ?> - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/modules/profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php

    $fotoPerfil = url_foto($profissional->foto_perfil);
    $fotoCapa   = url_foto($profissional->foto_capa);
    $linkZap    = link_whatsapp($profissional->whatsapp);
    $total      = (int) $profissional->total_avaliacoes;
    $localBase  = trim(implode(', ', array_filter([$profissional->bairro_nome, $profissional->cidade_nome])));

    if ($localBase !== '' && $profissional->uf) {
        $localBase .= ' - ' . $profissional->uf;
    }

    $nomeAutor = static function (?string $nome): string {
        $partes = preg_split('/\s+/', trim((string) $nome), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($partes)) {
            return 'Usuário';
        }

        return count($partes) > 1 ? $partes[0] . ' ' . mb_substr(end($partes), 0, 1) . '.' : $partes[0];
    };

    $notaAtual = $minhaAvaliacao->nota ?? null;

?>

<main class="page-wide">

    <a class="ap-back" href="<?= base_url('/') ?>">
        <i class="bi bi-arrow-left"></i> Voltar para a busca
    </a>

    <?= $this->include('Templates/partials/flash') ?>

    <!-- ======================================
    CABEÇALHO
    ======================================= -->

    <section class="ap-hero">

        <div class="ap-hero-capa" <?= $fotoCapa ? 'style="background-image:url(\'' . esc($fotoCapa, 'attr') . '\')"' : '' ?>></div>

        <div class="ap-hero-body com-capa">

            <div class="ap-avatar">
                <?php if ($fotoPerfil): ?>
                    <img src="<?= esc($fotoPerfil, 'attr') ?>" alt="Foto de <?= esc($profissional->nome, 'attr') ?>">
                <?php else: ?>
                    <?= esc(iniciais_nome($profissional->nome)) ?>
                <?php endif; ?>
            </div>

            <div>

                <h1><?= esc($profissional->nome) ?></h1>

                <?php if (!empty($profissional->categorias)): ?>
                    <div class="ap-categoria">
                        <?= esc(implode(' · ', array_map(static fn ($c) => $c->nome, $profissional->categorias))) ?>
                    </div>
                <?php endif; ?>

                <div class="ap-stars">
                    <?php if ($total > 0): ?>
                        <?= estrelas_texto($profissional->media) ?>
                        <small><?= formata_media($profissional->media) ?> · <?= rotulo_avaliacoes($total) ?></small>
                    <?php else: ?>
                        <small style="margin-left:0">Ainda sem avaliações</small>
                    <?php endif; ?>
                </div>

            </div>

        </div>

    </section>

    <div class="ap-grid" style="margin-top:16px;">

        <div>

            <section class="ap-card">
                <h2>Sobre o profissional</h2>
                <?php if (!empty($profissional->descricao)): ?>
                    <p style="white-space:pre-line;"><?= esc($profissional->descricao) ?></p>
                <?php else: ?>
                    <p>Este profissional ainda não escreveu uma descrição.</p>
                <?php endif; ?>
            </section>

            <?php if (!empty($profissional->categorias)): ?>
                <section class="ap-card">
                    <h2>Serviços oferecidos</h2>
                    <?php foreach ($profissional->categorias as $categoria): ?>
                        <a class="ap-chip text-decoration-none" href="<?= base_url('/?categoria=' . $categoria->id) ?>"><?= esc($categoria->nome) ?></a>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

            <?php if (!empty($profissional->horarios)): ?>
                <section class="ap-card">
                    <h2>Horários de atendimento</h2>
                    <table class="ap-horarios">
                        <?php foreach ($profissional->horarios as $dia => $faixas): ?>
                            <tr>
                                <td><?= esc(dia_semana_rotulo($dia)) ?></td>
                                <td>
                                    <?= esc(implode(' · ', array_map(static fn ($f) => $f->abre . ' às ' . $f->fecha, $faixas))) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </section>
            <?php endif; ?>

        </div>

        <aside>

            <section class="ap-card">

                <h2>Informações</h2>

                <?php if ($localBase !== ''): ?>
                    <div class="ap-info"><i class="bi bi-geo-alt"></i><span><?= esc($localBase) ?></span></div>
                <?php endif; ?>

                <?php if (!empty($profissional->locais)): ?>
                    <div class="ap-info">
                        <i class="bi bi-compass"></i>
                        <span>
                            Atende em:
                            <?= esc(implode('; ', array_map(static fn ($l) => $l->rotulo, array_slice($profissional->locais, 0, 6)))) ?><?= count($profissional->locais) > 6 ? '; e outras localidades' : '' ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($profissional->telefone_contato)): ?>
                    <div class="ap-info"><i class="bi bi-telephone"></i><span><?= esc(formata_telefone($profissional->telefone_contato)) ?></span></div>
                <?php endif; ?>

                <?php if (!empty($profissional->instagram)): ?>
                    <div class="ap-info">
                        <i class="bi bi-instagram"></i>
                        <a href="https://instagram.com/<?= esc(rawurlencode($profissional->instagram), 'attr') ?>" target="_blank" rel="noopener">@<?= esc($profissional->instagram) ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($profissional->site)): ?>
                    <div class="ap-info">
                        <i class="bi bi-globe"></i>
                        <a href="<?= esc($profissional->site, 'attr') ?>" target="_blank" rel="noopener nofollow"><?= esc(preg_replace('#^https?://#i', '', $profissional->site)) ?></a>
                    </div>
                <?php endif; ?>

                <div class="ap-info"><i class="bi bi-patch-check"></i><span>E-mail verificado</span></div>

            </section>

            <section class="ap-card">

                <?php if ($linkZap): ?>
                    <a class="ap-btn ap-btn-primary ap-btn-block" href="<?= esc($linkZap, 'attr') ?>" target="_blank" rel="noopener">
                        <i class="bi bi-whatsapp"></i> Entrar em contato
                    </a>
                <?php endif; ?>

                <a class="ap-btn ap-btn-outline ap-btn-block" style="margin-top:10px;" href="<?= base_url('/') ?>">
                    Ver outros profissionais
                </a>

                <?php if (!$ehOProprio): ?>
                    <?php if ($usuario): ?>
                        <button type="button" class="ap-link-danger" id="abrirDenuncia">
                            <i class="bi bi-flag"></i> Denunciar perfil
                        </button>
                    <?php else: ?>
                        <a class="ap-link-danger text-decoration-none" href="<?= base_url('login') ?>">
                            <i class="bi bi-flag"></i> Entre para denunciar este perfil
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

            </section>

        </aside>

    </div>

    <!-- ======================================
    AVALIAÇÕES
    ======================================= -->

    <section class="ap-card" id="avaliacoes">

        <h2>Avalie este profissional</h2>

        <?php if ($podeAvaliar): ?>

            <p>Conte como foi sua experiência para ajudar outras pessoas.</p>

            <form method="post" action="<?= base_url('profissionais/' . $profissional->slug . '/avaliar') ?>">

                <?= csrf_field() ?>

                <div class="ap-stars-select" role="radiogroup" aria-label="Escolha uma nota de 1 a 5 estrelas">
                    <?php for ($n = 5; $n >= 1; $n--): ?>
                        <input type="radio" id="star<?= $n ?>" name="nota" value="<?= $n ?>" <?= (int) $notaAtual === $n ? 'checked' : '' ?> required>
                        <label for="star<?= $n ?>" title="<?= $n ?> <?= $n === 1 ? 'estrela' : 'estrelas' ?>">★</label>
                    <?php endfor; ?>
                </div>

                <textarea class="ap-textarea" name="comentario" maxlength="1000" placeholder="Escreva um comentário sobre o serviço (opcional)" style="margin-bottom:10px;"><?= esc($minhaAvaliacao->comentario ?? '') ?></textarea>

                <button type="submit" class="ap-btn ap-btn-primary">
                    <i class="bi bi-send"></i> <?= $minhaAvaliacao ? 'Atualizar avaliação' : 'Enviar avaliação' ?>
                </button>

            </form>

        <?php elseif ($usuario): ?>

            <p>Apenas contratantes podem avaliar profissionais.</p>

        <?php else: ?>

            <p>Para avaliar este profissional, entre na sua conta.</p>
            <a href="<?= base_url('login') ?>" class="ap-btn ap-btn-outline">Entrar</a>

        <?php endif; ?>

    </section>

    <section class="ap-card">

        <h2>Avaliações recentes</h2>

        <?php if (empty($avaliacoes)): ?>

            <p>Nenhuma avaliação ainda. Seja o primeiro a avaliar.</p>

        <?php endif; ?>

        <?php foreach ($avaliacoes as $avaliacao): ?>

            <article class="ap-review">

                <div class="ap-review-head">
                    <strong><?= esc($nomeAutor($avaliacao->autor_nome)) ?></strong>
                    <span class="ap-stars"><?= estrelas_texto($avaliacao->nota) ?></span>
                </div>

                <?php if (!empty($avaliacao->comentario)): ?>
                    <p style="white-space:pre-line;"><?= esc($avaliacao->comentario) ?></p>
                <?php endif; ?>

                <span class="ap-muted">Publicado <?= esc(tempo_atras($avaliacao->created_at)) ?></span>

            </article>

        <?php endforeach; ?>

    </section>

</main>

<?php if ($usuario && !$ehOProprio): ?>

    <!-- ======================================
    MODAL DE DENÚNCIA
    ======================================= -->

    <div class="ap-modal-backdrop" id="modalDenuncia" role="dialog" aria-modal="true" aria-labelledby="tituloDenuncia">

        <div class="ap-modal-box">

            <div class="ap-modal-head">
                <div>
                    <h2 id="tituloDenuncia">Denunciar perfil</h2>
                    <p class="ap-muted" style="margin:7px 0 0;">Ajude a manter a comunidade segura.</p>
                </div>
                <button type="button" class="ap-modal-close" id="fecharDenuncia" aria-label="Fechar">&times;</button>
            </div>

            <form method="post" action="<?= base_url('profissionais/' . $profissional->slug . '/denunciar') ?>">

                <?= csrf_field() ?>

                <label class="ap-label" for="motivoDenuncia">Motivo da denúncia</label>
                <select class="ap-select" name="motivo" id="motivoDenuncia" required>
                    <option value="">Selecione um motivo</option>
                    <?php foreach ($motivosDenuncia as $valor => $rotulo): ?>
                        <option value="<?= esc($valor, 'attr') ?>"><?= esc($rotulo) ?></option>
                    <?php endforeach; ?>
                </select>

                <label class="ap-label" for="descricaoDenuncia">Descreva o que aconteceu</label>
                <textarea class="ap-textarea" name="descricao" id="descricaoDenuncia" maxlength="2000" placeholder="Informe detalhes que possam ajudar na análise."></textarea>

                <div class="ap-modal-actions">
                    <button type="button" class="ap-btn ap-btn-outline" id="cancelarDenuncia">Cancelar</button>
                    <button type="submit" class="ap-btn ap-btn-danger">Enviar denúncia</button>
                </div>

            </form>

        </div>

    </div>

<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

    (function () {

        const modal = document.getElementById('modalDenuncia');

        if (!modal) {
            return;
        }

        const abrir  = () => modal.classList.add('open');
        const fechar = () => modal.classList.remove('open');

        document.getElementById('abrirDenuncia').addEventListener('click', abrir);
        document.getElementById('fecharDenuncia').addEventListener('click', fechar);
        document.getElementById('cancelarDenuncia').addEventListener('click', fechar);

        modal.addEventListener('click', (evento) => {
            if (evento.target === modal) {
                fechar();
            }
        });

        document.addEventListener('keydown', (evento) => {
            if (evento.key === 'Escape') {
                fechar();
            }
        });

    })();

</script>
<?= $this->endSection() ?>
