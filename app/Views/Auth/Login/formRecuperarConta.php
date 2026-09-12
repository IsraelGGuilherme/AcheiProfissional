<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Recuperação de Conta - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="login-section">
        <div class="container">

            <div class="login-card">

                <a href="<?= base_url('esqueciminhasenha') ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Voltar para recuperação
                </a>

                <div class="text-center mb-4">
                    <span class="badge-type">
                        <i class="bi bi-shield-lock"></i>
                        Recuperação de Acesso
                    </span>

                    <h1 class="welcome-title">
                        Recupere sua conta
                    </h1>

                    <p class="welcome-text">
                        Enviaremos um código de verificação para que você possa redefinir sua senha com total segurança.
                    </p>
                </div>

                <!-- Box exibindo o e-mail informado -->
                <div class="email-display-box">
                    <div class="email-display-icon">
                        <i class="bi bi-envelope-at"></i>
                    </div>
                    <div class="email-display-info">
                        <div class="email-display-label">E-mail para envio do código</div>
                        <div class="email-display-value" id="userEmailDisplay" title="<?= esc($email ?? '') ?>">
                            <?= esc($email ?? '') ?>
                        </div>
                    </div>
                </div>

                <!-- Aviso informativo de segurança: envio condicionado à existência da conta -->
                <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                    <span class="small">
                        Por motivos de segurança, as instruções e o código de verificação só serão enviados caso exista uma conta cadastrada para este endereço de e-mail.
                    </span>
                </div>

                <!-- Alertas dinâmicos -->
                <div id="alertSuccess" class="alert alert-success py-2 px-3 mb-3 d-none align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <span id="alertSuccessMsg" class="small"></span>
                </div>

                <div id="alertError" class="alert alert-danger py-2 px-3 mb-3 d-none align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <span id="alertErrorMsg" class="small"></span>
                </div>

                <!-- Sessão 1: Botão inicial de envio do código -->
                <div id="sectionEnviarCodigo" class="mb-3">
                    <button
                        type="button"
                        id="btnEnviarCodigo"
                        class="btn-submit d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-send"></i>
                        <span id="btnEnviarTexto">Enviar código para este e-mail</span>
                    </button>
                </div>

                <!-- Sessão 2: Informar código recebido (Exibida após o envio) -->
                <div id="sectionInformarCodigo" class="d-none fade-in">
                    <hr class="my-4" style="border-color: #e5edf4;">

                    <div class="text-center mb-3">
                        <h2 class="h6 fw-bold mb-1 text-dark">
                            Digite o código de verificação
                        </h2>
                        <p class="text-muted small mb-0">
                            Informe os 6 dígitos recebidos na sua caixa de entrada ou spam.
                        </p>
                    </div>

                    <!-- Formulário de validação do código -->
                    <form action="<?= base_url('recuperarconta') ?>" method="POST" id="formValidarCodigo">
                        <!-- CSRF Field do CodeIgniter -->
                        <?= csrf_field() ?>

                        <!-- Campo oculto com o código completo concatenado -->
                        <input type="hidden" name="codigo" id="codigoCompleto" value="">

                        <!-- 6 campos individuais de dígitos (OTP) -->
                        <div class="otp-container" id="otpContainer">
                            <input type="text" inputmode="numeric" maxlength="1" class="otp-input" data-index="0" autocomplete="off" aria-label="Dígito 1">
                            <input type="text" inputmode="numeric" maxlength="1" class="otp-input" data-index="1" autocomplete="off" aria-label="Dígito 2">
                            <input type="text" inputmode="numeric" maxlength="1" class="otp-input" data-index="2" autocomplete="off" aria-label="Dígito 3">
                            <input type="text" inputmode="numeric" maxlength="1" class="otp-input" data-index="3" autocomplete="off" aria-label="Dígito 4">
                            <input type="text" inputmode="numeric" maxlength="1" class="otp-input" data-index="4" autocomplete="off" aria-label="Dígito 5">
                            <input type="text" inputmode="numeric" maxlength="1" class="otp-input" data-index="5" autocomplete="off" aria-label="Dígito 6">
                        </div>

                        <!-- Botão de confirmar código -->
                        <button
                            type="submit"
                            id="btnConfirmarCodigo"
                            class="btn-submit d-flex align-items-center justify-content-center gap-2 mb-3"
                            disabled>
                            <i class="bi bi-shield-check"></i>
                            <span>Verificar código e prosseguir</span>
                        </button>
                    </form>

                    <!-- Reenvio de código com contagem regressiva de 30 segundos -->
                    <div class="text-center mt-3">
                        <p class="text-muted small mb-2">Não recebeu o código?</p>
                        <button
                            type="button"
                            id="btnReenviarCodigo"
                            class="btn btn-outline-custom d-inline-flex align-items-center gap-2"
                            disabled>
                            <i class="bi bi-arrow-repeat"></i>
                            <span id="textoReenviar">Reenviar código (<span id="contadorReenviar">30</span>s)</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Benefícios / Segurança -->
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
                                Seus dados estão protegidos com criptografia.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-envelope-check"></i>
                            </div>
                            <div class="benefit-title">
                                Envio sigiloso
                            </div>
                            <p class="benefit-text">
                                Apenas o proprietário da conta recebe as instruções.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div class="benefit-title">
                                Precisa de ajuda?
                            </div>
                            <p class="benefit-text">
                                Fale com nossa equipe caso não tenha mais acesso ao e-mail.
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
        document.addEventListener('DOMContentLoaded', () => {
            // Elementos
            const btnEnviarCodigo = document.getElementById('btnEnviarCodigo');
            const sectionEnviarCodigo = document.getElementById('sectionEnviarCodigo');
            const sectionInformarCodigo = document.getElementById('sectionInformarCodigo');
            const btnReenviarCodigo = document.getElementById('btnReenviarCodigo');
            const alertSuccess = document.getElementById('alertSuccess');
            const alertSuccessMsg = document.getElementById('alertSuccessMsg');
            const alertError = document.getElementById('alertError');
            const alertErrorMsg = document.getElementById('alertErrorMsg');
            const formValidarCodigo = document.getElementById('formValidarCodigo');
            const btnConfirmarCodigo = document.getElementById('btnConfirmarCodigo');
            const codigoCompleto = document.getElementById('codigoCompleto');
            const otpInputs = document.querySelectorAll('.otp-input');

            // Configuração
            const URL_ENVIAR_CODIGO = '<?= base_url('receberemailrecuperacao') ?>';
            const TEMPO_COUNTDOWN = 30; // 30 segundos
            let intervaloTimer = null;

            // CSRF Tokens
            function getCsrfData() {
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const headerMeta = document.querySelector('meta[name="csrf-header"]');
                return {
                    header: headerMeta ? headerMeta.getAttribute('content') : 'X-CSRF-TOKEN',
                    token: tokenMeta ? tokenMeta.getAttribute('content') : ''
                };
            }

            function atualizarCsrfToken(novoToken) {
                if (!novoToken) return;
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (tokenMeta) tokenMeta.setAttribute('content', novoToken);

                const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                if (csrfInput) csrfInput.value = novoToken;
            }

            // Exibir mensagens
            function mostrarSucesso(mensagem) {
                alertError.classList.add('d-none');
                alertError.classList.remove('d-flex');
                alertSuccessMsg.textContent = mensagem;
                alertSuccess.classList.remove('d-none');
                alertSuccess.classList.add('d-flex');
            }

            function mostrarErro(mensagem) {
                alertSuccess.classList.add('d-none');
                alertSuccess.classList.remove('d-flex');
                alertErrorMsg.textContent = mensagem;
                alertError.classList.remove('d-none');
                alertError.classList.add('d-flex');
            }

            function limparAlertas() {
                alertSuccess.classList.add('d-none');
                alertSuccess.classList.remove('d-flex');
                alertError.classList.add('d-none');
                alertError.classList.remove('d-flex');
            }

            // Iniciar contagem regressiva de 30 segundos
            function iniciarCountdown(segundos = TEMPO_COUNTDOWN) {
                if (intervaloTimer) clearInterval(intervaloTimer);

                let restante = segundos;
                btnReenviarCodigo.disabled = true;
                btnReenviarCodigo.innerHTML = `
                    <i class="bi bi-arrow-repeat"></i>
                    <span>Reenviar código (<span id="contadorReenviar">${restante}</span>s)</span>
                `;

                intervaloTimer = setInterval(() => {
                    restante--;
                    const spanContador = document.getElementById('contadorReenviar');
                    if (spanContador) {
                        spanContador.textContent = restante;
                    }

                    if (restante <= 0) {
                        clearInterval(intervaloTimer);
                        intervaloTimer = null;
                        btnReenviarCodigo.disabled = false;
                        btnReenviarCodigo.innerHTML = `
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            <span>Reenviar código agora</span>
                        `;
                    }
                }, 1000);
            }

            // Envio Assíncrono do Código de Recuperação
            async function enviarCodigoAssincrono(origemBtn) {
                limparAlertas();

                // Estado de Carregamento
                const textoOriginal = origemBtn.innerHTML;
                origemBtn.disabled = true;
                origemBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    <span>Enviando código...</span>
                `;

                const csrf = getCsrfData();
                const headers = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                };
                if (csrf.token) {
                    headers[csrf.header] = csrf.token;
                }

                try {
                    const response = await fetch(URL_ENVIAR_CODIGO, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({})
                    });

                    const data = await response.json().catch(() => null);

                    if (data && data.csrf_token) {
                        atualizarCsrfToken(data.csrf_token);
                    }

                    if (!response.ok || (data && data.success === false)) {
                        const erroBackend = (data && (data.message || data.error)) 
                            ? (data.message || data.error) 
                            : 'Ocorreu um erro ao processar o envio. Tente novamente.';
                        throw new Error(erroBackend);
                    }

                    // Mensagem informando que foi enviado se a conta existir
                    const mensagemSucesso = (data && data.message) 
                        ? data.message 
                        : 'Se existir uma conta cadastrada para este e-mail, o código de recuperação foi enviado para a sua caixa de entrada e spam.';
                    mostrarSucesso(mensagemSucesso);

                    // Oculta botão inicial e exibe campos OTP
                    sectionEnviarCodigo.classList.add('d-none');
                    sectionInformarCodigo.classList.remove('d-none');

                    // Dispara a contagem regressiva de 30 segundos
                    iniciarCountdown(TEMPO_COUNTDOWN);

                    // Foco no primeiro campo do OTP
                    if (otpInputs.length > 0) {
                        otpInputs[0].focus();
                    }

                } catch (err) {
                    mostrarErro(err.message || 'Falha de comunicação com o servidor. Tente novamente.');
                    origemBtn.disabled = false;
                    origemBtn.innerHTML = textoOriginal;
                }
            }

            // Eventos dos botões de envio e reenvio
            btnEnviarCodigo.addEventListener('click', () => {
                enviarCodigoAssincrono(btnEnviarCodigo);
            });

            btnReenviarCodigo.addEventListener('click', () => {
                enviarCodigoAssincrono(btnReenviarCodigo);
            });

            // =========================================
            // COMPORTAMENTO DOS INPUTS OTP (6 DÍGITOS)
            // =========================================
            function atualizarCodigoFinal() {
                let codigo = '';
                otpInputs.forEach(input => codigo += input.value.trim());
                codigoCompleto.value = codigo;

                // Habilita o botão de confirmação apenas com os 6 dígitos preenchidos
                btnConfirmarCodigo.disabled = (codigo.length !== 6);
            }

            otpInputs.forEach((input, index) => {
                // Aceitar apenas números e avançar foco
                input.addEventListener('input', (e) => {
                    input.classList.remove('is-invalid');

                    // Limpa caracteres não numéricos
                    input.value = input.value.replace(/[^0-9]/g, '');

                    if (input.value.length > 0) {
                        input.classList.add('is-filled');
                        if (index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    } else {
                        input.classList.remove('is-filled');
                    }

                    atualizarCodigoFinal();
                });

                // Tecla Backspace para retornar foco
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });

                // Suporte a Colar (Paste)
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
                    const apenasNumeros = pasteData.replace(/[^0-9]/g, '').slice(0, 6);

                    if (apenasNumeros.length > 0) {
                        apenasNumeros.split('').forEach((num, i) => {
                            if (otpInputs[i]) {
                                otpInputs[i].value = num;
                                otpInputs[i].classList.remove('is-invalid');
                                otpInputs[i].classList.add('is-filled');
                            }
                        });

                        const proximoIndice = Math.min(apenasNumeros.length, otpInputs.length - 1);
                        otpInputs[proximoIndice].focus();
                        atualizarCodigoFinal();
                    }
                });
            });

            // Validação assíncrona do código recebido
            formValidarCodigo.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (codigoCompleto.value.length !== 6) {
                    mostrarErro('Por favor, informe todos os 6 dígitos do código recebido.');
                    return;
                }

                limparAlertas();

                // Estado de carregamento no botão de confirmação
                const textoOriginalBtn = btnConfirmarCodigo.innerHTML;
                btnConfirmarCodigo.disabled = true;
                btnConfirmarCodigo.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    <span>Validando código...</span>
                `;

                otpInputs.forEach(input => input.disabled = true);

                const csrf = getCsrfData();
                const headers = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                };
                if (csrf.token) {
                    headers[csrf.header] = csrf.token;
                }

                try {
                    const response = await fetch(formValidarCodigo.action, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            codigo: codigoCompleto.value
                        })
                    });

                    const data = await response.json().catch(() => null);

                    if (data && data.csrf_token) {
                        atualizarCsrfToken(data.csrf_token);
                    }

                    if (!response.ok || (data && data.success === false)) {
                        const erroBackend = (data && (data.message || data.error))
                            ? (data.message || data.error)
                            : 'Código de verificação incorreto ou expirado.';
                        throw new Error(erroBackend);
                    }

                    // Sucesso!
                    const mensagemSucesso = (data && data.message)
                        ? data.message
                        : 'Código verificado com sucesso! Redirecionando...';
                    mostrarSucesso(mensagemSucesso);

                    btnConfirmarCodigo.innerHTML = `
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span>Código validado!</span>
                    `;
                    btnConfirmarCodigo.style.background = '#198754';

                    const urlDestino = (data && data.redirect)
                        ? data.redirect
                        : '<?= base_url('redefinirsenha') ?>';
                    setTimeout(() => {
                        window.location.href = urlDestino;
                    }, 1200);

                } catch (err) {
                    mostrarErro(err.message || 'Falha ao validar o código. Tente novamente.');

                    otpInputs.forEach(input => {
                        input.disabled = false;
                        input.value = '';
                        input.classList.remove('is-filled');
                        input.classList.add('is-invalid');
                    });
                    codigoCompleto.value = '';

                    const container = document.getElementById('otpContainer');
                    if (container) {
                        container.classList.add('shake');
                        setTimeout(() => container.classList.remove('shake'), 400);
                    }

                    btnConfirmarCodigo.disabled = true;
                    btnConfirmarCodigo.innerHTML = textoOriginalBtn;

                    if (otpInputs.length > 0) {
                        otpInputs[0].focus();
                    }
                }
            });
        });
    </script>
<?= $this->endSection() ?>

