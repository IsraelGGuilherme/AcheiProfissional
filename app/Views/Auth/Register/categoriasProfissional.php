<?= $this->extend('Templates/auth') ?>

<?= $this->section('title') ?>Categorias de Atendimento - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .register-card-lg {
        max-width: 640px !important;
    }
    
    /* Ajustes TomSelect para ficar bonito com o tema */
    .ts-control {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border-color: #dee2e6;
    }
    .ts-control.focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .ts-control .item {
        background: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
        border-radius: 999px !important;
        padding: 4px 10px !important;
        font-weight: 500;
    }
    
    /* Tag Style secundário (Sugestões rápidas) */
    .quick-tag {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.85rem;
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 999px;
        font-size: 0.85rem;
        cursor: pointer;
        color: #4b5563;
        transition: all 0.2s;
    }
    .quick-tag:hover {
        border-color: #0d6efd;
        color: #0d6efd;
        background-color: #f0f8ff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="register-section">

        <div class="container">

            <div class="register-card register-card-lg">

                <div class="text-center mb-4">
                    <span class="badge-type">
                        <i class="bi bi-tags"></i>
                        Categorias Profissionais
                    </span>

                    <h1 class="welcome-title">
                        Quais serviços você oferece?
                    </h1>

                    <p class="welcome-text">
                        Escolha uma ou mais categorias que melhor descrevem o seu trabalho.
                    </p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger py-2 px-3 mb-4 font-sm" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('profissional/categorias') ?>" method="POST" id="formCategorias">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary">Selecione suas categorias:</label>
                        <select id="categorias-select" name="categorias[]" multiple placeholder="Digite para buscar categorias..." autocomplete="off">
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat->id ?>"><?= esc($cat->nome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Sugestões Rápidas (Exemplos Dinâmicos baseados no BD ou Estáticos para agilidade) -->
                    <div class="mb-4">
                        <p class="text-muted small fw-semibold text-uppercase tracking-wider mb-2">Sugestões comuns</p>
                        <div class="d-flex flex-wrap gap-2">
                            <?php 
                                // Sugestões que sabemos que existem no Seeder
                                $sugestoes = ['Pedreiro', 'Eletricista', 'Encanador', 'Pintor', 'Diarista / Faxineira'];
                                foreach ($categorias as $cat):
                                    if (in_array($cat->nome, $sugestoes)):
                            ?>
                                <button type="button" class="quick-tag" onclick="addCategory('<?= $cat->id ?>')">+ <?= esc($cat->nome) ?></button>
                            <?php 
                                    endif;
                                endforeach; 
                            ?>
                        </div>
                    </div>

                    <div class="pt-4 mt-5 border-top">
                        <button type="submit" class="btn-submit d-flex align-items-center justify-content-center gap-2">
                            <span>Avançar para Endereço</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    let tomSelectInstance;
    document.addEventListener("DOMContentLoaded", function() {
        tomSelectInstance = new TomSelect("#categorias-select", {
            plugins: ['remove_button'],
            create: false,
            maxItems: 10,
            placeholder: 'Clique ou digite para buscar...'
        });
    });

    function addCategory(val) {
        if (tomSelectInstance) {
            tomSelectInstance.addItem(val);
        }
    }
</script>
<?= $this->endSection() ?>
