<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Redefinir Senha - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="login-section">
        <div class="container">

            <div class="login-card">

                <a href="<?= base_url('login') ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Voltar para o login
                </a>

                <div class="text-center mb-4">
                    <span class="badge-type">
                        <i class="bi bi-key-fill"></i>
                        Nova Senha
                    </span>

                    <h1 class="welcome-title">
                        Redefinir sua senha
                    </h1>

                    <p class="welcome-text">
                        Crie uma nova senha de acesso forte e segura para a sua conta.
                    </p>
                </div>

                <!-- Box exibindo o e-mail do usuário em texto informativo (sem input) -->
                <?php 
                    $userEmail = $email ?? session('recuperarConta.email') ?? (is_array(session('recuperarConta')) ? (session('recuperarConta')['email'] ?? '') : '');
                ?>
                <?php if (!empty($userEmail)): ?>
                    <div class="email-display-box mb-4">
                        <div class="email-display-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="email-display-info">
                            <div class="email-display-label">Conta em redefinição</div>
                            <div class="email-display-value" id="userEmailDisplay" title="<?= esc($userEmail) ?>">
                                <?= esc($userEmail) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Alerta de Erro em Tempo Real (Cliente) -->
                <div id="clientErrorAlert" class="alert alert-danger py-2 px-3 mb-4 d-none" role="alert"></div>

                <!-- Mensagens de Erro de Validação (Backend) -->
                <?php if (function_exists('validation_list_errors') && validation_list_errors()): ?>
                    <div class="alert alert-danger py-2 px-3 mb-4 font-sm" role="alert">
                        <?= validation_list_errors() ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2 px-3 mb-4 font-sm" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success py-2 px-3 mb-4 font-sm" role="alert">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário de Redefinição de Senha (Sem input de e-mail) -->
                <form action="<?= base_url('redefinirsenha') ?>" method="POST" id="formRedefinirSenha" novalidate>
                    <?= csrf_field() ?>

                    <!-- Campo: Nova Senha -->
                    <div class="mb-3">
                        <label for="senha" class="form-label">
                            Nova Senha <span class="text-danger">*</span>
                        </label>
                        <div class="password-wrapper">
                            <i class="bi bi-lock field-icon"></i>
                            <input
                                type="password"
                                class="form-control form-control-custom"
                                id="senha"
                                name="senha"
                                placeholder="Digite sua nova senha"
                                minlength="8"
                                required
                                autocomplete="new-password"
                                autofocus>
                            <button
                                type="button"
                                class="password-toggle"
                                id="toggleSenha"
                                aria-label="Mostrar ou ocultar nova senha">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            A senha deve conter no mínimo 8 caracteres.
                        </div>
                        <div id="senhaFeedback" class="password-feedback"></div>
                    </div>

                    <!-- Campo: Confirmar Nova Senha -->
                    <div class="mb-4">
                        <label for="confirmarSenha" class="form-label">
                            Confirmar Nova Senha <span class="text-danger">*</span>
                        </label>
                        <div class="password-wrapper">
                            <i class="bi bi-shield-lock field-icon"></i>
                            <input
                                type="password"
                                class="form-control form-control-custom"
                                id="confirmarSenha"
                                name="confirmarSenha"
                                placeholder="Repita a nova senha"
                                minlength="8"
                                required
                                autocomplete="new-password">
                            <button
                                type="button"
                                class="password-toggle"
                                id="toggleConfirmarSenha"
                                aria-label="Mostrar ou ocultar confirmação de senha">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div id="confirmarFeedback" class="password-feedback"></div>
                    </div>

                    <!-- Botão de Envio -->
                    <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2" id="btnSubmit">
                        <i class="bi bi-check2-circle"></i>
                        <span>Redefinir senha</span>
                    </button>

                    <!-- Link de Retorno ao Login -->
                    <div class="text-center mt-4">
                        <span class="text-muted small">Lembrou da senha antiga?</span>
                        <a href="<?= base_url('login') ?>" class="text-decoration-none fw-semibold small ms-1">
                            Voltar ao login
                        </a>
                    </div>

                </form>

            </div>

            <!-- =================================
                 BENEFÍCIOS / SEGURANÇA
            ================================== -->
            <div class="benefits">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="benefit-title">
                                Segurança ponta a ponta
                            </div>
                            <p class="benefit-text">
                                Sua nova senha será protegida com hash criptográfico avançado.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-arrow-clockwise"></i>
                            </div>
                            <div class="benefit-title">
                                Atualização imediata
                            </div>
                            <p class="benefit-text">
                                Assim que alterada, sua senha já estará pronta para o próximo login.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div class="benefit-title">
                                Suporte disponível
                            </div>
                            <p class="benefit-text">
                                Em caso de qualquer dúvida, conte sempre com nosso suporte.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div>
                    © <?= date('Y') ?> Achei Profissional. Todos os direitos reservados.
                    <a href="#">Termos de uso</a>
                    <a href="#">Política de privacidade</a>
                    <a href="#">Ajuda</a>
                </div>
            </footer>

        </div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const form = document.getElementById('formRedefinirSenha');
        const senhaInput = document.getElementById('senha');
        const confirmarSenhaInput = document.getElementById('confirmar_senha');
        const senhaFeedback = document.getElementById('senhaFeedback');
        const confirmarFeedback = document.getElementById('confirmarFeedback');
        const clientErrorAlert = document.getElementById('clientErrorAlert');
        const btnSubmit = document.getElementById('btnSubmit');

        // Alternar visibilidade das senhas usando helper do módulo auth
        AuthUI.initPasswordToggle('#toggleSenha', '#senha');
        AuthUI.initPasswordToggle('#toggleConfirmarSenha', '#confirmar_senha');

        // Validação da Senha
        function validateSenha() {
            const passVal = senhaInput.value;

            if (passVal.length === 0) {
                senhaInput.classList.remove('is-invalid', 'is-valid');
                senhaFeedback.className = 'password-feedback';
                senhaFeedback.innerHTML = '';
                return false;
            }

            if (passVal.length < 8) {
                senhaInput.classList.add('is-invalid');
                senhaInput.classList.remove('is-valid');
                senhaFeedback.className = 'password-feedback show-error';
                senhaFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> A nova senha precisa ter pelo menos 8 caracteres (atual: ' + passVal.length + ').';
                return false;
            } else {
                senhaInput.classList.remove('is-invalid');
                senhaInput.classList.add('is-valid');
                senhaFeedback.className = 'password-feedback show-success';
                senhaFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Tamanho de senha adequado.';
                return true;
            }
        }

        // Validação da Confirmação
        function validateConfirmarSenha() {
            const passVal = senhaInput.value;
            const confirmVal = confirmarSenhaInput.value;

            if (confirmVal.length === 0) {
                confirmarSenhaInput.classList.remove('is-invalid', 'is-valid');
                confirmarFeedback.className = 'password-feedback';
                confirmarFeedback.innerHTML = '';
                return false;
            }

            if (passVal !== confirmVal) {
                confirmarSenhaInput.classList.add('is-invalid');
                confirmarSenhaInput.classList.remove('is-valid');
                confirmarFeedback.className = 'password-feedback show-error';
                confirmarFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> As senhas não conferem. Digite a mesma senha para confirmar.';
                return false;
            } else {
                confirmarSenhaInput.classList.remove('is-invalid');
                confirmarSenhaInput.classList.add('is-valid');
                confirmarFeedback.className = 'password-feedback show-success';
                confirmarFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> As senhas coincidem perfeitamente.';
                return true;
            }
        }

        // Eventos em tempo real
        senhaInput.addEventListener('input', () => {
            validateSenha();
            if (confirmarSenhaInput.value.length > 0) {
                validateConfirmarSenha();
            }
        });

        confirmarSenhaInput.addEventListener('input', () => {
            validateConfirmarSenha();
        });

        // Validação no Submit
        form.addEventListener('submit', (e) => {
            let hasError = false;
            let errorMessages = [];

            const isSenhaOk = validateSenha();
            if (!isSenhaOk) {
                hasError = true;
                senhaInput.classList.add('is-invalid');
                senhaFeedback.className = 'password-feedback show-error';
                senhaFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> A senha deve ter no mínimo 8 caracteres.';
                errorMessages.push('A nova senha deve conter pelo menos 8 caracteres.');
            }

            const isConfirmOk = validateConfirmarSenha();
            if (!isConfirmOk || senhaInput.value !== confirmarSenhaInput.value) {
                hasError = true;
                confirmarSenhaInput.classList.add('is-invalid');
                confirmarFeedback.className = 'password-feedback show-error';
                confirmarFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> As senhas não conferem.';
                errorMessages.push('As senhas digitadas não coincidem.');
            }

            if (hasError) {
                e.preventDefault();
                AuthUI.showErrors(clientErrorAlert, errorMessages);

                if (!isSenhaOk) {
                    senhaInput.focus();
                } else {
                    confirmarSenhaInput.focus();
                }
            } else {
                AuthUI.clearErrors(clientErrorAlert);
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Redefinindo senha...';
            }
        });
    </script>
<?= $this->endSection() ?>

