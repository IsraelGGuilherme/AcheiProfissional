<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($titulo ?? 'Achei Profissional') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary:#0876d1; --ink:#263746; --muted:#71808e; --border:#dce6ee; --bg:#f5f8fb; }
        * { box-sizing:border-box; } body { margin:0; background:var(--bg); color:var(--ink); font-family:Arial,sans-serif; font-size:14px; }
        .topbar { background:#fff; border-bottom:1px solid var(--border); } .topbar-inner { max-width:1040px; margin:auto; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:20px; }
        .brand { color:var(--primary); text-decoration:none; font-size:17px; font-weight:700; } .top-links a { color:#354454; text-decoration:none; font-size:13px; margin-left:16px; }
        .page { max-width:900px; margin:auto; padding:36px 20px 60px; } .page-title { margin:0 0 7px; font-size:25px; } .page-subtitle { color:var(--muted); margin:0 0 24px; font-size:13px; }
        .card { background:#fff; border:1px solid var(--border); border-radius:14px; padding:23px; box-shadow:0 4px 14px rgba(35,70,100,.06); margin-bottom:16px; } .card h2 { margin:0 0 18px; font-size:17px; }
        .label { display:block; color:#354454; font-size:12px; font-weight:600; margin:13px 0 6px; } .input,.select,.textarea { width:100%; border:1px solid #ccd9e3; border-radius:8px; padding:10px 11px; background:#fff; color:var(--ink); font:inherit; }
        .textarea { min-height:92px; resize:vertical; } .input:focus,.select:focus,.textarea:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(8,118,209,.1); }
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; } .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; }
        .chips { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; } .chip { background:#e9f5ff; color:#176aa8; border-radius:18px; padding:7px 11px; font-size:12px; }
        .upload { display:flex; gap:16px; align-items:center; } .avatar { width:70px; height:70px; flex:0 0 70px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#dff0ff; color:var(--primary); font-weight:700; font-size:21px; }
        .hint { color:var(--muted); font-size:11px; margin-top:5px; } .actions { display:flex; justify-content:flex-end; gap:10px; margin-top:18px; } .btn { border:1px solid var(--primary); background:#fff; color:var(--primary); border-radius:8px; padding:10px 15px; font-weight:600; text-decoration:none; cursor:pointer; } .btn.primary { background:var(--primary); color:#fff; }
        .notice { background:#eef7ff; border:1px solid #cfe7fa; color:#176aa8; border-radius:9px; padding:11px 13px; font-size:12px; margin-bottom:18px; }
        @media(max-width:650px){.topbar-inner{align-items:flex-start;flex-direction:column}.top-links a{margin:0 14px 0 0}.grid-2,.grid-3{grid-template-columns:1fr}.upload{align-items:flex-start;flex-direction:column}.actions{justify-content:stretch;flex-direction:column}.btn{text-align:center}}
    </style>
</head>
<body>
<header class="topbar"><div class="topbar-inner"><a class="brand" href="<?= site_url('busca') ?>">Achei Profissional</a><nav class="top-links"><a href="<?= site_url('busca') ?>">Buscar</a><a href="<?= site_url('login') ?>">Entrar</a></nav></div></header>
