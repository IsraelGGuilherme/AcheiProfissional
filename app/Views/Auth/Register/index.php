<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Cadastro - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">

        <div class="container">

            <!-- Título -->

            <div class="text-center">

                <h1 class="welcome-title">
                    Crie sua conta
                </h1>

                <p class="welcome-text">
                    Selecione como deseja se cadastrar na plataforma.
                </p>

            </div>


            <!-- =================================
                 SELEÇÃO DE TIPO DE CONTA
            ================================== -->

            <div class="register-card">

                <!-- Opção 1: Usuário Comum -->
                <a href="<?= base_url('register/usuario') ?>" class="account-type-card">

                    <div class="account-type-icon">
                        <i class="bi bi-person"></i>
                    </div>

                    <div class="account-type-info">

                        <div class="account-type-header">
                            <h2 class="account-type-title">
                                Usuário Comum
                            </h2>
                            <span class="account-type-badge">
                                Contratante
                            </span>
                        </div>

                        <p class="account-type-desc">
                            Quero encontrar profissionais qualificados, solicitar orçamentos e contratar serviços com facilidade.
                        </p>

                    </div>

                    <div class="account-type-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </div>

                </a>


                <!-- Opção 2: Usuário do tipo Profissional -->
                <a href="<?= base_url('register/profissional') ?>" class="account-type-card">

                    <div class="account-type-icon">
                        <i class="bi bi-briefcase"></i>
                    </div>

                    <div class="account-type-info">

                        <div class="account-type-header">
                            <h2 class="account-type-title">
                                Usuário Profissional
                            </h2>
                            <span class="account-type-badge">
                                Prestador
                            </span>
                        </div>

                        <p class="account-type-desc">
                            Quero divulgar meus serviços, receber solicitações de orçamentos e conquistar novos clientes.
                        </p>

                    </div>

                    <div class="account-type-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </div>

                </a>


                <!-- Já possui conta -->

                <div class="login-link-box">

                    Já tem uma conta?

                    <a href="<?= base_url('login') ?>">
                        Entrar
                    </a>

                </div>

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
                                com total segurança.
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
