<?php $this->extend('Templates/admin') ?>

<?php $this->section('title') ?>Administração - Achei Profissional<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url("assets/css/modules/admin.css") ?>">
<?php $this->endSection() ?>


<?php $this->section('content') ?>
<div class="admin-page">

    <!-- ===== DASHBOARD ===== -->
    <div id="adminDashboard" class="container-admin">

        <div class="page-header">
            <div>
                <h1 class="page-title">Administração</h1>
                <div class="page-description">Gerencie usuários, aprovações, denúncias, categorias e acompanhe os indicadores da plataforma.</div>
            </div>
            <span class="admin-badge"><i class="bi bi-shield-check me-1"></i> Área administrativa</span>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-number">1.284</div>
                <div class="stat-label">Usuários cadastrados</div>
                <div class="stat-change">↑ 12% este mês</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-person-workspace"></i></div>
                <div class="stat-number">128</div>
                <div class="stat-label">Profissionais ativos</div>
                <div class="stat-change">↑ 8 novos esta semana</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-person"></i></div>
                <div class="stat-number">1.156</div>
                <div class="stat-label">Contratantes</div>
                <div class="stat-change">↑ 9% este mês</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-briefcase"></i></div>
                <div class="stat-number">18,4</div>
                <div class="stat-label">Média de vagas por dia</div>
                <div class="stat-change">↑ 6% nos últimos 30 dias</div>
            </div>
        </div>

        <div class="card-custom">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="section-title">Atividade da plataforma</div>
                    <div class="section-subtitle">Média de vagas publicadas por dia nos últimos 7 dias.</div>
                </div>
                <select class="form-select select-periodo">
                    <option>Últimos 7 dias</option>
                    <option>Últimos 30 dias</option>
                    <option>Este ano</option>
                </select>
            </div>
            <div class="chart">
                <div class="bar-wrap">
                    <div class="bar" style="height:42%"><span>12</span></div>
                    <div class="day">07/08</div>
                </div>
                <div class="bar-wrap">
                    <div class="bar" style="height:60%"><span>17</span></div>
                    <div class="day">08/08</div>
                </div>
                <div class="bar-wrap">
                    <div class="bar" style="height:51%"><span>15</span></div>
                    <div class="day">09/08</div>
                </div>
                <div class="bar-wrap">
                    <div class="bar" style="height:76%"><span>22</span></div>
                    <div class="day">10/08</div>
                </div>
                <div class="bar-wrap">
                    <div class="bar" style="height:65%"><span>19</span></div>
                    <div class="day">11/08</div>
                </div>
                <div class="bar-wrap">
                    <div class="bar" style="height:91%"><span>27</span></div>
                    <div class="day">12/08</div>
                </div>
                <div class="bar-wrap">
                    <div class="bar" style="height:72%"><span>21</span></div>
                    <div class="day">13/08</div>
                </div>
            </div>
            <div class="chart-note">Total no período: <strong>133 vagas</strong> · Média: <strong>19 vagas/dia</strong></div>
        </div>

        <section class="card-custom">
            <ul class="nav nav-tabs main-tabs">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profissionais" type="button">
                        <i class="bi bi-person-workspace me-1"></i>Profissionais <span class="badge text-bg-light ms-1"><?= $qtd_profissionais ?></span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contratantes" type="button">
                        <i class="bi bi-person me-1"></i>Contratantes <span class="badge text-bg-light ms-1"><?= $qtd_contratantes ?></span>
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="profissionais">
                    <div class="filter-bar">
                        <input class="form-control filter-search" placeholder="Pesquisar pelo nome...">
                        <div class="status-tabs">
                            <a href="?usu=aprovados" class="status-tab active">Aprovados</a>
                            <a href="?usu=bloqueados" class="status-tab">Bloqueados</a>
                            <a href="?usu=denunciados" class="status-tab reported">Denunciados</a>
                        </div>
                    </div>
                    <div class="user-list">

                        <?php foreach ($users as $user): ?>
                            <?php if ($user->tipo == "PROFISSIONAL") : ?>
                                <div class="user-row">
                                    <div class="user-main">
                                        <div class="avatar"><?= iniciais_nome($user->nome) ?></div>
                                        <div>
                                            <div class="user-name"><?= $user->nome ?><span class="user-status status-approved"><?= $user->status ?></span></div>
                                            <div class="user-meta"><?= estrelas_texto($user->media_notas) ?></div>
                                        </div>
                                    </div>
                                    <div class="row-actions">
                                        <button
                                            class="btn-row"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBloqueio"
                                            data-user-id="<?= $user->id ?>"
                                            data-user-name="<?= esc($user->nome) ?>">
                                            <i class="bi bi-lock me-1"></i>Bloquear
                                        </button>
                                        <button
                                            type="button"
                                            class="btn-row btn-row-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalExclusao"
                                            data-user-id="<?= (int) $user->id ?>">
                                            <i class="bi bi-trash me-1"></i>Excluir
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                </div>

                <div class="tab-pane fade" id="contratantes">
                    <div class="filter-bar">
                        <input class="form-control filter-search" placeholder="Pesquisar pelo nome...">
                        <div class="status-tabs">
                            <button class="status-tab active">Aprovados</button>
                            <button class="status-tab">Bloqueados</button>
                            <button class="status-tab reported">Denunciados <span>3</span></button>
                        </div>
                    </div>
                    <div class="user-list">
                        <?php foreach ($users as $user): ?>
                            <?php if ($user->tipo == "COMUM"): ?>
                                <div class="user-row">
                                    <div class="user-main">
                                        <div class="avatar"><?= iniciais_nome($user->nome) ?></div>
                                        <div>
                                            <div class="user-name"><?= $user->nome ?> <span class="user-status status-reported"><?= $user->status ?></span></div>
                                            <div class="user-meta">Muriaé/MG · <?= $user->qtd_denucias ?> denúncia</div>
                                        </div>
                                    </div>
                                    <div class="row-actions">
                                        <button
                                            type="button"
                                            class="btn-row"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBloqueio"
                                            data-user-id="<?= (int) $user->id ?>"
                                            data-user-name="<?= esc($user->nome) ?>">
                                            <i class="bi bi-lock me-1"></i>Bloquear
                                        </button>
                                        <button
                                            type="button"
                                            class="btn-row btn-row-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalExclusao"
                                            data-user-id="<?= (int) $user->id ?>">
                                            <i class="bi bi-trash me-1"></i>Excluir
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </section>

        <section class="card-custom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <div class="section-title">Categorias do sistema</div>
                    <div class="section-subtitle">O gerenciamento de categoria fica em uma tela exclusiva, separada dos usuários da plataforma.</div>
                </div>
                <a href="#" data-admin-view="admins" class="btn-admin text-decoration-none">
                    <i class="bi bi-tags me-1"></i>Gerenciar categorias
                </a>
            </div>
        </section>

        <section class="card-custom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <div class="section-title">Administradores do sistema</div>
                    <div class="section-subtitle">O gerenciamento de administradores fica em uma tela exclusiva, separada dos usuários da plataforma.</div>
                </div>
                <a href="<?= site_url('admin/administradores') ?>" data-admin-view="admins" class="btn-admin text-decoration-none">
                    <i class="bi bi-shield-lock me-1"></i>Gerenciar administradores
                </a>
            </div>
        </section>
    </div>
</div>

<!-- ===== MODAL: BLOQUEIO TEMPORÁRIO ===== -->
<div class="modal fade modal-admin" id="modalBloqueio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fs-6"><i class="bi bi-lock me-1"></i> Bloquear usuário</h5>
                    <div class="section-subtitle">O bloqueio é temporário e não remove o cadastro.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="" method="post" id="form_bloqueio" data-action-base="<?= site_url('admin/bloqueio') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">

                    <label class="form-label">Período do bloqueio</label>
                    <select class="form-select mb-3" name="tempo_bloqueio" id="tempo_bloqueio">
                        <option value="1H">1 hora</option>
                        <option value="24H">24 horas</option>
                        <option value="3D">3 dias</option>
                        <option value="7D">7 dias</option>
                        <option value="15D">15 dias</option>
                        <option value="1M">30 dias</option>
                        <option value="PERSONALIZADO">Até uma data específica</option>
                    </select>

                    <input type="hidden" name="user_id" id="bloqueioUserId">

                    <label class="form-label">Data de desbloqueio</label>
                    <input type="datetime-local" class="form-control mb-3" name="data_personalizada">

                    <label class="form-label">Motivo</label>
                    <textarea class="form-control" rows="3" placeholder="Informe o motivo do bloqueio..." name="motivo"></textarea>

                    <div class="input-help">Enquanto estiver bloqueado, o usuário não poderá utilizar as funcionalidades que exigem acesso à plataforma.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-admin"><i class="bi bi-lock me-1"></i> Confirmar bloqueio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL: CONFIRMAÇÃO DE EXCLUSÃO DO USUÁRIO ===== -->
<div class="modal fade modal-admin" id="modalExclusao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fs-6"><i class="bi bi-lock me-1"></i> Remover usuário</h5>
                    <div class="section-subtitle">Não será possível recuperar esse registro novamente depois de apagado.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="" method="post" id="form_exclusao" data-action-base="<?= site_url('admin/excluir') ?>">
                <?= csrf_field() ?>
                <div class="modal-footer">
                    <button type="button" class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-admin"><i class="bi bi-trash me-1"></i> Confirmar exclusão</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('assets/js/modules/admin.js') ?>"></script>
<?php $this->endSection() ?>