<?= $this->include('partials/visual_header', ['titulo' => 'Cadastro de usuário - Achei Profissional']) ?>
<main class="page">
    <h1 class="page-title">Crie sua conta</h1>
    <p class="page-subtitle">Cadastre-se para encontrar profissionais ou divulgar seus serviços.</p>
    <div class="notice"><i class="bi bi-info-circle me-1"></i> Esta é uma tela demonstrativa. O cadastro será conectado ao banco em uma etapa posterior.</div>
    <form class="card">
        <h2>Dados da conta</h2>
        <div class="grid-2">
            <div><label class="label" for="nome">Nome completo</label><input class="input" id="nome" placeholder="Digite seu nome"></div>
            <div><label class="label" for="email">E-mail</label><input class="input" id="email" type="email" placeholder="voce@email.com"></div>
        </div>
        <div class="grid-2">
            <div><label class="label" for="senha">Senha</label><input class="input" id="senha" type="password" placeholder="Crie uma senha"></div>
            <div><label class="label" for="confirmacao">Confirmar senha</label><input class="input" id="confirmacao" type="password" placeholder="Repita a senha"></div>
        </div>
        <label class="label" for="tipo">Tipo de conta</label>
        <select class="select" id="tipo"><option>Selecione o tipo de conta</option><option>Profissional</option><option>Contratante</option></select>
        <div class="actions"><a class="btn" href="<?= site_url('login') ?>">Voltar</a><button class="btn primary" type="button">Criar conta</button></div>
    </form>
</main>
</body></html>
