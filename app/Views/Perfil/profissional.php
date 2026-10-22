<?= $this->extend('Templates/public') ?>

<?= $this->section('title') ?>Meu perfil - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <meta name="base-url" content="<?= esc(base_url('/'), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/modules/profile.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php

    $fotoPerfil = url_foto($usuario->foto_perfil);
    $fotoCapa   = url_foto($profissional->foto_capa);
    $tipoAtual  = old('tipo_pessoa', $profissional->tipo_pessoa ?: 'PF');
    $ehPj       = $tipoAtual === 'PJ';
    $total      = (int) ($nota->total ?? 0);

?>

<main class="page-wide">

    <div class="ap-page-header">
        <div>
            <h1>Meu perfil</h1>
            <p class="ap-muted" style="margin:3px 0 0;">Estas informações aparecem na sua página pública.</p>
            <div class="ap-resumo-nota">
                <?php if ($total > 0): ?>
                    <span class="ap-stars"><?= estrelas_texto($nota->media) ?></span>
                    <strong><?= formata_media($nota->media) ?></strong>
                    <span class="ap-muted">· <?= rotulo_avaliacoes($total) ?></span>
                <?php else: ?>
                    <span class="ap-muted">Você ainda não recebeu avaliações. Peça para seus clientes avaliarem pelo seu perfil público.</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end gap-2">
            <a class="ap-btn ap-btn-outline ap-btn-small" href="<?= base_url('profissionais/' . $profissional->slug) ?>">
                <i class="bi bi-eye"></i> Ver página pública
            </a>
        </div>
    </div>

    <?= $this->include('Templates/partials/flash') ?>

    <!-- ======================================
    ABAS DO PERFIL
    ======================================= -->

    <section class="ap-card ap-tabs-card">

        <ul class="nav nav-tabs ap-tabs" id="abasPerfil" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="aba-dados" data-bs-toggle="tab" data-bs-target="#dados" type="button" role="tab" aria-controls="dados" aria-selected="true">
                    <i class="bi bi-person-lines-fill"></i> Dados e contato
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aba-categorias" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab" aria-controls="categorias" aria-selected="false">
                    <i class="bi bi-tags"></i> Categorias
                    <span class="badge text-bg-light"><?= count($minhasCategorias) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aba-localizacao" data-bs-toggle="tab" data-bs-target="#localizacao" type="button" role="tab" aria-controls="localizacao" aria-selected="false">
                    <i class="bi bi-geo-alt"></i> Minha localização
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aba-atuacao" data-bs-toggle="tab" data-bs-target="#atuacao" type="button" role="tab" aria-controls="atuacao" aria-selected="false">
                    <i class="bi bi-compass"></i> Áreas de atuação
                    <span class="badge text-bg-light"><?= count($locais) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aba-galeria" data-bs-toggle="tab" data-bs-target="#galeria" type="button" role="tab" aria-controls="galeria" aria-selected="false">
                    <i class="bi bi-images"></i> Galeria de trabalhos
                    <span class="badge text-bg-light"><?= count($galeria) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aba-horarios" data-bs-toggle="tab" data-bs-target="#horarios" type="button" role="tab" aria-controls="horarios" aria-selected="false">
                    <i class="bi bi-clock"></i> Horários de atendimento
                    <span class="badge text-bg-light"><?= count($horarios) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="aba-conta" data-bs-toggle="tab" data-bs-target="#conta" type="button" role="tab" aria-controls="conta" aria-selected="false">
                    <i class="bi bi-gear"></i> Conta
                </button>
            </li>
        </ul>

        <div class="tab-content ap-tab-content">
        <div class="tab-pane fade ap-pane show active" id="dados" role="tabpanel" aria-labelledby="aba-dados" tabindex="0">

            <h2>Dados e contato</h2>

            <form method="post" action="<?= base_url('perfil/profissional') ?>" enctype="multipart/form-data" novalidate>

                <?= csrf_field() ?>

                <div class="ap-field">
                    <label>Foto de capa</label>
                    <div class="ap-capa-preview" id="previaCapa" <?= $fotoCapa ? 'style="background-image:url(\'' . esc($fotoCapa, 'attr') . '\')"' : '' ?>></div>
                    <input type="file" class="ap-input" name="foto_capa" accept="image/png,image/jpeg,image/webp" data-preview="#previaCapa">
                    <?php if ($profissional->foto_capa): ?>
                        <label class="ap-check" style="margin-top:6px;"><input type="checkbox" name="remover_foto_capa" value="1"> Remover capa atual</label>
                    <?php endif; ?>
                </div>

                <div class="ap-foto-area">

                    <div class="ap-avatar ap-avatar-sm">
                        <?php if ($fotoPerfil): ?>
                            <img src="<?= esc($fotoPerfil, 'attr') ?>" alt="Sua foto" id="previaFoto">
                        <?php else: ?>
                            <img src="" alt="" id="previaFoto" style="display:none">
                            <span><?= esc(iniciais_nome($profissional->nome)) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="ap-foto-controles">
                        <input type="file" class="ap-input" name="foto_perfil" accept="image/png,image/jpeg,image/webp" data-preview="#previaFoto">
                        <span class="ap-help">Foto de perfil: JPG, PNG ou WEBP até 2MB.</span>
                        <?php if ($usuario->foto_perfil): ?>
                            <label class="ap-check"><input type="checkbox" name="remover_foto_perfil" value="1"> Remover foto atual</label>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="ap-field">
                    <label>Tipo de pessoa</label>
                    <div class="ap-radio-line">
                        <label><input type="radio" name="tipo_pessoa" value="PF" <?= $tipoAtual === 'PF' ? 'checked' : '' ?>> Pessoa Física</label>
                        <label><input type="radio" name="tipo_pessoa" value="PJ" <?= $tipoAtual === 'PJ' ? 'checked' : '' ?>> Pessoa Jurídica</label>
                    </div>
                </div>

                <div class="ap-form-row">

                    <div class="ap-field">
                        <label for="cpf_cnpj" id="rotuloDocumento"><?= $ehPj ? 'CNPJ' : 'CPF' ?></label>

                        <div class="ap-input-botao">
                            <input type="text" class="ap-input" id="cpf_cnpj" name="cpf_cnpj" inputmode="<?= $ehPj ? 'text' : 'numeric' ?>" autocapitalize="characters" autocomplete="off" data-mask="documento" maxlength="18" value="<?= esc(old('cpf_cnpj', formata_documento($profissional->cpf_cnpj)), 'attr') ?>" required>
                            <button type="button" class="ap-btn ap-btn-outline" id="btnVerificarCnpj" data-url="<?= base_url('api/cnpj/validar') ?>" <?= $ehPj ? '' : 'hidden' ?>>
                                <i class="bi bi-shield-check"></i> Verificar
                            </button>
                        </div>

                        <div id="blocoCnpjFonte" <?= $ehPj ? '' : 'hidden' ?>>
                            <label for="cnpjFonte" class="ap-label-pequeno">Buscar os dados em</label>
                            <select class="ap-select" id="cnpjFonte" aria-describedby="ajudaCnpj">
                                <?php foreach (\App\Services\CnpjService::DATASETS as $valor => $rotulo): ?>
                                    <option value="<?= esc($valor, 'attr') ?>"><?= esc($rotulo) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="ap-help" id="ajudaCnpj">Consulta a base pública OpenCNPJ. É opcional e não impede de salvar.</div>
                        </div>
                    </div>

                    <div class="ap-field">
                        <label for="nome">Nome profissional</label>
                        <input type="text" class="ap-input" id="nome" name="nome" maxlength="150" value="<?= esc(old('nome', $profissional->nome), 'attr') ?>" required>
                    </div>

                </div>

                <div id="cnpjResultado" class="ap-cnpj-resultado" role="status" aria-live="polite" hidden></div>

                <div class="ap-field">
                    <label for="descricao">Descrição</label>
                    <textarea class="ap-textarea" id="descricao" name="descricao" maxlength="2000" placeholder="Fale sobre seus serviços, experiência e diferenciais."><?= esc(old('descricao', $profissional->descricao ?? '')) ?></textarea>
                </div>

                <div class="ap-form-row">

                    <div class="ap-field">
                        <label for="whatsapp">WhatsApp</label>
                        <input type="text" class="ap-input" id="whatsapp" name="whatsapp" inputmode="tel" data-mask="phone" maxlength="15" value="<?= esc(old('whatsapp', formata_telefone($profissional->whatsapp)), 'attr') ?>">
                    </div>

                    <div class="ap-field">
                        <label for="telefone_contato">Telefone</label>
                        <input type="text" class="ap-input" id="telefone_contato" name="telefone_contato" inputmode="tel" data-mask="phone" maxlength="15" value="<?= esc(old('telefone_contato', formata_telefone($profissional->telefone_contato)), 'attr') ?>">
                    </div>

                </div>

                <div class="ap-form-row">

                    <div class="ap-field">
                        <label for="instagram">Instagram</label>
                        <input type="text" class="ap-input" id="instagram" name="instagram" maxlength="100" placeholder="seu_usuario" value="<?= esc(old('instagram', $profissional->instagram ?? ''), 'attr') ?>">
                    </div>

                    <div class="ap-field">
                        <label for="site">Site</label>
                        <input type="text" class="ap-input" id="site" name="site" maxlength="255" placeholder="https://seusite.com.br" value="<?= esc(old('site', $profissional->site ?? ''), 'attr') ?>">
                    </div>

                </div>

                <div class="ap-field">
                    <label for="email">E-mail</label>
                    <input type="email" class="ap-input" id="email" value="<?= esc($usuario->email, 'attr') ?>" readonly>
                    <div class="ap-help">O e-mail de acesso não pode ser alterado por aqui.</div>
                </div>

                <button type="submit" class="ap-btn ap-btn-primary">Salvar dados</button>

            </form>

        </div>

        <div class="tab-pane fade ap-pane" id="categorias" role="tabpanel" aria-labelledby="aba-categorias" tabindex="0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="margin:0;">Categorias de atuação</h2>
                <button type="button" class="ap-btn ap-btn-outline ap-btn-small" data-bs-toggle="modal" data-bs-target="#modalCategorias">
                    <i class="bi bi-pencil"></i> Editar
                </button>
            </div>

            <div class="ap-tags">
                <?php foreach ($minhasCategorias as $categoria): ?>
                    <span class="ap-tag"><?= esc($categoria->nome) ?></span>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="tab-pane fade ap-pane" id="localizacao" role="tabpanel" aria-labelledby="aba-localizacao" tabindex="0">

            <h2>Minha localização</h2>

            <?= $this->setVar('action', base_url('perfil/profissional/endereco'))->setVar('endereco', $endereco)->include('Perfil/partials/endereco') ?>

        </div>

        <div class="tab-pane fade ap-pane" id="atuacao" role="tabpanel" aria-labelledby="aba-atuacao" tabindex="0">

            <h2>Áreas de atuação</h2>

            <p>Cidades, bairros ou estados onde você atende. Escolher uma cidade inteira cobre todos os bairros dela.</p>

            <form method="post" action="<?= base_url('perfil/profissional/locais') ?>" novalidate>

                <?= csrf_field() ?>

                <input type="hidden" name="localidade_id" value="" data-localidade-id>

                <div data-cascata="atuacao" data-texto-bairro="Todos os bairros (opcional)">

                    <div class="ap-form-row tres">

                        <div class="ap-field">
                            <label for="atuacaoUf">Estado</label>
                            <select class="ap-select" id="atuacaoUf" data-uf>
                                <option value="">Selecione o estado...</option>
                                <?php foreach ($endereco['ufs'] as $uf): ?>
                                    <option value="<?= $uf->id ?>"><?= esc($uf->nome) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="ap-field">
                            <label for="atuacaoCidade">Cidade</label>
                            <select class="ap-select" id="atuacaoCidade" data-cidade>
                                <option value="">Todas as cidades do estado</option>
                            </select>
                        </div>

                        <div class="ap-field">
                            <label for="atuacaoBairro">Bairro</label>
                            <select class="ap-select" id="atuacaoBairro" data-bairro>
                                <option value="">Todos os bairros (opcional)</option>
                            </select>
                        </div>

                    </div>

                </div>

                <button type="submit" class="ap-btn ap-btn-primary ap-btn-small"><i class="bi bi-plus-lg"></i> Adicionar área</button>

            </form>

            <div class="d-flex justify-content-between mt-4 mb-1">
                <strong style="font-size:13px;">Áreas cadastradas</strong>
                <span class="ap-muted"><?= count($locais) ?> <?= count($locais) === 1 ? 'área' : 'áreas' ?></span>
            </div>

            <?php foreach ($locais as $local): ?>

                <div class="ap-list-item">

                    <div>
                        <?= esc($local->rotulo) ?>
                        <small>· <?= ['UF' => 'Estado', 'MUNICIPIO' => 'Cidade', 'BAIRRO' => 'Bairro'][$local->tipo] ?? '' ?></small>
                    </div>

                    <form method="post" action="<?= base_url('perfil/profissional/locais/' . $local->id . '/remover') ?>" onsubmit="return confirm('Remover esta área de atuação?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="ap-icon-btn" aria-label="Remover <?= esc($local->rotulo, 'attr') ?>"><i class="bi bi-trash"></i></button>
                    </form>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="tab-pane fade ap-pane" id="galeria" role="tabpanel" aria-labelledby="aba-galeria" tabindex="0">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 style="margin:0;">Galeria de trabalhos</h2>
                <span class="ap-muted"><?= count($galeria) ?> de <?= (int) $limiteGaleria ?> fotos</span>
            </div>

            <p>Mostre serviços que você já fez. Cada foto pode ter uma legenda e aparece na sua página pública.</p>

            <?php if (count($galeria) < $limiteGaleria): ?>

                <form method="post" action="<?= base_url('perfil/profissional/galeria') ?>" enctype="multipart/form-data" novalidate>

                    <?= csrf_field() ?>

                    <div class="ap-capa-preview" id="previaGaleria" style="height:130px;"></div>

                    <div class="ap-field">
                        <input type="file" class="ap-input" name="foto" accept="image/png,image/jpeg,image/webp" data-preview="#previaGaleria" required>
                        <div class="ap-help">JPG, PNG ou WEBP até 2MB. Não envie fotos com pessoas que não autorizaram nem com dados pessoais (documentos, placas).</div>
                    </div>

                    <div class="ap-field">
                        <label for="legendaNova">Legenda <span class="ap-muted">(opcional)</span></label>
                        <textarea class="ap-textarea" id="legendaNova" name="descricao" maxlength="300" style="min-height:64px;" placeholder="Ex.: Troca de fiação e quadro de luz em apartamento."></textarea>
                    </div>

                    <button type="submit" class="ap-btn ap-btn-primary ap-btn-small"><i class="bi bi-plus-lg"></i> Adicionar foto</button>

                </form>

            <?php else: ?>

                <div class="alert alert-light border py-2 px-3 font-sm">Você atingiu o limite de fotos. Remova uma para adicionar outra.</div>

            <?php endif; ?>

            <?php foreach ($galeria as $foto): ?>

                <div class="ap-gal-item">

                    <img src="<?= esc(url_foto($foto->arquivo), 'attr') ?>" alt="" loading="lazy">

                    <div class="ap-gal-corpo">

                        <form method="post" action="<?= base_url('perfil/profissional/galeria/' . $foto->id . '/legenda') ?>">
                            <?= csrf_field() ?>
                            <textarea class="ap-textarea" name="descricao" maxlength="300" style="min-height:54px;" placeholder="Sem legenda" aria-label="Legenda da foto"><?= esc($foto->descricao ?? '') ?></textarea>
                            <button type="submit" class="ap-btn ap-btn-outline ap-btn-small" style="margin-top:6px;">Salvar legenda</button>
                        </form>

                        <form method="post" action="<?= base_url('perfil/profissional/galeria/' . $foto->id . '/remover') ?>" onsubmit="return confirm('Remover esta foto da galeria?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="ap-link-danger" style="text-align:left;padding:6px 0 0;margin:0;width:auto;"><i class="bi bi-trash"></i> Remover foto</button>
                        </form>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="tab-pane fade ap-pane" id="horarios" role="tabpanel" aria-labelledby="aba-horarios" tabindex="0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="margin:0;">Horários de atendimento</h2>
                <a href="<?= base_url('profissional/horarios') ?>" class="ap-btn ap-btn-outline ap-btn-small"><i class="bi bi-pencil"></i> Alterar</a>
            </div>

            <?php if (empty($horarios)): ?>
                <p class="ap-muted">Nenhum horário cadastrado.</p>
            <?php else: ?>
                <table class="ap-horarios">
                    <?php foreach ($horarios as $dia => $faixas): ?>
                        <tr>
                            <td><?= esc(dia_semana_rotulo($dia)) ?></td>
                            <td><?= esc(implode(' · ', array_map(static fn ($f) => $f->abre . ' às ' . $f->fecha, $faixas))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <p class="ap-help">Ao alterar, você informa todos os horários novamente.</p>
            <?php endif; ?>

        </div>

        <div class="tab-pane fade ap-pane" id="conta" role="tabpanel" aria-labelledby="aba-conta" tabindex="0">
            <?= $this->setVar('tipoConta', 'PROFISSIONAL')->setVar('parte', 'card')->include('Perfil/partials/excluir_conta') ?>
        </div>
        </div>

    </section>

    <div class="modal fade" id="modalCategorias" tabindex="-1" aria-labelledby="tituloModalCategorias" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:14px;">

                <form method="post" action="<?= base_url('perfil/profissional/categorias') ?>" data-checklist>

                    <?= csrf_field() ?>

                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModalCategorias" style="font-size:16px;">Categorias de atuação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">

                        <input type="search" class="ap-input" placeholder="Buscar categoria..." data-checklist-busca>

                        <div class="ap-muted mt-2"><strong data-checklist-contador>0</strong> selecionada(s)</div>

                        <div class="ap-checklist">
                            <?php foreach ($categorias as $categoria): ?>
                                <label data-checklist-item>
                                    <input type="checkbox" name="categorias[]" value="<?= $categoria->id ?>" <?= in_array((int) $categoria->id, $idsSelecionados, true) ? 'checked' : '' ?>>
                                    <span><?= esc($categoria->nome) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="ap-btn ap-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="ap-btn ap-btn-primary">Salvar categorias</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <?= $this->setVar('tipoConta', 'PROFISSIONAL')->setVar('parte', 'modal')->include('Perfil/partials/excluir_conta') ?>

</main>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/modules/perfil.js') ?>"></script>
<?= $this->endSection() ?>
