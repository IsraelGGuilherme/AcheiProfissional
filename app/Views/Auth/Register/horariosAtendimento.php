<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Horários de Atendimento - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .register-card-lg {
        max-width: 640px !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0d6efd;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
    }
    
    .accordion-button:focus {
        box-shadow: none;
    }

    .day-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
        cursor: pointer;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 999px;
    }
    
    .status-fechado {
        background-color: #f1f5f9;
        color: #64748b;
    }
    
    .status-aberto {
        background-color: #dbeafe;
        color: #1d4ed8;
    }

    .time-input {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        width: 100%;
        font-size: 0.875rem;
    }
    
    .time-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .table-horarios th {
        font-weight: 600;
        color: #4b5563;
        font-size: 0.875rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">
        <div class="container">
            <div class="register-card register-card-lg">

                <div class="text-center mb-4">
                    <span class="badge-type">
                        <i class="bi bi-clock-history"></i>
                        Horários de Atendimento
                    </span>

                    <h1 class="welcome-title">
                        Defina seus horários
                    </h1>

                    <p class="welcome-text">
                        Informe os dias e horários em que você está disponível para atendimento.
                    </p>
                </div>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('profissional/horarios') ?>" method="POST" id="formHorarios">
                    <?= csrf_field() ?>

                    <div class="accordion" id="accordionHorarios">
                        
                        <?php 
                        $dias = [
                            'DOMINGO' => 'Domingo',
                            'SEGUNDA' => 'Segunda-feira',
                            'TERCA' => 'Terça-feira',
                            'QUARTA' => 'Quarta-feira',
                            'QUINTA' => 'Quinta-feira',
                            'SEXTA' => 'Sexta-feira',
                            'SABADO' => 'Sábado'
                        ];
                        $count = 0;
                        foreach($dias as $key => $label): 
                            $count++;
                            $collapseId = "collapse_{$key}";
                            $headingId = "heading_{$key}";
                            // Por padrão deixaremos segunda a sexta marcados
                            $checked = in_array($key, ['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA']) ? 'checked' : '';
                        ?>
                            <div class="accordion-item" data-day="<?= $key ?>">
                                <h2 class="accordion-header" id="<?= $headingId ?>">
                                    <div class="d-flex align-items-center w-100 bg-white" style="border-bottom: 1px solid rgba(0,0,0,.125);">
                                        <div class="p-3 d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                            <input type="checkbox" class="day-checkbox form-check-input mt-0" 
                                                   name="dias_marcados[]" value="<?= $key ?>" <?= $checked ?> 
                                                   onchange="toggleDay('<?= $key ?>', '<?= $collapseId ?>')">
                                            <label class="form-check-label mb-0 fw-medium" style="cursor: pointer;" onclick="$(this).prev().click()">
                                                <?= $label ?>
                                            </label>
                                            <span class="status-badge status-<?= $checked ? 'aberto' : 'fechado' ?> ms-auto day-status-badge" id="badge_<?= $key ?>">
                                                <?= $checked ? 'Aberto' : 'Fechado' ?>
                                            </span>
                                        </div>
                                        <button class="accordion-button <?= $checked ? '' : 'collapsed' ?> border-0 shadow-none bg-transparent" 
                                                style="width: 60px; padding: 1.25rem;" 
                                                type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" 
                                                aria-expanded="<?= $checked ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">
                                        </button>
                                    </div>
                                </h2>
                                
                                <div id="<?= $collapseId ?>" class="accordion-collapse collapse <?= $checked ? 'show' : '' ?>" aria-labelledby="<?= $headingId ?>" data-bs-parent="#accordionHorarios">
                                    <div class="accordion-body bg-light">
                                        <table class="table table-borderless table-sm mb-2 table-horarios w-100" id="table_<?= $key ?>">
                                            <thead>
                                                <tr>
                                                    <th style="width: 42%;">Abertura</th>
                                                    <th style="width: 42%;">Fechamento</th>
                                                    <th style="width: 16%; text-align: center;">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_<?= $key ?>">
                                                <!-- Pelo menos um turno padrão se estiver marcado -->
                                                <tr class="align-middle time-row">
                                                    <td>
                                                        <input type="time" name="horarios[<?= $key ?>][abre][]" class="time-input" value="08:00" <?= $checked ? 'required' : 'disabled' ?>>
                                                    </td>
                                                    <td>
                                                        <input type="time" name="horarios[<?= $key ?>][fecha][]" class="time-input" value="18:00" <?= $checked ? 'required' : 'disabled' ?>>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this, '<?= $key ?>')" title="Remover turno">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-add-turno" onclick="addRow('<?= $key ?>')">
                                            <i class="bi bi-plus"></i> Adicionar turno
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= base_url('profissional/locaisatendimento') ?>" class="btn btn-outline-secondary px-4">Voltar</a>
                        <button type="submit" class="btn btn-primary px-5 fw-medium">
                            Concluir Cadastro
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleDay(dayKey, collapseId) {
        const checkbox = document.querySelector(`input[value="${dayKey}"]`);
        const isChecked = checkbox.checked;
        
        // Atualiza a badge
        const badge = document.getElementById(`badge_${dayKey}`);
        if(isChecked) {
            badge.textContent = 'Aberto';
            badge.className = 'status-badge status-aberto ms-auto day-status-badge';
        } else {
            badge.textContent = 'Fechado';
            badge.className = 'status-badge status-fechado ms-auto day-status-badge';
        }

        // Habilita/Desabilita os inputs
        const tbody = document.getElementById(`tbody_${dayKey}`);
        const inputs = tbody.querySelectorAll('input[type="time"]');
        inputs.forEach(input => {
            if(isChecked) {
                input.removeAttribute('disabled');
                input.setAttribute('required', 'required');
            } else {
                input.setAttribute('disabled', 'disabled');
                input.removeAttribute('required');
            }
        });

        // Abre/Fecha o accordion dinamicamente (opcional, mas boa UX)
        const collapseEl = document.getElementById(collapseId);
        const bsCollapse = bootstrap.Collapse.getInstance(collapseEl);
        
        if(isChecked) {
            if(bsCollapse) {
                bsCollapse.show();
            } else {
                new bootstrap.Collapse(collapseEl, { toggle: true }).show();
            }
            
            // Se ativou e não tem nenhuma linha, cria uma
            if(tbody.querySelectorAll('.time-row').length === 0) {
                addRow(dayKey);
            }
        } else {
            if(bsCollapse) {
                bsCollapse.hide();
            }
        }
    }

    function addRow(dayKey) {
        const tbody = document.getElementById(`tbody_${dayKey}`);
        const checkbox = document.querySelector(`input[value="${dayKey}"]`);
        const isChecked = checkbox.checked;
        
        const row = document.createElement('tr');
        row.className = 'align-middle time-row';
        row.innerHTML = `
            <td>
                <input type="time" name="horarios[${dayKey}][abre][]" class="time-input" ${isChecked ? 'required' : 'disabled'}>
            </td>
            <td>
                <input type="time" name="horarios[${dayKey}][fecha][]" class="time-input" ${isChecked ? 'required' : 'disabled'}>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this, '${dayKey}')" title="Remover turno">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    }

    function removeRow(btn, dayKey) {
        const tbody = document.getElementById(`tbody_${dayKey}`);
        const rows = tbody.querySelectorAll('.time-row');
        
        if(rows.length > 1) {
            btn.closest('tr').remove();
        } else {
            alert('Você precisa ter pelo menos um turno de horário se o dia estiver marcado. Se não for atender neste dia, desmarque a caixa de seleção ao lado do nome do dia.');
        }
    }
</script>
<?= $this->endSection() ?>

