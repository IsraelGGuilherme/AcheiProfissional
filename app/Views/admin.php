<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administração - Achei Profissional</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
:root{
    --primary:#0876d1;
    --primary-light:#e7f3ff;
    --bg:#f4f9fd;
    --text:#172b3f;
    --muted:#718096;
    --border:#d8e1e8;
    --danger:#dc3545;
}
*{box-sizing:border-box}
body{
    margin:0;
    background:var(--bg);
    color:var(--text);
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;
}
        /* ========================================
           NAVBAR PADRAO
        ======================================== */

        .top-border {
            height: 4px;
            background: #202020;
        }

        .navbar-custom {
            background: #fff;
            border-bottom: 1px solid #dce4eb;
        }

        .navbar-inner-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            min-height: 68px;
            padding: 10px 0;
        }

        .brand {
            color: var(--primary);
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
        }

        .navbar-toggler-custom {
            display: none;
            width: 38px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid #d5dfe7;
            border-radius: 8px;
            background: #fff;
            color: var(--primary);
            font-size: 18px;
            padding: 0;
            line-height: 1;
            cursor: pointer;
        }

        .navbar-toggler-custom:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(8, 118, 209, .15);
        }

        .nav-collapse {
            flex-basis: 100%;
        }

        .nav-links-wrap {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .nav-link-custom {
            color: #354454;
            font-size: 13px;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            transition: .2s;
            white-space: nowrap;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            background: #e4f2ff;
            color: var(--primary);
        }

        .nav-link-login {
            border: 1px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
            margin-left: 4px;
        }

        .nav-link-login:hover,
        .nav-link-login.active {
            background: var(--primary);
            color: #fff;
        }

        @media (max-width: 767.98px) {

            .navbar-inner-wrap {
                min-height: 58px;
                padding: 8px 0;
            }

            .navbar-toggler-custom {
                display: flex;
            }

            .nav-collapse {
                width: 100%;
            }

            .nav-links-wrap {
                flex-direction: column;
                align-items: stretch;
                gap: 4px;
                padding: 10px 0 14px;
                margin-top: 4px;
                border-top: 1px solid #e6edf2;
            }

            .nav-link-custom {
                padding: 10px 12px;
            }

            .nav-link-login {
                margin-left: 0;
                margin-top: 2px;
                text-align: center;
            }

        }

        @media (min-width: 768px) {

            .nav-collapse {
                display: flex !important;
                height: auto !important;
                flex-basis: auto;
            }

        }

.page{padding:27px 15px 50px}
.container-admin{max-width:950px;margin:auto}
.page-header{
    display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:19px
}
.page-title{font-size:21px;font-weight:700;margin:0}
.page-description{font-size:10px;color:var(--muted);margin-top:2px}
.admin-badge{
    border:1px solid #cbdce9;background:#fff;color:#607282;border-radius:12px;
    padding:4px 9px;font-size:8px
}

/* Dashboard */
.stats-grid{
    display:grid;grid-template-columns:repeat(4,1fr);gap:11px;margin-bottom:14px
}
.stat-card{
    background:#fff;border:1px solid var(--border);border-radius:12px;padding:13px;
    box-shadow:0 3px 10px rgba(35,70,100,.04)
}
.stat-icon{
    width:29px;height:29px;border-radius:8px;background:var(--primary-light);
    color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:13px;
    margin-bottom:8px
}
.stat-number{font-size:22px;font-weight:700;line-height:1}
.stat-label{font-size:8px;color:var(--muted);margin-top:4px}
.stat-change{font-size:7px;color:#2c9b6b;margin-top:7px}

/* Cards */
.card-custom{
    background:#fff;border:1px solid var(--border);border-radius:13px;
    padding:15px;margin-bottom:14px;box-shadow:0 3px 10px rgba(35,70,100,.05)
}
.section-title{font-size:12px;font-weight:700;margin:0}
.section-subtitle{font-size:8px;color:var(--muted);margin-top:2px}

/* Main tabs */
.main-tabs{
    border-bottom:1px solid #dbe4eb;margin:0 -15px 14px;padding:0 15px
}
.main-tabs .nav-link{
    border:0;border-radius:7px 7px 0 0;color:#627384;font-size:10px;padding:9px 13px
}
.main-tabs .nav-link.active{
    color:var(--primary);background:var(--primary-light);font-weight:600
}

/* Filter */
.filter-bar{
    display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin:13px 0
}
.filter-bar .form-control,.filter-bar .form-select{
    height:32px;font-size:9px;border:1px solid #ccd9e3;border-radius:7px;box-shadow:none
}
.filter-search{flex:1;min-width:190px}
.status-tabs{
    display:flex;gap:5px
}
.status-tab{
    border:1px solid #d3dfe7;background:#fff;color:#657584;border-radius:15px;
    padding:5px 9px;font-size:8px
}
.status-tab.active{background:var(--primary-light);border-color:#b8daf3;color:var(--primary)}
.status-tab.reported{color:#b42332}
.status-tab.reported.active{background:#fff0f1;border-color:#f1c1c6}

/* User rows */
.user-list{border-top:1px solid #e1e8ed}
.user-row{
    display:flex;align-items:center;justify-content:space-between;gap:12px;
    padding:11px 2px;border-bottom:1px solid #e1e8ed
}
.user-main{display:flex;align-items:center;gap:10px;min-width:0}
.avatar{
    width:36px;height:36px;flex:0 0 36px;border-radius:9px;background:#dff0ff;
    color:#1975b9;display:flex;align-items:center;justify-content:center;
    font-size:10px;font-weight:700
}
.user-name{font-size:10px;font-weight:600}
.user-meta{font-size:8px;color:var(--muted);margin-top:2px}
.user-status{
    display:inline-block;padding:2px 6px;border-radius:8px;font-size:7px;margin-left:5px
}
.status-approved{background:#e9f7f0;color:#278e68}
.status-reported{background:#fff0f1;color:#bd3545}
.status-inactive{background:#eef1f3;color:#7b8790}
.status-blocked{background:#fff4df;color:#a96800}
.status-pending{background:#f0edff;color:#6651a8}
.row-actions{display:flex;gap:5px;flex-shrink:0}
.btn-row{
    border:1px solid #d4dfe7;background:#fff;color:#526575;border-radius:6px;
    padding:5px 8px;font-size:8px
}
.btn-row:hover{background:#f5f9fc}
.btn-danger{color:#c63745}
.btn-primary{
    border:0;background:var(--primary);color:#fff;border-radius:7px;padding:7px 11px;font-size:9px
}

/* Categories */
.category-management{
    display:grid;grid-template-columns:1fr 1fr;gap:15px
}
.category-create{
    border:1px solid #dbe4eb;border-radius:9px;padding:12px;background:#fafcfe
}
.category-create .form-control{
    height:34px;font-size:9px;border:1px solid #ccd9e3;border-radius:7px
}
.category-chips{
    display:flex;flex-wrap:wrap;gap:6px;margin-top:10px
}
.category-chip{
    display:inline-flex;align-items:center;gap:4px;padding:5px 8px;border-radius:13px;
    background:#eaf5ff;border:1px solid #b7d9f4;color:#0876d1;font-size:8px
}
.category-chip button{
    border:0;background:transparent;color:#0876d1;padding:0;font-size:9px
}

/* Admin users */
.admin-row{
    display:flex;justify-content:space-between;align-items:center;padding:10px 0;
    border-bottom:1px solid #e3e9ee
}
.admin-row:last-child{border-bottom:0}
.admin-name{font-size:9px;font-weight:600}
.admin-email{font-size:8px;color:var(--muted)}
.admin-tag{
    padding:3px 6px;border-radius:8px;background:#e7f3ff;color:#0876d1;font-size:7px
}

/* chart */
.chart{
    height:135px;display:flex;align-items:flex-end;gap:13px;padding:15px 5px 22px;
    border-bottom:1px solid #dfe7ec
}
.bar-wrap{height:100%;flex:1;display:flex;align-items:flex-end;justify-content:center;position:relative}
.bar{
    width:22px;max-width:70%;background:#b9d9ef;border-radius:5px 5px 0 0;position:relative
}
.bar span{
    position:absolute;top:-14px;left:50%;transform:translateX(-50%);font-size:7px;color:#607282
}
.day{
    position:absolute;bottom:-17px;font-size:7px;color:#8997a5
}
.chart-note{font-size:8px;color:var(--muted);margin-top:9px}

/* Responsive */
@media(max-width:800px){
    .stats-grid{grid-template-columns:repeat(2,1fr)}
    .category-management{grid-template-columns:1fr}
}
@media(max-width:600px){
    .page-header{flex-direction:column;gap:8px}
    .user-row{align-items:flex-start}
    .row-actions{flex-direction:column}
    .main-tabs .nav-link{padding:8px}
}
@media(max-width:420px){
    .stats-grid{grid-template-columns:1fr}
    .filter-search{min-width:0;width:100%}
    .status-tabs{width:100%}
    .admin-row{flex-direction:column;align-items:flex-start;gap:8px}
    .row-actions{width:100%}
}
</style>

    <!-- Ajuste para telas maiores: amplia o conteúdo proporcionalmente em monitores de computador -->
    <style>
        @media (min-width: 992px) and (max-width: 1199.98px) {
            body { zoom: 1.15; }
        }
        @media (min-width: 1200px) and (max-width: 1599.98px) {
            body { zoom: 1.3; }
        }
        @media (min-width: 1600px) {
            body { zoom: 1.45; }
        }
    </style>
</head>


<body>

<div class="top-border"></div>

<header class="navbar-custom">
    <div class="container navbar-inner-wrap">

        <a href="<?= site_url('busca') ?>" class="brand">Achei Profissional</a>

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
                <a href="<?= site_url('busca') ?>" class="nav-link-custom">Buscar</a>
                <a href="<?= site_url('perfil/contratante') ?>" class="nav-link-custom">Sou Contratante</a>
                <a href="<?= site_url('perfil/profissional') ?>" class="nav-link-custom">Sou Profissional</a>
                <a href="<?= site_url('admin') ?>" class="nav-link-custom active">Admin</a>
                <a href="<?= site_url('login') ?>" class="nav-link-custom nav-link-login">Entrar</a>
            </div>
        </nav>

    </div>
</header>

<main class="page">
<div class="container-admin">

    <div class="page-header">
        <div>
            <h1 class="page-title">Administração</h1>
            <div class="page-description">
                Gerencie usuários, aprovações, denúncias, categorias e acompanhe os indicadores da plataforma.
            </div>
        </div>
        <span class="admin-badge">
            <i class="bi bi-shield-check me-1"></i> Área administrativa
        </span>
    </div>

    <!-- DASHBOARD -->
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

    <!-- SEGUNDO BLOCO DE INDICADORES -->
    <div class="card-custom">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <div class="section-title">Atividade da plataforma</div>
                <div class="section-subtitle">Média de vagas publicadas por dia nos últimos 7 dias.</div>
            </div>

            <select class="form-select" style="width:105px;height:29px;font-size:8px">
                <option>Últimos 7 dias</option>
                <option>Últimos 30 dias</option>
                <option>Este ano</option>
            </select>
        </div>

        <div class="chart">
            <div class="bar-wrap"><div class="bar" style="height:42%"><span>12</span></div><div class="day">07/08</div></div>
            <div class="bar-wrap"><div class="bar" style="height:60%"><span>17</span></div><div class="day">08/08</div></div>
            <div class="bar-wrap"><div class="bar" style="height:51%"><span>15</span></div><div class="day">09/08</div></div>
            <div class="bar-wrap"><div class="bar" style="height:76%"><span>22</span></div><div class="day">10/08</div></div>
            <div class="bar-wrap"><div class="bar" style="height:65%"><span>19</span></div><div class="day">11/08</div></div>
            <div class="bar-wrap"><div class="bar" style="height:91%"><span>27</span></div><div class="day">12/08</div></div>
            <div class="bar-wrap"><div class="bar" style="height:72%"><span>21</span></div><div class="day">13/08</div></div>
        </div>

        <div class="chart-note">
            Total no período: <strong>133 vagas</strong> · Média: <strong>19 vagas/dia</strong>
        </div>

    </div>

    <!-- USUÁRIOS -->
    <section class="card-custom">

        <ul class="nav nav-tabs main-tabs" id="usersTabs">

            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profissionais">
                    <i class="bi bi-person-workspace me-1"></i>
                    Profissionais
                    <span class="badge text-bg-light ms-1">128</span>
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contratantes">
                    <i class="bi bi-person me-1"></i>
                    Contratantes
                    <span class="badge text-bg-light ms-1">1.156</span>
                </button>
            </li>

        </ul>

        <div class="tab-content">

            <!-- PROFISSIONAIS -->
            <div class="tab-pane fade show active" id="profissionais">

                <div class="filter-bar">

                    <input
                        class="form-control filter-search"
                        placeholder="Pesquisar pelo nome..."
                    >

                    <div class="status-tabs">
                        <button class="status-tab active">Aprovados</button>
                        <button class="status-tab">Inativos</button>
                        <button class="status-tab reported">
                            Denunciados <span>7</span>
                        </button>
                    </div>

                </div>

                <div class="user-list">

                    <div class="user-row">
                        <div class="user-main">
                            <div class="avatar">JE</div>
                            <div>
                                <div class="user-name">
                                    João Eletricista
                                    <span class="user-status status-approved">Aprovado</span>
                                </div>
                                <div class="user-meta">Eletricista · Muriaé/MG</div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button class="btn-row">Inativar</button>
                            <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                        </div>
                    </div>

                    <div class="user-row">
                        <div class="user-main">
                            <div class="avatar">MI</div>
                            <div>
                                <div class="user-name">
                                    Maria Instalações
                                    <span class="user-status status-approved">Aprovado</span>
                                </div>
                                <div class="user-meta">Eletricista · Barra/MG</div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button class="btn-row">Inativar</button>
                            <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                        </div>
                    </div>

                    <div class="user-row">
                        <div class="user-main">
                            <div class="avatar">CE</div>
                            <div>
                                <div class="user-name">
                                    Carlos Encanador
                                    <span class="user-status status-approved">Aprovado</span>
                                </div>
                                <div class="user-meta">Encanador · Safira/MG</div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button class="btn-row">Inativar</button>
                            <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                        </div>
                    </div>

                    <div class="user-row">
                        <div class="user-main">
                            <div class="avatar">SA</div>
                            <div>
                                <div class="user-name">
                                    Sandra Costureira
                                    <span class="user-status status-reported">Denunciado</span>
                                </div>
                                <div class="user-meta">Costureira · Muriaé/MG · 2 denúncias</div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button class="btn-row">Inativar</button>
                            <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- CONTRATANTES -->
            <div class="tab-pane fade" id="contratantes">

                <div class="filter-bar">

                    <input
                        class="form-control filter-search"
                        placeholder="Pesquisar pelo nome..."
                    >

                    <div class="status-tabs">
                        <button class="status-tab active">Aprovados</button>
                        <button class="status-tab">Inativos</button>
                        <button class="status-tab reported">
                            Denunciados <span>3</span>
                        </button>
                    </div>

                </div>

                <div class="user-list">

                    <div class="user-row">
                        <div class="user-main">
                            <div class="avatar">AM</div>
                            <div>
                                <div class="user-name">
                                    Ana Martins
                                    <span class="user-status status-approved">Aprovado</span>
                                </div>
                                <div class="user-meta">Muriaé/MG</div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button class="btn-row">Inativar</button>
                            <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                        </div>
                    </div>

                    <div class="user-row">
                        <div class="user-main">
                            <div class="avatar">RC</div>
                            <div>
                                <div class="user-name">
                                    Roberto Carvalho
                                    <span class="user-status status-approved">Aprovado</span>
                                </div>
                                <div class="user-meta">Miradouro/MG</div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button class="btn-row">Inativar</button>
                            <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                        </div>
                    </div>

                    <div class="user-row">
                        <div class="user-main">
                            <div class="avatar">LF</div>
                            <div>
                                <div class="user-name">
                                    Lucas Ferreira
                                    <span class="user-status status-reported">Denunciado</span>
                                </div>
                                <div class="user-meta">Muriaé/MG · 1 denúncia</div>
                            </div>
                        </div>
                        <div class="row-actions">
                            <button class="btn-row">Inativar</button>
                            <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- CATEGORIAS -->
    <section class="card-custom">

        <div class="mb-3">
            <div class="section-title">Categorias</div>
            <div class="section-subtitle">
                Visualize as categorias cadastradas e adicione novas categorias à plataforma.
            </div>
        </div>

        <div class="category-management">

            <div class="category-create">

                <label class="form-label">Nova categoria</label>

                <div class="input-group">
                    <input
                        type="text"
                        id="novaCategoria"
                        class="form-control"
                        placeholder="Ex.: Técnico de refrigeração"
                    >

                    <button
                        class="btn-primary"
                        type="button"
                        id="btnCriarCategoria"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Criar
                    </button>
                </div>

                <div class="input-help">
                    A nova categoria ficará disponível para profissionais selecionarem em seus perfis.
                </div>

            </div>

            <div>

                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:9px;font-weight:600">
                        Categorias cadastradas
                    </span>

                    <span style="font-size:8px;color:#718096">
                        20 categorias
                    </span>
                </div>

                <div class="category-chips" id="categoryChips">

                    <span class="category-chip">Eletricista <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Encanador <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Pedreiro <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Pintor <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Marceneiro <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Costureira <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Diarista <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Jardineiro <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Técnico em informática <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Chaveiro <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Cuidador de idosos <button><i class="bi bi-x"></i></button></span>
                    <span class="category-chip">Professor particular <button><i class="bi bi-x"></i></button></span>

                </div>

            </div>

        </div>

    </section>


    <!-- ACESSO A ADMINISTRADORES -->
    <section class="card-custom" id="administradores">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="section-title">Administradores do sistema</div>
                <div class="section-subtitle">
                    O gerenciamento de administradores fica em uma tela exclusiva, separada dos usuários da plataforma.
                </div>
            </div>
            <a href="#telaAdministradores" class="btn-primary text-decoration-none">
                <i class="bi bi-shield-lock me-1"></i>
                Gerenciar administradores
            </a>
        </div>
    </section>

</div>
</main>


<!-- TELA SEPARADA DE ADMINISTRADORES -->
<section id="telaAdministradores" class="page" style="display:none">
<div class="container-admin">

    <div class="page-header">
        <div>
            <h1 class="page-title">Administradores</h1>
            <div class="page-description">
                Gerencie exclusivamente as contas que possuem acesso administrativo.
            </div>
        </div>
        <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdmin">
            <i class="bi bi-person-plus me-1"></i>
            Novo administrador
        </button>
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
                    <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
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
                    <button class="btn-row btn-danger"><i class="bi bi-trash me-1"></i>Excluir</button>
                </div>
            </div>

        </div>
    </div>

</div>
</section>


<!-- MODAL DE BLOQUEIO TEMPORÁRIO -->
<div class="modal fade" id="modalBloqueio" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content" style="border-radius:13px;border:1px solid #d8e1e8">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" style="font-size:13px">
                        <i class="bi bi-lock me-1"></i> Bloquear usuário
                    </h5>
                    <div style="font-size:8px;color:#718096">
                        O bloqueio é temporário e não remove o cadastro.
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label class="form-label">Período do bloqueio</label>
                <select class="form-select mb-3" style="height:34px;font-size:9px">
                    <option>1 hora</option>
                    <option>24 horas</option>
                    <option>3 dias</option>
                    <option>7 dias</option>
                    <option>15 dias</option>
                    <option>30 dias</option>
                    <option>Até uma data específica</option>
                </select>

                <label class="form-label">Data de desbloqueio</label>
                <input type="datetime-local" class="form-control mb-3" style="height:34px;font-size:9px">

                <label class="form-label">Motivo</label>
                <textarea class="form-control" rows="3" style="font-size:9px"
                    placeholder="Informe o motivo do bloqueio..."></textarea>

                <div class="input-help mt-2">
                    Enquanto estiver bloqueado, o usuário não poderá utilizar as funcionalidades que exigem acesso à plataforma.
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn-primary" data-bs-dismiss="modal">
                    <i class="bi bi-lock me-1"></i> Confirmar bloqueio
                </button>
            </div>

        </div>
    </div>
</div>

<!-- MODAL NOVO ADMIN -->
<div class="modal fade" id="modalAdmin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content" style="border-radius:13px;border:1px solid #d8e1e8">

            <div class="modal-header">
                <h5 class="modal-title" style="font-size:13px">Cadastrar administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label class="form-label">Nome</label>
                <input id="adminNome" class="form-control mb-3" style="height:34px;font-size:9px" placeholder="Nome do administrador">

                <label class="form-label">E-mail</label>
                <input type="email" class="form-control mb-3" style="height:34px;font-size:9px" placeholder="admin@exemplo.com">

                <label class="form-label">Senha</label>
                <input type="password" class="form-control" style="height:34px;font-size:9px" placeholder="Digite a senha">

                <div class="input-help mt-2">
                    Esse cadastro cria um usuário com permissão exclusivamente administrativa.
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn-primary" data-bs-dismiss="modal">Cadastrar administrador</button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

/* Alternância entre dashboard e tela exclusiva de administradores */
document.querySelectorAll('a[href="#telaAdministradores"], a[href="#administradores"]').forEach(link => {
    link.addEventListener('click', function(e){
        e.preventDefault();

        const dashboard = document.querySelector('main.page');
        const adminScreen = document.getElementById('telaAdministradores');

        if (this.getAttribute('href') === '#telaAdministradores') {
            dashboard.style.display = 'none';
            adminScreen.style.display = 'block';
            window.scrollTo({top:0, behavior:'smooth'});
        } else {
            adminScreen.style.display = 'none';
            dashboard.style.display = 'block';
        }
    });
});

/* Criar categoria visualmente */
document.getElementById('btnCriarCategoria').addEventListener('click', function(){

    const input = document.getElementById('novaCategoria');
    const nome = input.value.trim();

    if(!nome) return;

    const chip = document.createElement('span');
    chip.className = 'category-chip';

    chip.innerHTML = `
        ${nome}
        <button type="button">
            <i class="bi bi-x"></i>
        </button>
    `;

    chip.querySelector('button').addEventListener('click', function(){
        chip.remove();
    });

    document.getElementById('categoryChips').appendChild(chip);
    input.value = '';

});

/* Permite criar com Enter */
document.getElementById('novaCategoria').addEventListener('keydown', function(e){
    if(e.key === 'Enter'){
        e.preventDefault();
        document.getElementById('btnCriarCategoria').click();
    }
});
</script>

</body>
</html>
