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
                                        <button class="btn-row btn-row-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
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
                                        <button class="btn-row" data-bs-toggle="modal" data-bs-target="#modalBloqueio"><i class="bi bi-lock me-1"></i>Bloquear</button>
                                        <button class="btn-row btn-row-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </section>

        <section class="card-custom">
            <div class="mb-3">
                <div class="section-title">Categorias</div>
                <div class="section-subtitle">Visualize as categorias cadastradas e adicione novas categorias à plataforma.</div>
            </div>
            <div class="category-management">
                <div class="category-create">
                    <label class="form-label" for="novaCategoria">Nova categoria</label>
                    <div class="input-group">
                        <input type="text" id="novaCategoria" class="form-control" placeholder="Ex.: Técnico de refrigeração">
                        <button class="btn-admin" type="button" id="btnCriarCategoria"><i class="bi bi-plus-lg me-1"></i>Criar</button>
                    </div>
                    <div class="input-help">A nova categoria ficará disponível para profissionais selecionarem em seus perfis.</div>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold" style="font-size:9px">Categorias cadastradas</span>
                        <span class="section-subtitle m-0">20 categorias</span>
                    </div>
                    <div class="category-chips" id="categoryChips">
                        <span class="category-chip">Eletricista <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Encanador <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Pedreiro <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Pintor <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Marceneiro <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Costureira <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Diarista <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Jardineiro <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Técnico em informática <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Chaveiro <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Cuidador de idosos <button type="button"><i class="bi bi-x"></i></button></span>
                        <span class="category-chip">Professor particular <button type="button"><i class="bi bi-x"></i></button></span>
                    </div>
                </div>
            </div>
        </section>

        <section class="card-custom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <div class="section-title">Administradores do sistema</div>
                    <div class="section-subtitle">O gerenciamento de administradores fica em uma tela exclusiva, separada dos usuários da plataforma.</div>
                </div>
                <a href="#" data-admin-view="admins" class="btn-admin text-decoration-none">
                    <i class="bi bi-shield-lock me-1"></i>Gerenciar administradores
                </a>
            </div>
        </section>
    </div>

    <!-- ===== TELA DE ADMINISTRADORES ===== -->
    <div id="telaAdministradores" class="container-admin d-none">

        <div class="page-header">
            <div>
                <h1 class="page-title">Administradores</h1>
                <div class="page-description">Gerencie exclusivamente as contas que possuem acesso administrativo.</div>
            </div>
            <div class="d-flex gap-2">
                <a href="#" data-admin-view="dashboard" class="btn-row text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
                <button class="btn-admin" data-bs-toggle="modal" data-bs-target="#modalAdmin">
                    <i class="bi bi-person-plus me-1"></i>Novo administrador
                </button>
            </div>
        </div>

        <div class="card-custom">
            <div class="filter-bar">
                <input class="form-control filter-search" placeholder="Pesquisar administrador pelo nome ou e-mail...">
                <span class="admin-badge"><i class="bi bi-shield-check me-1"></i>3 administradores</span>
            </div>
            <div class="user-list">
                <div class="admin-row">
                    <div class="user-main">
                        <div class="avatar"><i class="bi bi-shield-lock"></i></div>
                        <div>
                            <div class="admin-name">Administrador Principal</div>
                            <div class="admin-email">admin@acheiprofissional.com.br</div>
                        </div>
                    </div>
                    <div class="row-actions">
                        <span class="admin-tag">Administrador</span>
                        <button class="btn-row">Editar</button>
                    </div>
                </div>

                <div class="admin-row">
                    <div class="user-main">
                        <div class="avatar">MS</div>
                        <div>
                            <div class="admin-name">Marcos Silva</div>
                            <div class="admin-email">marcos@acheiprofissional.com.br</div>
                        </div>
                    </div>
                    <div class="row-actions">
                        <span class="admin-tag">Administrador</span>
                        <button class="btn-row">Editar</button>
                        <button class="btn-row btn-row-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                    </div>
                </div>

                <div class="admin-row">
                    <div class="user-main">
                        <div class="avatar">JC</div>
                        <div>
                            <div class="admin-name">Juliana Costa</div>
                            <div class="admin-email">juliana@acheiprofissional.com.br</div>
                        </div>
                    </div>
                    <div class="row-actions">
                        <span class="admin-tag">Administrador</span>
                        <button class="btn-row">Editar</button>
                        <button class="btn-row btn-row-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                    </div>
                </div>
            </div>
        </div>
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
            <form action="" method="post" id="form_bloqueio">
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
                    <button class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                    <button submit class="btn-admin" data-bs-dismiss="modal"><i class="bi bi-lock me-1"></i> Confirmar bloqueio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL: NOVO ADMINISTRADOR ===== -->
<div class="modal fade modal-admin" id="modalAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-6">Cadastrar administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Nome</label>
                <input id="adminNome" class="form-control mb-3" placeholder="Nome do administrador">
                <label class="form-label">E-mail</label>
                <input type="email" class="form-control mb-3" placeholder="admin@exemplo.com">
                <label class="form-label">Senha</label>
                <input type="password" class="form-control" placeholder="Digite a senha">
                <div class="input-help">Esse cadastro cria um usuário com permissão exclusivamente administrativa.</div>
            </div>
            <div class="modal-footer">
                <button class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn-admin" data-bs-dismiss="modal">Cadastrar administrador</button>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('assets/js/modules/admin.js') ?>"></script>
<?php $this->endSection() ?>