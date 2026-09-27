<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Completar Perfil - Usuário Comum - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/components/avatar-upload.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">

        <div class="container">

            <div class="register-card">

                <a href="<?= base_url('register/usuario') ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>

                <div class="text-center mb-4">

                    <span class="badge-type">
                        <i class="bi bi-person-check"></i>
                        Usuário Comum / Dados Pessoais
                    </span>

                    <h1 class="welcome-title">
                        Complete seus dados
                    </h1>

                    <p class="welcome-text">
                        Preencha suas informações pessoais para concluir o seu perfil.
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

                <!-- Formulário de Dados do Usuário Comum -->
                <form action="<?= base_url('register/usuariocomum/criarconta') ?>" method="POST" enctype="multipart/form-data" id="formRegisterUsuarioComum" novalidate>
                    <?= csrf_field() ?>

                    <!-- Campo: Foto de Perfil -->
                    <div class="avatar-upload-wrapper">
                        <input
                            type="file"
                            name="foto_perfil"
                            id="foto_perfil"
                            accept="image/png, image/jpeg, image/jpg, image/webp"
                            class="d-none">

                        <div class="position-relative">
                            <div class="avatar-preview-box" id="avatarPreviewBox" title="Clique para escolher uma foto de perfil">
                                <i class="bi bi-person avatar-placeholder-icon" id="avatarPlaceholder"></i>
                                <img src="" alt="Prévia da foto de perfil" class="avatar-preview-img" id="avatarPreviewImg">
                            </div>
                            <div class="avatar-overlay-badge">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                        </div>

                        <div class="avatar-actions">
                            <button type="button" class="avatar-action-btn" id="btnChoosePhoto">
                                <i class="bi bi-upload"></i> Escolher foto
                            </button>
                            <button type="button" class="avatar-action-btn btn-remove d-none" id="btnRemovePhoto">
                                <i class="bi bi-trash"></i> Remover
                            </button>
                        </div>

                        <span class="avatar-helper-text">
                            JPG, PNG ou WEBP até 2MB (opcional)
                        </span>
                        <div id="fotoPerfilFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: Nome Completo (tabela usuarios_comuns) -->
                    <div class="mb-3">
                        <label for="nome" class="form-label">
                            Nome Completo <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="nome"
                                name="nome"
                                placeholder="Digite seu nome completo"
                                maxlength="150"
                                value="<?= old('nome') ?>"
                                required
                                autocomplete="name">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Como você deseja ser identificado na plataforma.
                        </div>
                        <div id="nomeFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: CPF (tabela usuarios_comuns) -->
                    <div class="mb-3">
                        <label for="cpf" class="form-label">
                            CPF <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-person-vcard"></i>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="cpf"
                                name="cpf"
                                placeholder="000.000.000-00"
                                maxlength="14"
                                value="<?= old('cpf') ?>"
                                required
                                autocomplete="off">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-shield-lock"></i>
                            Seu CPF será mantido em sigilo e segurança.
                        </div>
                        <div id="cpfFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: Telefone (tabela usuarios_comuns) -->
                    <div class="mb-4">
                        <label for="telefone" class="form-label">
                            Telefone / WhatsApp
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-telephone"></i>
                            <input
                                type="tel"
                                class="form-control form-control-custom"
                                id="telefone"
                                name="telefone"
                                placeholder="(00) 00000-0000"
                                maxlength="15"
                                value="<?= old('telefone') ?>"
                                autocomplete="tel">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Telefone celular para contato rápido (opcional).
                        </div>
                        <div id="telefoneFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Botão de Conclusão -->
                    <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2">
                        <span>Finalizar cadastro</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

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
                                Seus dados estão protegidos com total privacidade.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <div class="benefit-title">
                                Busca facilitada
                            </div>
                            <p class="benefit-text">
                                Encontre prestadores por categoria e localização.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="benefit-title">
                                Avaliações reais
                            </div>
                            <p class="benefit-text">
                                Consulte notas e depoimentos de outros clientes.
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
    <script src="<?= base_url('assets/js/core/masks.js') ?>"></script>
    <script src="<?= base_url('assets/js/core/validators.js') ?>"></script>
    <script>
        const form = document.getElementById('formRegisterUsuarioComum');
        const nomeInput = document.getElementById('nome');
        const cpfInput = document.getElementById('cpf');
        const telefoneInput = document.getElementById('telefone');
        const fotoPerfilInput = document.getElementById('foto_perfil');

        const avatarPreviewBox = document.getElementById('avatarPreviewBox');
        const avatarPlaceholder = document.getElementById('avatarPlaceholder');
        const avatarPreviewImg = document.getElementById('avatarPreviewImg');
        const btnChoosePhoto = document.getElementById('btnChoosePhoto');
        const btnRemovePhoto = document.getElementById('btnRemovePhoto');

        const nomeFeedback = document.getElementById('nomeFeedback');
        const cpfFeedback = document.getElementById('cpfFeedback');
        const telefoneFeedback = document.getElementById('telefoneFeedback');
        const fotoPerfilFeedback = document.getElementById('fotoPerfilFeedback');
        const clientErrorAlert = document.getElementById('clientErrorAlert');

        // =========================================
        // UPLOAD & PRÉVIA DA FOTO DE PERFIL
        // =========================================

        avatarPreviewBox.addEventListener('click', () => fotoPerfilInput.click());
        btnChoosePhoto.addEventListener('click', () => fotoPerfilInput.click());
        btnRemovePhoto.addEventListener('click', resetAvatar);

        function resetAvatar() {
            fotoPerfilInput.value = '';
            avatarPreviewImg.src = '';
            avatarPreviewImg.style.display = 'none';
            avatarPlaceholder.style.display = 'block';
            btnRemovePhoto.classList.add('d-none');
            fotoPerfilFeedback.className = 'field-feedback';
            fotoPerfilFeedback.innerHTML = '';
        }

        fotoPerfilInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Validar tamanho (máx 2MB)
            const maxSizeInBytes = 2 * 1024 * 1024;
            if (file.size > maxSizeInBytes) {
                fotoPerfilFeedback.className = 'field-feedback show-error';
                fotoPerfilFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> A foto selecionada excede o limite de 2MB.';
                fotoPerfilInput.value = '';
                return;
            }

            // Validar tipo MIME
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                fotoPerfilFeedback.className = 'field-feedback show-error';
                fotoPerfilFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Escolha um arquivo de imagem válido (JPG, PNG ou WEBP).';
                fotoPerfilInput.value = '';
                return;
            }

            fotoPerfilFeedback.className = 'field-feedback';
            fotoPerfilFeedback.innerHTML = '';

            const reader = new FileReader();
            reader.onload = (event) => {
                avatarPreviewImg.src = event.target.result;
                avatarPreviewImg.style.display = 'block';
                avatarPlaceholder.style.display = 'none';
                btnRemovePhoto.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });

        // =========================================
        // MÁSCARAS DE ENTRADA (masks.js)
        // =========================================

        if (cpfInput.value) {
            cpfInput.value = Masks.cpf(cpfInput.value);
        }

        if (telefoneInput.value) {
            telefoneInput.value = Masks.phone(telefoneInput.value);
        }

        Masks.bind(cpfInput, 'cpf', () => validateCpf());
        Masks.bind(telefoneInput, 'phone', () => validateTelefone());

        // =========================================
        // VALIDAÇÕES (validators.js)
        // =========================================

        // Validação de Nome
        function validateNome() {
            const val = nomeInput.value.trim();

            if (val.length === 0) {
                nomeInput.classList.remove('is-invalid', 'is-valid');
                nomeFeedback.className = 'field-feedback';
                nomeFeedback.innerHTML = '';
                return false;
            }

            if (!Validators.fullName(val)) {
                nomeInput.classList.add('is-invalid');
                nomeInput.classList.remove('is-valid');
                nomeFeedback.className = 'field-feedback show-error';
                nomeFeedback.innerHTML = val.length < 3
                    ? '<i class="bi bi-x-circle-fill me-1"></i> O nome deve ter pelo menos 3 caracteres.'
                    : '<i class="bi bi-x-circle-fill me-1"></i> Por favor, informe nome e sobrenome.';
                return false;
            }

            nomeInput.classList.remove('is-invalid');
            nomeInput.classList.add('is-valid');
            nomeFeedback.className = 'field-feedback show-success';
            nomeFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Nome válido.';
            return true;
        }

        nomeInput.addEventListener('input', validateNome);

        // Validação de CPF
        function validateCpf() {
            const raw = Masks.clean(cpfInput.value);

            if (raw.length === 0) {
                cpfInput.classList.remove('is-invalid', 'is-valid');
                cpfFeedback.className = 'field-feedback';
                cpfFeedback.innerHTML = '';
                return false;
            }

            if (raw.length < 11 || !Validators.cpf(raw)) {
                cpfInput.classList.add('is-invalid');
                cpfInput.classList.remove('is-valid');
                cpfFeedback.className = 'field-feedback show-error';
                cpfFeedback.innerHTML = raw.length < 11
                    ? '<i class="bi bi-x-circle-fill me-1"></i> O CPF deve conter 11 dígitos.'
                    : '<i class="bi bi-x-circle-fill me-1"></i> O CPF digitado é inválido.';
                return false;
            }

            cpfInput.classList.remove('is-invalid');
            cpfInput.classList.add('is-valid');
            cpfFeedback.className = 'field-feedback show-success';
            cpfFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> CPF válido.';
            return true;
        }

        // Validação de Telefone
        function validateTelefone() {
            const raw = Masks.clean(telefoneInput.value);

            if (raw.length === 0) {
                telefoneInput.classList.remove('is-invalid', 'is-valid');
                telefoneFeedback.className = 'field-feedback';
                telefoneFeedback.innerHTML = '';
                return true;
            }

            if (!Validators.phone(raw)) {
                telefoneInput.classList.add('is-invalid');
                telefoneInput.classList.remove('is-valid');
                telefoneFeedback.className = 'field-feedback show-error';
                telefoneFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Informe um número de telefone válido com DDD (10 ou 11 dígitos).';
                return false;
            }

            telefoneInput.classList.remove('is-invalid');
            telefoneInput.classList.add('is-valid');
            telefoneFeedback.className = 'field-feedback show-success';
            telefoneFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Telefone válido.';
            return true;
        }

        // =========================================
        // SUBMIT DO FORMULÁRIO
        // =========================================

        form.addEventListener('submit', (e) => {
            let hasError = false;
            let errorMessages = [];

            const isNomeOk = validateNome();
            if (!isNomeOk) {
                hasError = true;
                errorMessages.push(!nomeInput.value.trim() ? 'O campo Nome Completo é obrigatório.' : 'Por favor, informe seu nome completo com sobrenome.');
            }

            const isCpfOk = validateCpf();
            if (!isCpfOk) {
                hasError = true;
                errorMessages.push(!cpfInput.value.trim() ? 'O campo CPF é obrigatório.' : 'O CPF informado é inválido.');
            }

            const isTelefoneOk = validateTelefone();
            if (!isTelefoneOk) {
                hasError = true;
                errorMessages.push('O número de telefone informado está incompleto.');
            }

            if (hasError) {
                e.preventDefault();
                AuthUI.showErrors(clientErrorAlert, errorMessages);

                // Rolar até o topo do formulário ou dar foco no primeiro campo com erro
                if (!isNomeOk) {
                    nomeInput.focus();
                } else if (!isCpfOk) {
                    cpfInput.focus();
                } else if (!isTelefoneOk) {
                    telefoneInput.focus();
                }
            } else {
                AuthUI.clearErrors(clientErrorAlert);
                cpfInput.value = Masks.clean(cpfInput.value);
                if (telefoneInput.value) {
                    telefoneInput.value = Masks.clean(telefoneInput.value);
                }
            }
        });
    </script>
<?= $this->endSection() ?>

