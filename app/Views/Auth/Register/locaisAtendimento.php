<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Localidades de Atendimento - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .register-card-lg {
        max-width: 640px !important;
    }
    
    .badge-local {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .badge-remove {
        cursor: pointer;
        color: #0284c7;
        background: none;
        border: none;
        padding: 0;
        display: flex;
        align-items: center;
    }
    .badge-remove:hover {
        color: #dc3545;
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
        100% { transform: rotate(360deg); }
    }

    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .hierachy-line {
        position: absolute;
        left: -0.75rem;
        top: 0;
        height: 100%;
        border-left: 2px dashed #dee2e6;
    }
    .hierachy-branch {
        position: absolute;
        left: -0.75rem;
        top: 50%;
        width: 0.75rem;
        border-top: 2px dashed #dee2e6;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">

        <div class="container">

            <div class="register-card register-card-lg">

                <div class="text-center mb-4">
                    <span class="badge-type">
                        <i class="bi bi-geo-alt"></i>
                        Locais de Atendimento
                    </span>

                    <h1 class="welcome-title">
                        Onde você atende?
                    </h1>

                    <p class="welcome-text">
                        Selecione as localidades onde você oferece seus serviços.
                    </p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2 px-3 mb-4 font-sm" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Lista de Locais Selecionados -->
                <div class="mb-4">
                    <h6 class="mb-3 text-muted">Locais já adicionados:</h6>
                    <div class="d-flex flex-wrap gap-2" id="badgesContainer">
                        <p class="text-muted text-sm mb-0" id="emptyMessage">Nenhum local selecionado.</p>
                        <!-- Badges virão aqui via JS -->
                    </div>
                </div>

                <!-- Painel de Seleção -->
                <div class="card bg-light border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="card-title mb-4 fw-bold text-primary d-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle"></i> Adicionando Localidade...
                        </h6>
                        
                        <div class="d-flex flex-column gap-3 position-relative ms-3">
                            
                            <!-- Nível 1: País -->
                            <div class="d-flex flex-column flex-sm-row align-items-sm-end gap-2 fade-in position-relative">
                                <div class="flex-grow-1">
                                    <label class="form-label fw-semibold mb-1 text-secondary">País</label>
                                    <select id="selPais" class="form-select form-control-custom">
                                        <option value="">Selecione um país...</option>
                                        <?php foreach ($paises as $pais): ?>
                                            <option value="<?= $pais->id ?>"><?= esc($pais->nome) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="actionPais" style="display: none;">
                                    <button type="button" class="btn btn-primary w-100" onclick="addLocal(document.getElementById('selPais'))">
                                        <i class="bi bi-check2"></i> Selecionar Todo o País
                                    </button>
                                </div>
                            </div>

                            <!-- Nível 2: Estado -->
                            <div class="d-flex flex-column flex-sm-row align-items-sm-end gap-2 fade-in position-relative" id="rowEstado" style="display: none;">
                                <div class="hierachy-line d-none d-sm-block"></div>
                                <div class="hierachy-branch d-none d-sm-block"></div>
                                <div class="flex-grow-1">
                                    <label class="form-label fw-semibold mb-1 text-secondary">Estado (UF)</label>
                                    <select id="selEstado" class="form-select form-control-custom">
                                        <option value="">Filtrar estado específico...</option>
                                    </select>
                                </div>
                                <div id="actionEstado" style="display: none;">
                                    <button type="button" class="btn btn-primary w-100" onclick="addLocal(document.getElementById('selEstado'), 'Todo o Estado de ')">
                                        <i class="bi bi-check2"></i> Selecionar Todo o Estado
                                    </button>
                                </div>
                            </div>

                            <!-- Nível 3: Município -->
                            <div class="d-flex flex-column flex-sm-row align-items-sm-end gap-2 fade-in position-relative" id="rowMunicipio" style="display: none;">
                                <div class="hierachy-line d-none d-sm-block"></div>
                                <div class="hierachy-branch d-none d-sm-block"></div>
                                <div class="flex-grow-1">
                                    <label class="form-label fw-semibold mb-1 text-secondary">Município</label>
                                    <select id="selMunicipio" class="form-select form-control-custom">
                                        <option value="">Filtrar município específico...</option>
                                    </select>
                                </div>
                                <div id="actionMunicipio" style="display: none;">
                                    <button type="button" class="btn btn-primary w-100" onclick="addLocal(document.getElementById('selMunicipio'), 'Todo o Município de ')">
                                        <i class="bi bi-check2"></i> Selecionar Município
                                    </button>
                                </div>
                            </div>

                            <!-- Nível 4: Bairro -->
                            <div class="d-flex flex-column flex-sm-row align-items-sm-end gap-2 fade-in position-relative" id="rowBairro" style="display: none;">
                                <div class="hierachy-line d-none d-sm-block"></div>
                                <div class="hierachy-branch d-none d-sm-block"></div>
                                <div class="flex-grow-1">
                                    <label class="form-label fw-semibold mb-1 text-secondary">Bairro</label>
                                    <select id="selBairro" class="form-select form-control-custom">
                                        <option value="">Selecione o bairro...</option>
                                    </select>
                                </div>
                                <div id="actionBairro" style="display: none;">
                                    <button type="button" class="btn btn-primary w-100" onclick="addLocal(document.getElementById('selBairro'), 'Bairro ')">
                                        <i class="bi bi-check2"></i> Adicionar Bairro
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetForm()">Recomeçar Adição</button>
                        </div>
                    </div>
                </div>

                <form action="<?= base_url('profissional/locaisatendimento') ?>" method="POST" id="formLocaisAtendimento">
                    <?= csrf_field() ?>
                    <input type="hidden" name="locais" id="locaisInput" value="[]">

                    <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2">
                        <span>Concluir e Ir para o Dashboard</span>
                        <i class="bi bi-check-circle"></i>
                    </button>
                </form>

            </div>

        </div>

    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const baseUrl = '<?= base_url() ?>';
    
    // Arrays em memória
    let selectedLocais = [];

    // Refs DOM
    const selPais = document.getElementById('selPais');
    const selEstado = document.getElementById('selEstado');
    const selMunicipio = document.getElementById('selMunicipio');
    const selBairro = document.getElementById('selBairro');
    
    const actionPais = document.getElementById('actionPais');
    const actionEstado = document.getElementById('actionEstado');
    const actionMunicipio = document.getElementById('actionMunicipio');
    const actionBairro = document.getElementById('actionBairro');

    const rowEstado = document.getElementById('rowEstado');
    const rowMunicipio = document.getElementById('rowMunicipio');
    const rowBairro = document.getElementById('rowBairro');
    
    const badgesContainer = document.getElementById('badgesContainer');
    const emptyMessage = document.getElementById('emptyMessage');
    const locaisInput = document.getElementById('locaisInput');

    function resetForm() {
        selPais.value = '';
        actionPais.style.display = 'none';
        
        selEstado.innerHTML = '<option value="">Filtrar estado específico...</option>';
        rowEstado.style.display = 'none';
        actionEstado.style.display = 'none';
        
        selMunicipio.innerHTML = '<option value="">Filtrar município específico...</option>';
        rowMunicipio.style.display = 'none';
        actionMunicipio.style.display = 'none';
        
        selBairro.innerHTML = '<option value="">Selecione o bairro...</option>';
        rowBairro.style.display = 'none';
        actionBairro.style.display = 'none';
    }

    async function fetchFilhos(paiId, selectTarget, defaultOptionText) {
        selectTarget.innerHTML = `<option value="">${defaultOptionText}</option>`;
        selectTarget.classList.add('select-loading');
        selectTarget.disabled = true;

        try {
            const response = await fetch(`${baseUrl}enderecos/filhos/${paiId}`);
            if (!response.ok) throw new Error('Falha ao buscar localidades');
            
            const data = await response.json();
            
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nome;
                selectTarget.appendChild(option);
            });
        } catch (error) {
            console.error(error);
        } finally {
            selectTarget.classList.remove('select-loading');
            selectTarget.disabled = false;
        }
    }

    selPais.addEventListener('change', () => {
        const val = selPais.value;
        if (val) {
            actionPais.style.display = 'block';
            rowEstado.style.display = 'flex';
            fetchFilhos(val, selEstado, 'Filtrar estado específico...');
        } else {
            resetForm();
        }
    });

    selEstado.addEventListener('change', () => {
        const val = selEstado.value;
        if (val) {
            actionEstado.style.display = 'block';
            rowMunicipio.style.display = 'flex';
            fetchFilhos(val, selMunicipio, 'Filtrar município específico...');
        } else {
            actionEstado.style.display = 'none';
            rowMunicipio.style.display = 'none';
            selMunicipio.value = '';
            selMunicipio.dispatchEvent(new Event('change'));
        }
    });

    selMunicipio.addEventListener('change', () => {
        const val = selMunicipio.value;
        if (val) {
            actionMunicipio.style.display = 'block';
            rowBairro.style.display = 'flex';
            fetchFilhos(val, selBairro, 'Selecione o bairro...');
        } else {
            actionMunicipio.style.display = 'none';
            rowBairro.style.display = 'none';
            selBairro.value = '';
            selBairro.dispatchEvent(new Event('change'));
        }
    });

    selBairro.addEventListener('change', () => {
        const val = selBairro.value;
        if (val) {
            actionBairro.style.display = 'block';
        } else {
            actionBairro.style.display = 'none';
        }
    });

    window.addLocal = function(selectEl, prefix = '') {
        const id = selectEl.value;
        if (!id) return;
        
        const nome = selectEl.options[selectEl.selectedIndex].text;
        
        if (selectedLocais.some(l => l.id === id)) {
            alert('Esta localidade já foi adicionada.');
            return;
        }

        selectedLocais.push({ id, nome: prefix + nome });
        renderBadges();
        resetForm();
    };

    window.removeLocal = function(id) {
        selectedLocais = selectedLocais.filter(l => l.id !== id);
        renderBadges();
    };

    function renderBadges() {
        if (selectedLocais.length === 0) {
            emptyMessage.style.display = 'block';
            badgesContainer.innerHTML = '';
            badgesContainer.appendChild(emptyMessage);
        } else {
            emptyMessage.style.display = 'none';
            badgesContainer.innerHTML = '';
            
            selectedLocais.forEach(local => {
                const badge = document.createElement('span');
                badge.className = 'badge-local fade-in';
                badge.innerHTML = `
                    <i class="bi bi-geo-alt-fill"></i>
                    ${local.nome}
                    <button type="button" class="badge-remove ms-1" onclick="removeLocal('${local.id}')" title="Remover">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                `;
                badgesContainer.appendChild(badge);
            });
        }
        
        locaisInput.value = JSON.stringify(selectedLocais.map(l => l.id));
    }
</script>
<?= $this->endSection() ?>
