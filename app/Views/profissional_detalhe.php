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
        .review-card { margin-top:16px; } .stars-select { display:flex; flex-direction:row-reverse; justify-content:flex-end; gap:3px; margin-bottom:12px; }
        .stars-select input { position:absolute; opacity:0; } .stars-select label { cursor:pointer; color:#c8d1d8; font-size:29px; line-height:1; transition:.15s; }
        .stars-select label:hover, .stars-select label:hover ~ label, .stars-select input:checked ~ label { color:#f2b01e; }
        .review-input { width:100%; box-sizing:border-box; border:1px solid var(--border); border-radius:8px; padding:11px; font:inherit; color:var(--ink); resize:vertical; min-height:78px; margin-bottom:10px; }
        .review-input:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(8,118,209,.1); }
        .review-feedback { display:none; padding:10px 12px; border-radius:8px; margin-top:12px; background:#eafaf3; color:#18794e; font-size:13px; }
        .btn-report { display:block; width:100%; border:0; background:transparent; color:#b34b4b; text-align:center; padding:10px 15px; margin-top:8px; font-size:12px; cursor:pointer; }
        .btn-report:hover { color:#8f2929; text-decoration:underline; }
        .modal-backdrop-custom { display:none; position:fixed; inset:0; z-index:10; align-items:center; justify-content:center; padding:20px; background:rgba(22,39,54,.55); }
        .modal-backdrop-custom.open { display:flex; } .modal-box { width:min(520px,100%); background:#fff; border-radius:14px; padding:25px; box-shadow:0 15px 45px rgba(0,0,0,.22); }
        .modal-head { display:flex; align-items:flex-start; justify-content:space-between; gap:15px; margin-bottom:8px; } .modal-head h2 { margin:0; font-size:19px; }
        .modal-close { border:0; background:transparent; color:#71808e; font-size:23px; cursor:pointer; line-height:1; } .report-label { display:block; color:#354454; font-size:13px; font-weight:600; margin:17px 0 7px; }
        .report-select, .report-textarea, .report-file { width:100%; box-sizing:border-box; border:1px solid #ccd9e3; border-radius:8px; padding:11px; background:#fff; font:inherit; color:var(--ink); }
        .report-textarea { resize:vertical; min-height:90px; } .modal-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:19px; }
        .modal-actions button { border-radius:8px; padding:10px 15px; cursor:pointer; } .cancel-report { border:1px solid var(--border); background:#fff; color:#566675; } .submit-report { border:0; background:#b34b4b; color:#fff; }
        .report-feedback { display:none; margin-top:14px; padding:10px; border-radius:8px; background:#eafaf3; color:#18794e; font-size:13px; }
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
                    <button type="button" class="btn-report" id="openReport"><i class="bi bi-flag"></i> Denunciar perfil</button>
                </section>
            </aside>
        </div>

        <section class="card review-card">
            <h2>Avalie este profissional</h2>
            <p style="margin-bottom:14px;">Conte como foi sua experiência para ajudar outras pessoas.</p>
            <form id="reviewForm">
                <div class="stars-select" aria-label="Escolha uma nota de 1 a 5 estrelas">
                    <input type="radio" id="star5" name="nota" value="5" required><label for="star5" title="5 estrelas">★</label>
                    <input type="radio" id="star4" name="nota" value="4"><label for="star4" title="4 estrelas">★</label>
                    <input type="radio" id="star3" name="nota" value="3"><label for="star3" title="3 estrelas">★</label>
                    <input type="radio" id="star2" name="nota" value="2"><label for="star2" title="2 estrelas">★</label>
                    <input type="radio" id="star1" name="nota" value="1"><label for="star1" title="1 estrela">★</label>
                </div>
                <textarea class="review-input" name="comentario" placeholder="Escreva um comentário sobre o serviço (opcional)"></textarea>
                <button type="submit" class="btn-primary-custom"><i class="bi bi-send"></i> Enviar avaliação</button>
                <div id="reviewFeedback" class="review-feedback"></div>
            </form>
        </section>
    </main>

    <div class="modal-backdrop-custom" id="reportModal" role="dialog" aria-modal="true" aria-labelledby="reportTitle">
        <div class="modal-box">
            <div class="modal-head">
                <div><h2 id="reportTitle">Denunciar perfil</h2><p style="color:var(--muted);font-size:13px;margin:7px 0 0;">Ajude a manter a comunidade segura.</p></div>
                <button type="button" class="modal-close" id="closeReport" aria-label="Fechar">&times;</button>
            </div>
            <form id="reportForm">
                <label class="report-label" for="reportReason">Motivo da denúncia</label>
                <select class="report-select" id="reportReason" required><option value="">Selecione um motivo</option><option>Informações falsas ou enganosas</option><option>Comportamento inadequado</option><option>Contato ou anúncio suspeito</option><option>Outro motivo</option></select>
                <label class="report-label" for="reportDescription">Descreva o que aconteceu</label>
                <textarea class="report-textarea" id="reportDescription" placeholder="Informe detalhes que possam ajudar na análise."></textarea>
                <label class="report-label" for="reportFile">Anexo (opcional)</label>
                <input class="report-file" id="reportFile" type="file" accept="image/*,.pdf">
                <div class="modal-actions"><button type="button" class="cancel-report" id="cancelReport">Cancelar</button><button type="submit" class="submit-report">Enviar denúncia</button></div>
                <div class="report-feedback" id="reportFeedback"></div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('reviewForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const nota = document.querySelector('input[name="nota"]:checked');
            const feedback = document.getElementById('reviewFeedback');
            if (!nota) return;
            feedback.textContent = 'Obrigado! Sua avaliação de ' + nota.value + ' estrela(s) foi registrada nesta demonstração.';
            feedback.style.display = 'block';
            this.reset();
        });

        const reportModal = document.getElementById('reportModal');
        const reportForm = document.getElementById('reportForm');
        const reportFeedback = document.getElementById('reportFeedback');
        const closeReport = () => { reportModal.classList.remove('open'); };
        document.getElementById('openReport').addEventListener('click', () => reportModal.classList.add('open'));
        document.getElementById('closeReport').addEventListener('click', closeReport);
        document.getElementById('cancelReport').addEventListener('click', closeReport);
        reportModal.addEventListener('click', (event) => { if (event.target === reportModal) closeReport(); });
        reportForm.addEventListener('submit', function (event) {
            event.preventDefault();
            reportFeedback.textContent = 'Denúncia preenchida para esta demonstração. O envio real será implementado posteriormente.';
            reportFeedback.style.display = 'block';
        });
    </script>
</body>
</html>
