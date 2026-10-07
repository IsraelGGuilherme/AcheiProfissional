<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Completar Perfil - Profissional - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/components/avatar-upload.css') ?>">
    <style>
        .capa-upload-wrapper {
            position: relative;
            width: 100%;
            height: 150px;
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .capa-upload-wrapper:hover {
            border-color: var(--bs-primary);
            background-color: #e9ecef;
        }
        .capa-placeholder {
            text-align: center;
            color: #6c757d;
        }
        .capa-placeholder i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
        }
        .capa-preview-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">

        <div class="container">

            <div class="register-card">

                <a href="<?= base_url('register/profissional') ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>

                <div class="text-center mb-4">

                    <span class="badge-type">
                        <i class="bi bi-briefcase"></i>
                        Profissional / Dados do Perfil
                    </span>

                    <h1 class="welcome-title">
                        Complete o seu perfil
                    </h1>

                    <p class="welcome-text">
                        Preencha as informações do seu negócio para que os clientes possam te encontrar.
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

                <!-- Formulário de Dados do Profissional -->
                <form action="<?= base_url('register/profissional/criarconta') ?>" method="POST" enctype="multipart/form-data" id="formRegisterProfissional" novalidate>
                    <?= csrf_field() ?>

                    <!-- Campo: Foto de Capa -->
                    <div class="mb-4">
                        <label class="form-label">Foto de Capa</label>
                        <input
                            type="file"
                            name="foto_capa"
                            id="foto_capa"
                            accept="image/png, image/jpeg, image/jpg, image/webp"
                            class="d-none">
                        <div class="capa-upload-wrapper" id="capaPreviewBox" title="Clique para escolher uma foto de capa">
                            <div class="capa-placeholder" id="capaPlaceholder">
                                <i class="bi bi-image"></i>
                                <span>Adicionar foto de capa</span>
                            </div>
                            <img src="" alt="Prévia da foto de capa" class="capa-preview-img" id="capaPreviewImg">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="form-helper mb-0">
                                <i class="bi bi-info-circle"></i> JPG, PNG ou WEBP até 2MB (opcional).
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-danger d-none" id="btnRemoveCapa">
                                <i class="bi bi-trash"></i> Remover
                            </button>
                        </div>
                        <div id="fotoCapaFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: Foto de Perfil -->
                    <div class="avatar-upload-wrapper mb-4">
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

                    <!-- Tipo Pessoa -->
                    <div class="mb-3">
                        <label class="form-label">
                            Tipo de Pessoa <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_pessoa" id="tipoPF" value="PF" <?= old('tipo_pessoa', 'PF') === 'PF' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tipoPF">
                                    Pessoa Física (CPF)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_pessoa" id="tipoPJ" value="PJ" <?= old('tipo_pessoa') === 'PJ' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="tipoPJ">
                                    Pessoa Jurídica (CNPJ)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Campo: CPF/CNPJ -->
                    <div class="mb-3">
                        <label for="cpf_cnpj" class="form-label" id="labelCpfCnpj">
                            CPF <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-person-vcard" id="iconCpfCnpj"></i>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="cpf_cnpj"
                                name="cpf_cnpj"
                                placeholder="000.000.000-00"
                                maxlength="18"
                                value="<?= old('cpf_cnpj') ?>"
                                required
                                autocomplete="off">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-shield-lock"></i>
                            Documento necessário para segurança da plataforma.
                        </div>
                        <div id="cpfCnpjFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: Nome / Razão Social -->
                    <div class="mb-3">
                        <label for="nome" class="form-label" id="labelNome">
                            Nome Completo <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="nome"
                                name="nome"
                                placeholder="Digite o nome completo"
                                maxlength="150"
                                value="<?= old('nome') ?>"
                                required
                                autocomplete="name">
                        </div>
                        <div class="form-helper" id="helperNome">
                            <i class="bi bi-info-circle"></i>
                            Nome pelo qual você ou sua empresa será identificado.
                        </div>
                        <div id="nomeFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: Descrição -->
                    <div class="mb-3">
                        <label for="descricao" class="form-label">
                            Descrição do Perfil
                        </label>
                        <textarea
                            class="form-control form-control-custom"
                            id="descricao"
                            name="descricao"
                            rows="4"
                            placeholder="Fale um pouco sobre você, sua experiência e os serviços que oferece..."><?= old('descricao') ?></textarea>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Um bom texto ajuda a atrair mais clientes (opcional).
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Informações de Contato</h5>

                    <!-- Campo: Telefone Contato -->
                    <div class="mb-3">
                        <label for="telefone_contato" class="form-label">
                            Telefone Fixo / Celular
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-telephone"></i>
                            <input
                                type="tel"
                                class="form-control form-control-custom"
                                id="telefone_contato"
                                name="telefone_contato"
                                placeholder="(00) 00000-0000"
                                maxlength="15"
                                value="<?= old('telefone_contato') ?>"
                                autocomplete="tel">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Telefone principal para os clientes entrarem em contato (opcional).
                        </div>
                        <div id="telefoneContatoFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: WhatsApp -->
                    <div class="mb-3">
                        <label for="whatsapp" class="form-label">
                            WhatsApp
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-whatsapp"></i>
                            <input
                                type="tel"
                                class="form-control form-control-custom"
                                id="whatsapp"
                                name="whatsapp"
                                placeholder="(00) 00000-0000"
                                maxlength="15"
                                value="<?= old('whatsapp') ?>">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Apenas números com WhatsApp (opcional).
                        </div>
                        <div id="whatsappFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Campo: Instagram -->
                    <div class="mb-3">
                        <label for="instagram" class="form-label">
                            Instagram
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-instagram"></i>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="instagram"
                                name="instagram"
                                placeholder="@seu_usuario"
                                maxlength="100"
                                value="<?= old('instagram') ?>">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Seu perfil no Instagram (opcional).
                        </div>
                    </div>

                    <!-- Campo: Site -->
                    <div class="mb-4">
                        <label for="site" class="form-label">
                            Site
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-globe"></i>
                            <input
                                type="url"
                                class="form-control form-control-custom"
                                id="site"
                                name="site"
                                placeholder="https://seusite.com.br"
                                maxlength="255"
                                value="<?= old('site') ?>">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Link para seu site ou portfólio (opcional).
                        </div>
                    </div>

                    <!-- Botão de Conclusão -->
                    <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2">
                        <span>Continuar cadastro</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </form>

            </div>

        </div>

    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/core/masks.js') ?>"></script>
    <script src="<?= base_url('assets/js/core/validators.js') ?>"></script>
    <script>
        const form = document.getElementById('formRegisterProfissional');
        const nomeInput = document.getElementById('nome');
        const cpfCnpjInput = document.getElementById('cpf_cnpj');
        const telefoneInput = document.getElementById('telefone_contato');
        const whatsappInput = document.getElementById('whatsapp');
        const fotoPerfilInput = document.getElementById('foto_perfil');
        const fotoCapaInput = document.getElementById('foto_capa');
        
        const tipoPF = document.getElementById('tipoPF');
        const tipoPJ = document.getElementById('tipoPJ');
        const labelCpfCnpj = document.getElementById('labelCpfCnpj');
        const iconCpfCnpj = document.getElementById('iconCpfCnpj');
        const labelNome = document.getElementById('labelNome');

        const avatarPreviewBox = document.getElementById('avatarPreviewBox');
        const avatarPlaceholder = document.getElementById('avatarPlaceholder');
        const avatarPreviewImg = document.getElementById('avatarPreviewImg');
        const btnChoosePhoto = document.getElementById('btnChoosePhoto');
        const btnRemovePhoto = document.getElementById('btnRemovePhoto');

        const capaPreviewBox = document.getElementById('capaPreviewBox');
        const capaPlaceholder = document.getElementById('capaPlaceholder');
        const capaPreviewImg = document.getElementById('capaPreviewImg');
        const btnRemoveCapa = document.getElementById('btnRemoveCapa');

        const nomeFeedback = document.getElementById('nomeFeedback');
        const cpfCnpjFeedback = document.getElementById('cpfCnpjFeedback');
        const telefoneContatoFeedback = document.getElementById('telefoneContatoFeedback');
        const whatsappFeedback = document.getElementById('whatsappFeedback');
        const fotoPerfilFeedback = document.getElementById('fotoPerfilFeedback');
        const fotoCapaFeedback = document.getElementById('fotoCapaFeedback');
        const clientErrorAlert = document.getElementById('clientErrorAlert');

        // Adicionando validadores e máscaras para CNPJ
        // Isso normalmente iria nos arquivos core, mas para evitar alterar muitos arquivos,
        // adicionamos a lógica complementar aqui ou expandimos os objetos existentes.
        
        if (!Masks.cnpj) {
            Masks.cnpj = function(value) {
                let val = value.replace(/\D/g, '');
                if (val.length > 14) val = val.substring(0, 14);
                
                if (val.length > 12) return val.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{1,2})/, '$1.$2.$3/$4-$5');
                if (val.length > 8) return val.replace(/(\d{2})(\d{3})(\d{3})(\d{1,4})/, '$1.$2.$3/$4');
                if (val.length > 5) return val.replace(/(\d{2})(\d{3})(\d{1,3})/, '$1.$2.$3');
                if (val.length > 2) return val.replace(/(\d{2})(\d{1,3})/, '$1.$2');
                return val;
            };
        }

        if (!Validators.cnpj) {
            Validators.cnpj = function(cnpj) {
                const b = [ 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2 ];
                const c = String(cnpj).replace(/[^\d]/g, '');
                
                if(c.length !== 14) return false;
                if(/0{14}/.test(c)) return false;
                
                for (var i = 0, n = 0; i < 12; n += c[i] * b[++i]);
                if(c[12] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
                
                for (var i = 0, n = 0; i <= 12; n += c[i] * b[i++]);
                if(c[13] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
                
                return true;
            };
        }

        // =========================================
        // UPLOAD & PRÉVIA DA FOTO DE PERFIL E CAPA
        // =========================================

        avatarPreviewBox.addEventListener('click', () => fotoPerfilInput.click());
        btnChoosePhoto.addEventListener('click', () => fotoPerfilInput.click());
        btnRemovePhoto.addEventListener('click', () => {
            fotoPerfilInput.value = '';
            avatarPreviewImg.src = '';
            avatarPreviewImg.style.display = 'none';
            avatarPlaceholder.style.display = 'block';
            btnRemovePhoto.classList.add('d-none');
            fotoPerfilFeedback.className = 'field-feedback';
            fotoPerfilFeedback.innerHTML = '';
        });

        fotoPerfilInput.addEventListener('change', (e) => handleImageUpload(e, fotoPerfilInput, avatarPreviewImg, avatarPlaceholder, btnRemovePhoto, fotoPerfilFeedback));

        capaPreviewBox.addEventListener('click', () => fotoCapaInput.click());
        btnRemoveCapa.addEventListener('click', () => {
            fotoCapaInput.value = '';
            capaPreviewImg.src = '';
            capaPreviewImg.style.display = 'none';
            capaPlaceholder.style.display = 'block';
            btnRemoveCapa.classList.add('d-none');
            fotoCapaFeedback.className = 'field-feedback';
            fotoCapaFeedback.innerHTML = '';
        });

        fotoCapaInput.addEventListener('change', (e) => handleImageUpload(e, fotoCapaInput, capaPreviewImg, capaPlaceholder, btnRemoveCapa, fotoCapaFeedback));

        function handleImageUpload(e, inputEl, previewImgEl, placeholderEl, removeBtnEl, feedbackEl) {
            const file = e.target.files[0];
            if (!file) return;

            const maxSizeInBytes = 2 * 1024 * 1024;
            if (file.size > maxSizeInBytes) {
                feedbackEl.className = 'field-feedback show-error';
                feedbackEl.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> A foto selecionada excede o limite de 2MB.';
                inputEl.value = '';
                return;
            }

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                feedbackEl.className = 'field-feedback show-error';
                feedbackEl.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Escolha um arquivo de imagem válido (JPG, PNG ou WEBP).';
                inputEl.value = '';
                return;
            }

            feedbackEl.className = 'field-feedback';
            feedbackEl.innerHTML = '';

            const reader = new FileReader();
            reader.onload = (event) => {
                previewImgEl.src = event.target.result;
                previewImgEl.style.display = 'block';
                placeholderEl.style.display = 'none';
                removeBtnEl.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }

        // =========================================
        // MÁSCARAS DE ENTRADA E TROCA TIPO PESSOA
        // =========================================

        function updateCpfCnpjView() {
            const isPF = tipoPF.checked;
            
            labelCpfCnpj.innerHTML = isPF ? 'CPF <span class="text-danger">*</span>' : 'CNPJ <span class="text-danger">*</span>';
            cpfCnpjInput.placeholder = isPF ? '000.000.000-00' : '00.000.000/0000-00';
            iconCpfCnpj.className = isPF ? 'bi bi-person-vcard' : 'bi bi-building';
            
            labelNome.innerHTML = isPF ? 'Nome Completo <span class="text-danger">*</span>' : 'Razão Social / Nome Fantasia <span class="text-danger">*</span>';
            
            // Re-aplicar máscara no valor atual
            cpfCnpjInput.value = isPF ? Masks.cpf(cpfCnpjInput.value) : Masks.cnpj(cpfCnpjInput.value);
            
            validateCpfCnpj();
        }

        tipoPF.addEventListener('change', updateCpfCnpjView);
        tipoPJ.addEventListener('change', updateCpfCnpjView);

        cpfCnpjInput.addEventListener('input', (e) => {
            if (tipoPF.checked) {
                e.target.value = Masks.cpf(e.target.value);
            } else {
                e.target.value = Masks.cnpj(e.target.value);
            }
            validateCpfCnpj();
        });

        if (telefoneInput.value) telefoneInput.value = Masks.phone(telefoneInput.value);
        if (whatsappInput.value) whatsappInput.value = Masks.phone(whatsappInput.value);

        Masks.bind(telefoneInput, 'phone', () => validateTelefone(telefoneInput, telefoneContatoFeedback));
        Masks.bind(whatsappInput, 'phone', () => validateTelefone(whatsappInput, whatsappFeedback));

        // Inicializar view corretamente
        updateCpfCnpjView();

        // =========================================
        // VALIDAÇÕES
        // =========================================

        function validateNome() {
            const val = nomeInput.value.trim();
            const isPF = tipoPF.checked;

            if (val.length === 0) {
                nomeInput.classList.remove('is-invalid', 'is-valid');
                nomeFeedback.className = 'field-feedback';
                nomeFeedback.innerHTML = '';
                return false;
            }

            if (isPF && !Validators.fullName(val)) {
                nomeInput.classList.add('is-invalid');
                nomeInput.classList.remove('is-valid');
                nomeFeedback.className = 'field-feedback show-error';
                nomeFeedback.innerHTML = val.length < 3
                    ? '<i class="bi bi-x-circle-fill me-1"></i> O nome deve ter pelo menos 3 caracteres.'
                    : '<i class="bi bi-x-circle-fill me-1"></i> Por favor, informe nome e sobrenome.';
                return false;
            } else if (!isPF && val.length < 3) {
                nomeInput.classList.add('is-invalid');
                nomeInput.classList.remove('is-valid');
                nomeFeedback.className = 'field-feedback show-error';
                nomeFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> O nome da empresa deve ter pelo menos 3 caracteres.';
                return false;
            }

            nomeInput.classList.remove('is-invalid');
            nomeInput.classList.add('is-valid');
            nomeFeedback.className = 'field-feedback show-success';
            nomeFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Nome válido.';
            return true;
        }

        nomeInput.addEventListener('input', validateNome);

        function validateCpfCnpj() {
            const raw = Masks.clean(cpfCnpjInput.value);
            const isPF = tipoPF.checked;

            if (raw.length === 0) {
                cpfCnpjInput.classList.remove('is-invalid', 'is-valid');
                cpfCnpjFeedback.className = 'field-feedback';
                cpfCnpjFeedback.innerHTML = '';
                return false;
            }

            let isValid = false;
            let errorMsg = '';

            if (isPF) {
                isValid = raw.length === 11 && Validators.cpf(raw);
                errorMsg = raw.length < 11 ? 'O CPF deve conter 11 dígitos.' : 'O CPF digitado é inválido.';
            } else {
                isValid = raw.length === 14 && Validators.cnpj(raw);
                errorMsg = raw.length < 14 ? 'O CNPJ deve conter 14 dígitos.' : 'O CNPJ digitado é inválido.';
            }

            if (!isValid) {
                cpfCnpjInput.classList.add('is-invalid');
                cpfCnpjInput.classList.remove('is-valid');
                cpfCnpjFeedback.className = 'field-feedback show-error';
                cpfCnpjFeedback.innerHTML = `<i class="bi bi-x-circle-fill me-1"></i> ${errorMsg}`;
                return false;
            }

            cpfCnpjInput.classList.remove('is-invalid');
            cpfCnpjInput.classList.add('is-valid');
            cpfCnpjFeedback.className = 'field-feedback show-success';
            cpfCnpjFeedback.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> ${isPF ? 'CPF' : 'CNPJ'} válido.`;
            return true;
        }

        function validateTelefone(inputEl, feedbackEl) {
            const raw = Masks.clean(inputEl.value);

            if (raw.length === 0) {
                inputEl.classList.remove('is-invalid', 'is-valid');
                feedbackEl.className = 'field-feedback';
                feedbackEl.innerHTML = '';
                return true;
            }

            if (!Validators.phone(raw)) {
                inputEl.classList.add('is-invalid');
                inputEl.classList.remove('is-valid');
                feedbackEl.className = 'field-feedback show-error';
                feedbackEl.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Informe um número válido com DDD.';
                return false;
            }

            inputEl.classList.remove('is-invalid');
            inputEl.classList.add('is-valid');
            feedbackEl.className = 'field-feedback show-success';
            feedbackEl.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Telefone válido.';
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
                errorMessages.push(!nomeInput.value.trim() ? 'O campo Nome / Razão Social é obrigatório.' : 'O nome informado é inválido.');
            }

            const isCpfCnpjOk = validateCpfCnpj();
            if (!isCpfCnpjOk) {
                hasError = true;
                const isPF = tipoPF.checked;
                errorMessages.push(!cpfCnpjInput.value.trim() ? `O campo ${isPF ? 'CPF' : 'CNPJ'} é obrigatório.` : `O ${isPF ? 'CPF' : 'CNPJ'} informado é inválido.`);
            }

            const isTelefoneOk = validateTelefone(telefoneInput, telefoneContatoFeedback);
            if (!isTelefoneOk) {
                hasError = true;
                errorMessages.push('O número de telefone de contato está inválido.');
            }

            const isWhatsappOk = validateTelefone(whatsappInput, whatsappFeedback);
            if (!isWhatsappOk) {
                hasError = true;
                errorMessages.push('O número de WhatsApp está inválido.');
            }

            if (hasError) {
                e.preventDefault();
                // Assumindo que a função AuthUI.showErrors está definida globalmente
                if (typeof AuthUI !== 'undefined') {
                    AuthUI.showErrors(clientErrorAlert, errorMessages);
                } else {
                    clientErrorAlert.innerHTML = errorMessages.join('<br>');
                    clientErrorAlert.classList.remove('d-none');
                }

                if (!isCpfCnpjOk) {
                    cpfCnpjInput.focus();
                } else if (!isNomeOk) {
                    nomeInput.focus();
                } else if (!isTelefoneOk) {
                    telefoneInput.focus();
                } else if (!isWhatsappOk) {
                    whatsappInput.focus();
                }
            } else {
                if (typeof AuthUI !== 'undefined') AuthUI.clearErrors(clientErrorAlert);
                cpfCnpjInput.value = Masks.clean(cpfCnpjInput.value);
                if (telefoneInput.value) telefoneInput.value = Masks.clean(telefoneInput.value);
                if (whatsappInput.value) whatsappInput.value = Masks.clean(whatsappInput.value);
            }
        });
    </script>
<?= $this->endSection() ?>
