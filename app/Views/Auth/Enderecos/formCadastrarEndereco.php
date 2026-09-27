<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Cadastrar Endereço - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .register-card-lg {
        max-width: 640px !important;
    }

    .form-select.form-control-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23718096' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px 12px;
        padding-right: 40px;
    }

    .select-loading {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%230876d1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M21 12a9 9 0 1 1-6.219-8.56'/%3e%3c/svg%3e") !important;
        animation: spin 1s linear infinite;
        background-size: 18px 18px !important;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">

        <div class="container">

            <div class="register-card register-card-lg">

                <a href="<?= base_url('usuariocomum/criarconta') ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Voltar
                </a>

                <div class="text-center mb-4">

                    <span class="badge-type">
                        <i class="bi bi-geo-alt"></i>
                        Localização / Endereço
                    </span>

                    <h1 class="welcome-title">
                        Onde você está localizado?
                    </h1>

                    <p class="welcome-text">
                        Informe o seu endereço para que possamos conectar você a profissionais e oportunidades na sua região.
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

                <!-- Formulário de Cadastro de Endereço -->
                <form action="<?= base_url('cadastrarendereco') ?>" method="POST" id="formCadastrarEndereco" novalidate>
                    <?= csrf_field() ?>

                    <!-- Linha 1: País e Estado -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="pais_id" class="form-label">
                                País <span class="text-danger">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-globe-americas"></i>
                                <select
                                    class="form-select form-control-custom"
                                    id="pais_id"
                                    name="pais_id"
                                    required>
                                    <option value="">Selecione o país</option>
                                    <?php if (!empty($paises)): ?>
                                        <?php foreach ($paises as $pais): ?>
                                            <option value="<?= $pais->id ?>" <?= old('pais_id') == $pais->id ? 'selected' : '' ?>>
                                                <?= esc($pais->nome) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div id="paisFeedback" class="field-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="estado_id" class="form-label">
                                Estado (UF) <span class="text-danger">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-map"></i>
                                <select
                                    class="form-select form-control-custom"
                                    id="estado_id"
                                    name="estado_id"
                                    disabled
                                    required>
                                    <option value="">Selecione o país primeiro</option>
                                </select>
                            </div>
                            <div id="estadoFeedback" class="field-feedback"></div>
                        </div>
                    </div>

                    <!-- Linha 2: Cidade e Bairro -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="cidade_id" class="form-label">
                                Cidade <span class="text-danger">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-buildings"></i>
                                <select
                                    class="form-select form-control-custom"
                                    id="cidade_id"
                                    name="cidade_id"
                                    disabled
                                    required>
                                    <option value="">Selecione o estado primeiro</option>
                                </select>
                            </div>
                            <div id="cidadeFeedback" class="field-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="bairro_id" class="form-label">
                                Bairro <span class="text-danger">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-houses"></i>
                                <select
                                    class="form-select form-control-custom"
                                    id="bairro_id"
                                    name="bairro_id"
                                    disabled
                                    required>
                                    <option value="">Selecione a cidade primeiro</option>
                                </select>
                            </div>
                            <div id="bairroFeedback" class="field-feedback"></div>
                        </div>
                    </div>

                    <!-- Linha Dinâmica: Bairro Outro (Caso bairro não esteja na lista) -->
                    <div class="mb-3 d-none" id="containerBairroOutro">
                        <label for="bairro_outro" class="form-label">
                            Nome do Bairro <span class="text-danger">*</span>
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-pencil"></i>
                            <input
                                type="text"
                                class="form-control form-control-custom"
                                id="bairro_outro"
                                name="bairro_outro"
                                placeholder="Digite o nome do seu bairro"
                                maxlength="150"
                                value="<?= old('bairro_outro') ?>">
                        </div>
                        <div class="form-helper">
                            <i class="bi bi-info-circle"></i>
                            Informe o nome do seu bairro caso ele não conste na lista acima.
                        </div>
                        <div id="bairroOutroFeedback" class="field-feedback"></div>
                    </div>

                    <!-- Linha 3: CEP e Logradouro -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="cep" class="form-label">
                                CEP <span class="text-danger">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-pin-map"></i>
                                <input
                                    type="text"
                                    class="form-control form-control-custom"
                                    id="cep"
                                    name="cep"
                                    placeholder="00000-000"
                                    maxlength="9"
                                    value="<?= old('cep') ?>"
                                    required
                                    autocomplete="postal-code">
                            </div>
                            <div id="cepFeedback" class="field-feedback"></div>
                        </div>

                        <div class="col-md-8">
                            <label for="logradouro" class="form-label">
                                Logradouro (Rua, Avenida, etc.) <span class="text-danger">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-signpost"></i>
                                <input
                                    type="text"
                                    class="form-control form-control-custom"
                                    id="logradouro"
                                    name="logradouro"
                                    placeholder="Ex: Rua das Flores, Av. Brasil"
                                    maxlength="255"
                                    value="<?= old('logradouro') ?>"
                                    required
                                    autocomplete="street-address">
                            </div>
                            <div id="logradouroFeedback" class="field-feedback"></div>
                        </div>
                    </div>

                    <!-- Linha 4: Número e Complemento -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="numero" class="form-label">
                                Número <span class="text-danger">*</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-hash"></i>
                                <input
                                    type="text"
                                    class="form-control form-control-custom"
                                    id="numero"
                                    name="numero"
                                    placeholder="Ex: 123 ou S/N"
                                    maxlength="20"
                                    value="<?= old('numero') ?>"
                                    required>
                            </div>
                            <div id="numeroFeedback" class="field-feedback"></div>
                        </div>

                        <div class="col-md-8">
                            <label for="complemento" class="form-label">
                                Complemento <span class="text-muted fw-normal">(Opcional)</span>
                            </label>
                            <div class="input-icon">
                                <i class="bi bi-info-circle"></i>
                                <input
                                    type="text"
                                    class="form-control form-control-custom"
                                    id="complemento"
                                    name="complemento"
                                    placeholder="Ex: Apto 101, Bloco B, Fundos"
                                    maxlength="255"
                                    value="<?= old('complemento') ?>">
                            </div>
                            <div id="complementoFeedback" class="field-feedback"></div>
                        </div>
                    </div>

                    <!-- Botão de Finalização -->
                    <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2" id="btnSubmit">
                        <span>Concluir cadastro</span>
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
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="benefit-title">
                                Resultados próximos
                            </div>
                            <p class="benefit-text">
                                Localize profissionais autônomos que realmente atendem na sua localidade.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="benefit-title">
                                Dados protegidos
                            </div>
                            <p class="benefit-text">
                                Seu endereço exato não é exibido publicamente sem sua autorização.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="benefit">
                            <div class="benefit-icon">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <div class="benefit-title">
                                Cadastro completo
                            </div>
                            <p class="benefit-text">
                                Com o perfil completo, sua conta fica 100% pronta para uso imediato.
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('formCadastrarEndereco');
            const paisSelect = document.getElementById('pais_id');
            const estadoSelect = document.getElementById('estado_id');
            const cidadeSelect = document.getElementById('cidade_id');
            const bairroSelect = document.getElementById('bairro_id');
            const containerBairroOutro = document.getElementById('containerBairroOutro');
            const bairroOutroInput = document.getElementById('bairro_outro');
            const cepInput = document.getElementById('cep');
            const logradouroInput = document.getElementById('logradouro');
            const numeroInput = document.getElementById('numero');
            const complementoInput = document.getElementById('complemento');
            const clientErrorAlert = document.getElementById('clientErrorAlert');

            // URL base limpa para requisições
            const baseUrl = '<?= rtrim(base_url(), '/') ?>';

            // Dados prévios para restauração de estado após erro de validação do backend
            const oldValues = {
                paisId: '<?= old('pais_id') ?>',
                estadoId: '<?= old('estado_id') ?>',
                cidadeId: '<?= old('cidade_id') ?>',
                bairroId: '<?= old('bairro_id') ?>',
                bairroOutro: '<?= addslashes(old('bairro_outro') ?? '') ?>'
            };

            // =========================================
            // MÁSCARA DE CEP (00000-000)
            // =========================================
            function formatCep(val) {
                let clean = (val || '').replace(/\D/g, '');
                if (clean.length > 8) {
                    clean = clean.substring(0, 8);
                }
                if (clean.length > 5) {
                    return clean.replace(/^(\d{5})(\d{1,3})/, '$1-$2');
                }
                return clean;
            }

            if (cepInput.value) {
                cepInput.value = formatCep(cepInput.value);
            }

            cepInput.addEventListener('input', (e) => {
                e.target.value = formatCep(e.target.value);
            });

            // =========================================
            // REQUISIÇÕES AJAX DE LOCALIDADES FILHAS
            // =========================================
            async function fetchFilhos(paiId) {
                const response = await fetch(`${baseUrl}/enderecos/filhos/${paiId}`);
                if (!response.ok) {
                    throw new Error(`Falha na requisição: ${response.status}`);
                }
                return await response.json();
            }

            // =========================================
            // EVENTOS DE MUDANÇA EM CASCATA
            // =========================================

            // 1. País alterado -> Carrega Estados
            paisSelect.addEventListener('change', async () => {
                const paisId = paisSelect.value;

                resetSelect(estadoSelect, 'Selecione o país primeiro');
                resetSelect(cidadeSelect, 'Selecione o estado primeiro');
                resetSelect(bairroSelect, 'Selecione a cidade primeiro');
                hideBairroOutro();

                if (!paisId) return;

                estadoSelect.innerHTML = '<option value="">Carregando estados...</option>';
                estadoSelect.disabled = true;

                try {
                    const estados = await fetchFilhos(paisId);
                    populateSelect(estadoSelect, estados, 'Selecione o estado', true);
                    estadoSelect.disabled = false;
                } catch (error) {
                    console.error('Erro ao carregar estados:', error);
                    resetSelect(estadoSelect, 'Erro ao carregar estados. Tente novamente');
                }
            });

            // 2. Estado alterado -> Carrega Cidades
            estadoSelect.addEventListener('change', async () => {
                const estadoId = estadoSelect.value;

                resetSelect(cidadeSelect, 'Selecione o estado primeiro');
                resetSelect(bairroSelect, 'Selecione a cidade primeiro');
                hideBairroOutro();

                if (!estadoId) return;

                cidadeSelect.innerHTML = '<option value="">Carregando cidades...</option>';
                cidadeSelect.disabled = true;

                try {
                    const cidades = await fetchFilhos(estadoId);
                    populateSelect(cidadeSelect, cidades, 'Selecione a cidade', false);
                    cidadeSelect.disabled = false;
                } catch (error) {
                    console.error('Erro ao carregar cidades:', error);
                    resetSelect(cidadeSelect, 'Erro ao carregar cidades. Tente novamente');
                }
            });

            // 3. Cidade alterada -> Carrega Bairros
            cidadeSelect.addEventListener('change', async () => {
                const cidadeId = cidadeSelect.value;

                resetSelect(bairroSelect, 'Selecione a cidade primeiro');
                hideBairroOutro();

                if (!cidadeId) return;

                bairroSelect.innerHTML = '<option value="">Carregando bairros...</option>';
                bairroSelect.disabled = true;

                try {
                    const bairros = await fetchFilhos(cidadeId);
                    populateSelect(bairroSelect, bairros, 'Selecione o bairro', false);

                    // Opção "Outro" obrigatória conforme requisito
                    const outroOption = document.createElement('option');
                    outroOption.value = 'outro';
                    outroOption.textContent = 'Outro (meu bairro não está na lista)';
                    bairroSelect.appendChild(outroOption);

                    bairroSelect.disabled = false;

                    // Se não houver bairros retornados para esta cidade, seleciona "Outro" automaticamente
                    if (!bairros || bairros.length === 0) {
                        bairroSelect.value = 'outro';
                        showBairroOutro();
                    }
                } catch (error) {
                    console.error('Erro ao carregar bairros:', error);
                    resetSelect(bairroSelect, 'Erro ao carregar bairros. Tente novamente');
                }
            });

            // 4. Bairro alterado -> Exibe/Oculta campo "bairro_outro"
            bairroSelect.addEventListener('change', () => {
                if (bairroSelect.value === 'outro') {
                    showBairroOutro();
                } else {
                    hideBairroOutro();
                }
            });

            // Helpers de controle do campo Bairro Outro
            function showBairroOutro() {
                containerBairroOutro.classList.remove('d-none');
                bairroOutroInput.required = true;
                bairroOutroInput.focus();
            }

            function hideBairroOutro() {
                containerBairroOutro.classList.add('d-none');
                bairroOutroInput.required = false;
                bairroOutroInput.value = '';
                bairroOutroInput.classList.remove('is-invalid', 'is-valid');
                const feedback = document.getElementById('bairroOutroFeedback');
                if (feedback) {
                    feedback.className = 'field-feedback';
                    feedback.innerHTML = '';
                }
            }

            function resetSelect(select, placeholder) {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                select.disabled = true;
                select.classList.remove('is-invalid', 'is-valid');
            }

            function populateSelect(select, items, placeholder, showSigla = false) {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                if (Array.isArray(items)) {
                    items.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = (showSigla && item.sigla)
                            ? `${item.nome} (${item.sigla})`
                            : item.nome;
                        select.appendChild(opt);
                    });
                }
            }

            // =========================================
            // RESTAURAÇÃO DE VALORES (OLD INPUT)
            // =========================================
            async function restaurarValoresAntigos() {
                if (!oldValues.paisId) return;

                try {
                    paisSelect.value = oldValues.paisId;
                    const estados = await fetchFilhos(oldValues.paisId);
                    populateSelect(estadoSelect, estados, 'Selecione o estado', true);
                    estadoSelect.disabled = false;

                    if (!oldValues.estadoId) return;
                    estadoSelect.value = oldValues.estadoId;

                    const cidades = await fetchFilhos(oldValues.estadoId);
                    populateSelect(cidadeSelect, cidades, 'Selecione a cidade', false);
                    cidadeSelect.disabled = false;

                    if (!oldValues.cidadeId) return;
                    cidadeSelect.value = oldValues.cidadeId;

                    const bairros = await fetchFilhos(oldValues.cidadeId);
                    populateSelect(bairroSelect, bairros, 'Selecione o bairro', false);

                    const outroOption = document.createElement('option');
                    outroOption.value = 'outro';
                    outroOption.textContent = 'Outro (meu bairro não está na lista)';
                    bairroSelect.appendChild(outroOption);
                    bairroSelect.disabled = false;

                    if (oldValues.bairroId) {
                        bairroSelect.value = oldValues.bairroId;
                    } else if (oldValues.bairroOutro) {
                        bairroSelect.value = 'outro';
                        showBairroOutro();
                        bairroOutroInput.value = oldValues.bairroOutro;
                    }
                } catch (e) {
                    console.error('Erro ao restaurar cascata de endereços:', e);
                }
            }

            restaurarValoresAntigos();

            // =========================================
            // VALIDAÇÃO E SUBMISSÃO DO FORMULÁRIO
            // =========================================
            form.addEventListener('submit', (e) => {
                let hasError = false;
                const errorMessages = [];

                // 1. País
                if (!paisSelect.value) {
                    hasError = true;
                    paisSelect.classList.add('is-invalid');
                    errorMessages.push('Por favor, selecione um país.');
                } else {
                    paisSelect.classList.remove('is-invalid');
                }

                // 2. Estado
                if (!estadoSelect.value) {
                    hasError = true;
                    estadoSelect.classList.add('is-invalid');
                    errorMessages.push('Por favor, selecione um estado.');
                } else {
                    estadoSelect.classList.remove('is-invalid');
                }

                // 3. Cidade
                if (!cidadeSelect.value) {
                    hasError = true;
                    cidadeSelect.classList.add('is-invalid');
                    errorMessages.push('Por favor, selecione uma cidade.');
                } else {
                    cidadeSelect.classList.remove('is-invalid');
                }

                // 4. Bairro
                if (!bairroSelect.value) {
                    hasError = true;
                    bairroSelect.classList.add('is-invalid');
                    errorMessages.push('Por favor, selecione um bairro ou escolha "Outro".');
                } else {
                    bairroSelect.classList.remove('is-invalid');
                }

                // 5. Bairro Outro
                if (bairroSelect.value === 'outro') {
                    const bairroOutroVal = bairroOutroInput.value.trim();
                    if (!bairroOutroVal) {
                        hasError = true;
                        bairroOutroInput.classList.add('is-invalid');
                        errorMessages.push('Você selecionou a opção "Outro". Por favor, digite o nome do bairro.');
                    } else {
                        bairroOutroInput.classList.remove('is-invalid');
                    }
                }

                // 6. CEP
                const cleanCep = (cepInput.value || '').replace(/\D/g, '');
                if (!cleanCep || cleanCep.length !== 8) {
                    hasError = true;
                    cepInput.classList.add('is-invalid');
                    errorMessages.push('Informe um CEP válido com 8 dígitos.');
                } else {
                    cepInput.classList.remove('is-invalid');
                }

                // 7. Logradouro
                const logradouroVal = logradouroInput.value.trim();
                if (!logradouroVal || logradouroVal.length < 2) {
                    hasError = true;
                    logradouroInput.classList.add('is-invalid');
                    errorMessages.push('O campo Logradouro (Rua/Avenida) é obrigatório.');
                } else {
                    logradouroInput.classList.remove('is-invalid');
                }

                // 8. Número
                const numeroVal = numeroInput.value.trim();
                if (!numeroVal) {
                    hasError = true;
                    numeroInput.classList.add('is-invalid');
                    errorMessages.push('O campo Número é obrigatório (ou digite S/N).');
                } else {
                    numeroInput.classList.remove('is-invalid');
                }

                if (hasError) {
                    e.preventDefault();
                    AuthUI.showErrors(clientErrorAlert, errorMessages);

                    // Foco no primeiro campo com erro
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                } else {
                    AuthUI.clearErrors(clientErrorAlert);

                    // Limpa máscara do CEP antes do envio (banco espera CHAR 8)
                    cepInput.value = cleanCep;

                    // Se a opção selecionada for "outro", removemos o name do select bairro_id
                    // para não enviar a string "outro" para a coluna inteira bairro_id no banco
                    if (bairroSelect.value === 'outro') {
                        bairroSelect.removeAttribute('name');
                    }
                }
            });
        });
    </script>
<?= $this->endSection() ?>

