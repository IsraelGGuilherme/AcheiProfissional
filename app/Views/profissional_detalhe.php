<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($profissional['nome']) ?> - Achei Profissional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #0876d1; --ink: #263746; --muted: #71808e; --border: #dce6ee; --bg: #f5f8fb; }
        body { margin: 0; background: var(--bg); color: var(--ink); font-family: Arial, sans-serif; font-size: 14px; }
        .navbar-custom { background: #fff; border-bottom: 1px solid var(--border); }
        .navbar-inner { max-width: 1080px; min-height: 68px; margin: auto; padding: 10px 20px; display:flex; align-items:center; justify-content:space-between; gap:20px; }
        .brand { color: var(--primary); font-weight: 700; text-decoration: none; font-size: 17px; }
        .nav-links { display:flex; flex-wrap:wrap; gap:5px; align-items:center; }
        .nav-links a { color:#354454; text-decoration:none; padding:8px 12px; border-radius:8px; font-size:13px; }
        .nav-links a:hover, .nav-links a.active { background:#e4f2ff; color:var(--primary); }
        .nav-links .login { border:1px solid var(--primary); color:var(--primary); font-weight:600; }
        .page { max-width: 850px; margin: 0 auto; padding: 38px 20px 60px; }
        .back { display:inline-flex; gap:7px; align-items:center; color:var(--primary); text-decoration:none; font-size:13px; margin-bottom:20px; }
        .profile-hero, .card { background:#fff; border:1px solid var(--border); border-radius:14px; box-shadow:0 4px 14px rgba(35,70,100,.06); }
        .profile-hero { padding:28px; display:flex; gap:22px; align-items:center; }
        .avatar { width:88px; height:88px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex:0 0 88px; background:#dff0ff; color:var(--primary); font-size:25px; font-weight:700; }
        h1 { font-size:26px; margin:0 0 5px; } .category { color:var(--muted); margin-bottom:10px; }
        .rating { color:#e9a400; font-size:16px; } .rating small { color:var(--muted); font-size:12px; }
        .grid { display:grid; grid-template-columns:1.15fr .85fr; gap:16px; margin-top:16px; }
        .card { padding:22px; } .card h2 { font-size:16px; margin:0 0 15px; }
        .card p { color:#566675; line-height:1.65; margin:0; } .service { display:inline-block; background:#eef7ff; color:#176aa8; border-radius:20px; padding:8px 12px; margin:0 7px 8px 0; font-size:12px; }
        .info { display:flex; gap:10px; align-items:flex-start; color:#566675; margin-bottom:13px; } .info i { color:var(--primary); font-size:17px; }
        .btn-primary-custom { display:block; width:100%; border:0; border-radius:8px; padding:11px 15px; background:var(--primary); color:#fff; text-align:center; text-decoration:none; font-weight:600; }
        .btn-outline-custom { display:block; width:100%; border:1px solid var(--primary); border-radius:8px; padding:10px 15px; background:#fff; color:var(--primary); text-align:center; text-decoration:none; margin-top:10px; }
        @media (max-width: 700px) { .navbar-inner { align-items:flex-start; flex-direction:column; } .nav-links { width:100%; } .profile-hero { align-items:flex-start; flex-direction:column; } .grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    <header class="navbar-custom">
        <div class="navbar-inner">
            <a class="brand" href="<?= site_url('busca') ?>">Achei Profissional</a>
            <nav class="nav-links" aria-label="Navegação principal">
                <a href="<?= site_url('busca') ?>" class="active">Buscar</a>
                <a href="<?= site_url('perfil/contratante') ?>">Sou Contratante</a>
                <a href="<?= site_url('perfil/profissional') ?>">Sou Profissional</a>
                <a href="<?= site_url('admin') ?>">Admin</a>
                <a href="<?= site_url('login') ?>" class="login">Entrar</a>
            </nav>
        </div>
    </header>

    <main class="page">
        <a class="back" href="<?= site_url('busca') ?>"><i class="bi bi-arrow-left"></i> Voltar para a busca</a>
        <section class="profile-hero">
            <div class="avatar"><?= esc($profissional['inicial']) ?></div>
            <div>
                <h1><?= esc($profissional['nome']) ?></h1>
                <div class="category"><?= esc($profissional['profissao']) ?></div>
                <div class="rating">★★★★★ <small><?= esc($profissional['avaliacao']) ?> · <?= esc($profissional['avaliacoes']) ?> avaliações</small></div>
            </div>
        </section>

        <div class="grid">
            <div>
                <section class="card mb-3">
                    <h2>Sobre o profissional</h2>
                    <p><?= esc($profissional['descricao']) ?></p>
                </section>
                <section class="card">
                    <h2>Serviços oferecidos</h2>
                    <?php foreach ($profissional['servicos'] as $servico): ?>
                        <span class="service"><?= esc($servico) ?></span>
                    <?php endforeach; ?>
                </section>
            </div>
            <aside>
                <section class="card mb-3">
                    <h2>Informações</h2>
                    <div class="info"><i class="bi bi-geo-alt"></i><span><?= esc($profissional['cidade']) ?></span></div>
                    <div class="info"><i class="bi bi-house"></i><span><?= esc($profissional['atendimento']) ?></span></div>
                    <div class="info"><i class="bi bi-patch-check"></i><span>Perfil verificado</span></div>
                </section>
                <section class="card">
                    <a class="btn-primary-custom" href="https://wa.me/5532999990000"><i class="bi bi-whatsapp"></i> Entrar em contato</a>
                    <a class="btn-outline-custom" href="<?= site_url('busca') ?>">Ver outros profissionais</a>
                </section>
            </aside>
        </div>
    </main>
</body>
</html>
