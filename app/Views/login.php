<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Achei Profissional</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #0876d1;
            --primary-dark: #0567b9;
            --light-blue: #eaf5ff;
            --background: #f4f9fd;
            --text: #172b3f;
            --muted: #718096;
            --border: #d9e2ea;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--background);
            color: var(--text);
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                sans-serif;
        }

        /* =========================================
           HEADER
        ========================================= */

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

        /* =========================================
           MAIN
        ========================================= */

        .login-section {
            min-height: calc(100vh - 72px);
            padding: 55px 20px 70px;

            background:
                radial-gradient(ellipse 75% 55% at 0% 15%,
                    #e3f1ff 0%,
                    transparent 65%),
                #f6faff;
        }

        .welcome-title {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #172b3f;
        }

        .welcome-text {
            color: var(--muted);
            font-size: 15px;
            margin-bottom: 32px;
        }

        /* =========================================
           LOGIN CARD
        ========================================= */

        .login-card {
            width: 100%;
            max-width: 560px;
            margin: 0 auto;

            background: #ffffff;
            border: 1px solid #edf1f5;
            border-radius: 18px;

            padding: 30px;

            box-shadow:
                0 8px 30px rgba(33, 65, 92, 0.08);
        }

        /* =========================================
           TYPE SELECTOR
        ========================================= */

        .account-selector {
            display: flex;
            padding: 4px;
            background: #f6f8fa;
            border: 1px solid #e3e8ed;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .account-option {
            flex: 1;
            border: none;
            background: transparent;
            color: #657386;

            padding: 12px 10px;

            border-radius: 8px;

            font-size: 14px;
            font-weight: 500;

            transition: all .2s;
        }

        .account-option i {
            margin-right: 6px;
        }

        .account-option.active {
            background: #e4f1ff;
            color: var(--primary);
            font-weight: 600;

            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        }

        /* =========================================
           FORM
        ========================================= */

        .form-label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 7px;
            color: #25384a;
        }

        .form-control-custom {
            height: 48px;

            border: 1px solid #cfd9e2;
            border-radius: 9px;

            padding: 10px 14px;

            font-size: 14px;

            box-shadow: none !important;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(8, 118, 209, .10) !important;
        }

        .input-icon {
            position: relative;
        }

        .input-icon>i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            z-index: 5;
        }

        .input-icon .form-control-custom {
            padding-left: 43px;
        }

        .password-icon {
            position: relative;
        }

        .password-icon .form-control-custom {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);

            border: none;
            background: transparent;

            color: #718096;

            cursor: pointer;

            padding: 4px;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* =========================================
           FORGOT PASSWORD
        ========================================= */

        .forgot-password {
            color: var(--primary);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* =========================================
           BUTTON
        ========================================= */

        .btn-login {
            width: 100%;
            height: 48px;

            border: none;
            border-radius: 8px;

            background: var(--primary);
            color: white;

            font-size: 15px;
            font-weight: 600;

            transition: .2s;
        }

        .btn-login:hover {
            background: var(--primary-dark);
        }

        /* =========================================
           SOCIAL BUTTONS
        ========================================= */

        .social-button {
            width: 100%;
            height: 46px;

            border: 1px solid #d6dfe7;
            border-radius: 8px;

            background: #ffffff;

            color: #27384a;

            font-size: 14px;
            font-weight: 500;

            transition: .2s;
        }

        .social-button:hover {
            background: #f8fafc;
            border-color: #c5d0da;
        }

        .social-button+.social-button {
            margin-top: 10px;
        }

        .google-icon {
            color: #4285f4;
            margin-right: 8px;
        }

        .whatsapp-icon {
            color: #25d366;
            margin-right: 8px;
        }

        /* =========================================
           CREATE ACCOUNT
        ========================================= */

        .create-account {
            margin-top: 28px;
            text-align: center;

            font-size: 14px;
            color: #657386;
        }

        .create-account a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
        }

        .create-account a:hover {
            text-decoration: underline;
        }

        /* =========================================
           BENEFITS
        ========================================= */

        .benefits {
            max-width: 700px;
            margin: 45px auto 0;
        }

        .benefit {
            text-align: center;
            padding: 10px 20px;
        }

        .benefit-icon {
            width: 58px;
            height: 58px;

            margin: 0 auto 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #e4f2ff;
            color: var(--primary);

            font-size: 24px;
        }

        .benefit-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .benefit-text {
            color: #778697;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }

        /* =========================================
           FOOTER
        ========================================= */

        .footer {
            border-top: 1px solid #dce5ec;
            margin-top: 55px;
            padding-top: 25px;

            text-align: center;

            color: #718096;
            font-size: 13px;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            margin-left: 25px;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 576px) {

            .login-section {
                padding-top: 35px;
            }

            .welcome-title {
                font-size: 25px;
            }

            .login-card {
                padding: 20px;
            }

            .account-option {
                font-size: 13px;
            }

            .benefits {
                margin-top: 30px;
            }

            .footer a {
                display: inline-block;
                margin: 8px;
            }
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

    <!-- =========================================
         HEADER
    ========================================== -->

    <div class="top-border"></div>

    <header class="navbar-custom">
        <div class="container navbar-inner-wrap">

            <a href="<?= site_url('busca') ?>" class="brand">
                Achei Profissional
            </a>

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

                    <a href="<?= site_url('busca') ?>" class="nav-link-custom">
                        Buscar
                    </a>

                    <a href="<?= site_url('perfil/contratante') ?>" class="nav-link-custom">
                        Sou Contratante
                    </a>

                    <a href="<?= site_url('perfil/profissional') ?>" class="nav-link-custom">
                        Sou Profissional
                    </a>

                    <a href="<?= site_url('admin') ?>" class="nav-link-custom">
                        Admin
                    </a>

                    <a href="<?= site_url('login') ?>" class="nav-link-custom nav-link-login active">
                        Entrar
                    </a>

                </div>
            </nav>

        </div>
    </header>


    <!-- =========================================
         MAIN
    ========================================== -->

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

                <!-- Tipo de conta -->

                <div class="account-selector">

                    <button
                        type="button"
                        class="account-option active"
                        data-type="profissional">
                        <i class="bi bi-briefcase"></i>
                        Sou profissional
                    </button>

                    <button
                        type="button"
                        class="account-option"
                        data-type="contratante">
                        <i class="bi bi-building"></i>
                        Sou contratante
                    </button>

                </div>


                <!-- Formulário -->

                <form>

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
                                placeholder="seu@email.com">

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
                                id="password"
                                class="form-control form-control-custom"
                                placeholder="Sua senha">

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

                        <a href="<?= site_url('recuperar-senha') ?>" class="forgot-password">
                            Esqueci minha senha
                        </a>

                    </div>


                    <!-- Entrar -->

                    <a
                        href="<?= site_url('busca') ?>"
                        class="btn-login d-inline-flex align-items-center justify-content-center text-decoration-none">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Entrar
                    </a>
                    

                    <!-- Criar conta -->

                    <div class="create-account">

                        Ainda não tem uma conta?

                        <a href="<?= site_url('cadastro') ?>">
                            Criar conta
                        </a>

                    </div>

                </form>

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


    <!-- =========================================
         JAVASCRIPT
    ========================================== -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /*
         * Alternância entre:
         * - Profissional
         * - Contratante
         */

        const accountOptions =
            document.querySelectorAll('.account-option');

        accountOptions.forEach(option => {

            option.addEventListener('click', () => {

                accountOptions.forEach(item => {
                    item.classList.remove('active');
                });

                option.classList.add('active');

            });

        });


        /*
         * Mostrar / ocultar senha
         */

        const password =
            document.getElementById('password');

        const passwordToggle =
            document.getElementById('passwordToggle');

        passwordToggle.addEventListener('click', () => {

            const isPassword =
                password.type === 'password';

            password.type =
                isPassword ? 'text' : 'password';

            passwordToggle.innerHTML =
                isPassword ?
                '<i class="bi bi-eye-slash"></i>' :
                '<i class="bi bi-eye"></i>';

        });
    </script>

</body>

</html>