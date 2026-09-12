<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Recuperação de Senha - Achei Profissional<?= $this->endSection() ?>

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
                        <i class="bi bi-key"></i>
                        Recuperação de Acesso
                    </span>

                    <h1 class="welcome-title">
                        Esqueceu sua senha?
                    </h1>

                    <p class="welcome-text">
                        Não se preocupe! Informe o e-mail cadastrado em sua conta para receber as instruções de redefinição de senha.
                    </p>

                </div>

                <!-- Alerta de Erro em Tempo Real (Cliente) -->
                <div id="clientErrorAlert" class="alert alert-danger py-2 px-3 mb-4 d-none" role="alert"></div>

                <!-- Mensagens de Erro de Validação (Backend) -->
                <?php if (function_exists('validation_list_errors') && validation_list_errors()): ?>
                    <?= validation_list_errors() ?>
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

                <!-- Formulário de Recuperação de Senha -->
                <form action="<?= base_url('esqueciminhasenha') ?>" method="POST" id="formEsqueciSenha" novalidate>
                    <?= csrf_field() ?>

                    <!-- Campo: E-mail -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            E-mail cadastrado <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input
                                type="email"
                                class="form-control form-control-custom"
                                id="email"
                                name="email"
                                value="<?= old('email') ?>"
                                placeholder="seu@email.com"
                                required
                                autocomplete="email"
                                autofocus>
                        </div>
                        <div class="form-text text-muted mt-2">
                            Enviaremos um código ou link de recuperação para este endereço.
                        </div>
                    </div>

                    <!-- Botão de Envio -->
                    <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2" id="btnSubmit">
                        <i class="bi bi-send"></i>
                        <span>Enviar instruções</span>
                    </button>

                    <!-- Link de Retorno ao Login -->
                    <div class="text-center mt-4">
                        <span class="text-muted small">Lembrou da senha?</span>
                        <a href="<?= base_url('login') ?>" class="text-decoration-none fw-semibold small ms-1">
                            Entrar na conta
                        </a>
                    </div>

                </form>

            </div>

            <!-- =================================
                 BENEFÍCIOS / CONFIABILIDADE
            ================================== -->
            <div class="benefits">

                <div class="row g-4">

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="benefit-title">
                                Ambiente seguro
                            </div>
                            <p class="benefit-text">
                                Seus dados estão protegidos com total privacidade.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-envelope-check"></i>
                            </div>
                            <div class="benefit-title">
                                Validação rápida
                            </div>
                            <p class="benefit-text">
                                Receba as instruções de recuperação instantaneamente no seu e-mail.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div class="benefit-title">
                                Suporte pronto
                            </div>
                            <p class="benefit-text">
                                Teve dificuldades? Nossa equipe está sempre à disposição.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const form = document.getElementById('formEsqueciSenha');
        const emailInput = document.getElementById('email');
        const clientErrorAlert = document.getElementById('clientErrorAlert');
        const btnSubmit = document.getElementById('btnSubmit');

        emailInput.addEventListener('input', () => {
            if (emailInput.value.trim() !== '') {
                emailInput.classList.remove('is-invalid');
                AuthUI.clearErrors(clientErrorAlert);
            }
        });

        form.addEventListener('submit', (e) => {
            const emailValue = emailInput.value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailValue) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');
                AuthUI.showErrors(clientErrorAlert, ['Por favor, digite seu endereço de e-mail.']);
                emailInput.focus();
                return;
            }

            if (!emailPattern.test(emailValue)) {
                e.preventDefault();
                emailInput.classList.add('is-invalid');
                AuthUI.showErrors(clientErrorAlert, ['Por favor, informe um endereço de e-mail válido (ex: seu@email.com).']);
                emailInput.focus();
                return;
            }

            // Feedback visual no envio
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';
        });
    </script>
<?= $this->endSection() ?>

