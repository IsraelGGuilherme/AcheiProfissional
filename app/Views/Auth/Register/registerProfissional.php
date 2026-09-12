<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Cadastro de Profissional - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">

        <div class="container">

            <div class="register-card">

                <a href="<?= base_url('register') ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Voltar para opções de cadastro
                </a>

                <div class="text-center mb-4">

                    <span class="badge-type">
                        <i class="bi bi-briefcase"></i>
                        Usuário Profissional / Prestador
                    </span>

                    <h1 class="welcome-title">
                        Crie sua conta profissional
                    </h1>

                    <p class="welcome-text">
                        Informe seu e-mail e crie uma senha para divulgar seus serviços.
                    </p>

                </div>

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

                <!-- Formulário de Cadastro Inicial do Profissional -->
                <form action="<?= base_url('register') ?>" method="POST" id="formRegisterProfissional" novalidate>
                    <?= csrf_field() ?>

                    <!-- Campo Oculto: Tipo de Usuário -->
                    <div>
                        <input type="hidden" name="tipo" value="PROFISSIONAL">
                    </div>

                    <!-- Campo: E-mail -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            E-mail Profissional <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input
                                type="email"
                                class="form-control form-control-custom"
                                id="email"
                                name="email"
                                placeholder="seu@email.com"
                                value="<?= old('email') ?>"
                                required
                                autocomplete="email">
                        </div>
                    </div>

                    <!-- Campo: Senha -->
                    <div class="mb-3">
                        <label for="senha" class="form-label">
                            Senha <span class="text-danger">*</span>
                        </label>
                        <div class="password-wrapper">
                            <i class="bi bi-lock field-icon"></i>
                            <input
                                type="password"
                                class="form-control form-control-custom"
                                id="senha"
                                name="senha"
                                placeholder="Crie uma senha (mínimo 8 caracteres)"
                                minlength="8"
                                required
                                autocomplete="new-password">
                            <button
                                type="button"
                                class="password-toggle"
                                id="toggleSenha"
                                aria-label="Mostrar ou ocultar senha">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            A senha deve conter no mínimo 8 caracteres.
                        </div>
                        <div id="senhaFeedback" class="password-feedback"></div>
                    </div>

                    <!-- Campo: Confirmar Senha -->
                    <div class="mb-4">
                        <label for="confirmar_senha" class="form-label">
                            Confirmar Senha <span class="text-danger">*</span>
                        </label>
                        <div class="password-wrapper">
                            <i class="bi bi-shield-lock field-icon"></i>
                            <input
                                type="password"
                                class="form-control form-control-custom"
                                id="confirmar_senha"
                                name="confirmar_senha"
                                placeholder="Repita sua senha"
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
                    <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2">
                        <span>Continuar cadastro</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <!-- Link para Login -->
                    <div class="login-link-box">
                        Já tem uma conta?
                        <a href="<?= base_url('login') ?>">
                            Entrar
                        </a>
                    </div>

                </form>

            </div>


            <!-- =================================
                 BENEFÍCIOS PARA O PROFISSIONAL
            ================================== -->

            <div class="benefits">

                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div class="benefit-title">
                                Mais visibilidade
                            </div>
                            <p class="benefit-text">
                                Conecte-se com novos clientes que procuram seus serviços.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div class="benefit-title">
                                Perfil profissional
                            </div>
                            <p class="benefit-text">
                                Exiba seus serviços, fotos, especialidades e informações.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <div class="benefit-title">
                                Contato direto
                            </div>
                            <p class="benefit-text">
                                Receba contatos de orçamentos diretamente sem intermediários.
                            </p>
                        </div>
                    </div>

                </div>

            </div>


            <!-- =================================
                 FOOTER
            ================================== -->

            <footer class="footer">
                <div>
                    © 2026 Achei Profissional. Todos os direitos reservados.

                    <a href="#">
                        Termos de uso
                    </a>

                    <a href="#">
                        Política de privacidade
                    </a>

                    <a href="#">
                        Ajuda
                    </a>
                </div>
            </footer>

        </div>

    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const form = document.getElementById('formRegisterProfissional');
        const emailInput = document.getElementById('email');
        const senhaInput = document.getElementById('senha');
        const confirmarSenhaInput = document.getElementById('confirmar_senha');
        const toggleSenhaBtn = document.getElementById('toggleSenha');
        const toggleConfirmarSenhaBtn = document.getElementById('toggleConfirmarSenha');
        const senhaFeedback = document.getElementById('senhaFeedback');
        const confirmarFeedback = document.getElementById('confirmarFeedback');
        const clientErrorAlert = document.getElementById('clientErrorAlert');

        // Alternar visibilidade das senhas
        AuthUI.initPasswordToggle('#toggleSenha', '#senha');
        AuthUI.initPasswordToggle('#toggleConfirmarSenha', '#confirmar_senha');

        // Validação do campo Senha
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
                senhaFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> A senha precisa ter pelo menos 8 caracteres (atual: ' + passVal.length + ').';
                return false;
            } else {
                senhaInput.classList.remove('is-invalid');
                senhaInput.classList.add('is-valid');
                senhaFeedback.className = 'password-feedback show-success';
                senhaFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Tamanho de senha adequado.';
                return true;
            }
        }

        // Validação da Confirmação de Senha
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
                confirmarFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> As senhas coincidem perfeitamente!';
                return true;
            }
        }

        // Eventos de digitação (input)
        senhaInput.addEventListener('input', () => {
            validateSenha();
            if (confirmarSenhaInput.value.length > 0) {
                validateConfirmarSenha();
            }
        });

        confirmarSenhaInput.addEventListener('input', () => {
            validateConfirmarSenha();
        });

        // Validação e feedback no Submit
        form.addEventListener('submit', (e) => {
            let hasError = false;
            let errorMessages = [];

            // Validação de e-mail básico
            if (!emailInput.value || !emailInput.checkValidity()) {
                emailInput.classList.add('is-invalid');
                hasError = true;
                errorMessages.push('Por favor, informe um endereço de e-mail válido.');
            } else {
                emailInput.classList.remove('is-invalid');
            }

            // Validação de senha
            const isSenhaOk = validateSenha();
            if (!isSenhaOk) {
                hasError = true;
                senhaInput.classList.add('is-invalid');
                senhaFeedback.className = 'password-feedback show-error';
                senhaFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> A senha deve ter no mínimo 8 caracteres.';
                errorMessages.push('A senha deve ter no mínimo 8 caracteres.');
            }

            // Validação de confirmação
            const isConfirmOk = validateConfirmarSenha();
            if (!isConfirmOk || senhaInput.value !== confirmarSenhaInput.value) {
                hasError = true;
                confirmarSenhaInput.classList.add('is-invalid');
                confirmarFeedback.className = 'password-feedback show-error';
                confirmarFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> As senhas não conferem. Verifique e tente novamente.';
                errorMessages.push('As senhas digitadas não coincidem.');
            }

            if (hasError) {
                e.preventDefault();
                AuthUI.showErrors(clientErrorAlert, errorMessages);

                // Foco no primeiro campo com erro
                if (!emailInput.value || !emailInput.checkValidity()) {
                    emailInput.focus();
                } else if (!isSenhaOk) {
                    senhaInput.focus();
                } else {
                    confirmarSenhaInput.focus();
                }
            } else {
                AuthUI.clearErrors(clientErrorAlert);
            }
        });
    </script>
<?= $this->endSection() ?>
