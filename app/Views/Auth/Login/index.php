<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Login - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="login-section">

        <div class="container">

            <!-- Título -->

            <div class="text-center">

                <h1 class="welcome-title">
                    Bem-vindo de volta!
                </h1>

                <p class="welcome-text">
                    Faça login para continuar conectando talentos e oportunidades.
                </p>

            </div>


            <!-- =================================
                 LOGIN
            ================================== -->

            <div class="login-card">

                <?= validation_list_errors() ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success py-2 px-3 mb-4 font-sm" role="alert">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2 px-3 mb-4 font-sm" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário -->

                <?= form_open(base_url('login'), ['method' => 'POST']) ?>

                    <!-- E-mail -->

                    <div class="mb-3">

                        <label class="form-label">
                            E-mail
                        </label>

                        <div class="input-icon">

                            <i class="bi bi-envelope"></i>

                            <input
                                type="email"
                                class="form-control form-control-custom"
                                placeholder="seu@email.com"
                                name="email"
                                id="email">

                        </div>

                    </div>


                    <!-- Senha -->

                    <div class="mb-2">

                        <label class="form-label">
                            Senha
                        </label>

                        <div class="password-icon">

                            <input
                                type="password"
                                id="senha"
                                name="senha"
                                class="form-control form-control-custom"
                                placeholder="Sua senha"
                                required>

                            <button
                                type="button"
                                class="password-toggle"
                                id="passwordToggle">
                                <i class="bi bi-eye"></i>
                            </button>

                        </div>

                    </div>


                    <!-- Esqueci senha -->

                    <div class="text-end mb-4">

                        <a href="<?= base_url('esqueciminhasenha') ?>" class="forgot-password">
                            Esqueci minha senha
                        </a>

                    </div>


                    <!-- Entrar -->

                    <button type="submit"
                        class="btn-login d-inline-flex align-items-center justify-content-center text-decoration-none">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Entrar
                    </button>               

                    <!-- Criar conta -->

                    <div class="create-account">

                        Ainda não tem uma conta?

                        <a href="<?= base_url('register') ?>">
                            Criar conta
                        </a>

                    </div>

                <?= form_close() ?>

            </div>


            <!-- =================================
                 BENEFÍCIOS
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
                                Seus dados protegidos
                                com segurança.
                            </p>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="benefit">

                            <div class="benefit-icon">
                                <i class="bi bi-people"></i>
                            </div>

                            <div class="benefit-title">
                                Conexões reais
                            </div>

                            <p class="benefit-text">
                                Profissionais verificados
                                e avaliados.
                            </p>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="benefit">

                            <div class="benefit-icon">
                                <i class="bi bi-chat-dots"></i>
                            </div>

                            <div class="benefit-title">
                                Contato direto
                            </div>

                            <p class="benefit-text">
                                Encontre e fale diretamente
                                com profissionais.
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
        // Alternância entre tipos de conta
        const accountOptions = document.querySelectorAll('.account-option');
        accountOptions.forEach(option => {
            option.addEventListener('click', () => {
                accountOptions.forEach(item => item.classList.remove('active'));
                option.classList.add('active');
            });
        });

        // Toggle de senha
        AuthUI.initPasswordToggle('#passwordToggle', '#password');
    </script>
<?= $this->endSection() ?>