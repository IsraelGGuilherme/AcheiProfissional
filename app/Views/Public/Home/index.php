<?= $this->extend('Templates/public') ?>

<?= $this->section('title') ?>Buscar profissionais - Achei Profissional<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= base_url('assets/css/modules/home.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="hero">

    <div class="container text-center">

        <div class="location-badge">

            <i class="bi bi-geo-alt"></i>

            Muriaé-MG e cidades vizinhas

        </div>

        <h1>
            Encontre um profissional de confiança
            <br>
            perto de você
        </h1>

        <p class="hero-description">

            Eletricista, encanador, diarista, pedreiro e muito mais.
            Veja avaliações de outros moradores e chame direto no WhatsApp.

        </p>

        <!-- ==================================
        FILTROS
        =================================== -->

        <div class="search-box text-start">

            <div class="row g-2">

                <!-- Categoria -->

                <div class="col-md-3">

                    <label class="filter-label">
                        Categoria
                    </label>

                    <select class="form-select filter-select">

                        <option>
                            Todas as categorias
                        </option>

                        <option>
                            Eletricista
                        </option>

                        <option>
                            Encanador
                        </option>

                        <option>
                            Pedreiro
                        </option>

                        <option>
                            Pintor
                        </option>

                        <option>
                            Marceneiro
                        </option>

                    </select>

                </div>

                <!-- Cidade -->

                <div class="col-md-3">

                    <label class="filter-label">
                        Cidade
                    </label>

                    <select class="form-select filter-select">

                        <option>
                            Muriaé
                        </option>

                        <option>
                            Mirai
                        </option>

                        <option>
                            Eugenópolis
                        </option>

                        <option>
                            Patrocínio do Muriaé
                        </option>

                    </select>

                </div>

                <!-- Bairro -->

                <div class="col-md-3">

                    <label class="filter-label">
                        Bairro
                    </label>

                    <select class="form-select filter-select">

                        <option>
                            Todos os bairros
                        </option>

                        <option>
                            Centro
                        </option>

                        <option>
                            Barra
                        </option>

                        <option>
                            Safira
                        </option>

                        <option>
                            Porto
                        </option>

                        <option>
                            São Joaquim
                        </option>

                    </select>

                </div>

                <!-- Avaliação -->

                <div class="col-md-3">

                    <label class="filter-label">
                        Avaliação
                    </label>

                    <select class="form-select filter-select">

                        <option>
                            Qualquer nota
                        </option>

                        <option>
                            5 estrelas
                        </option>

                        <option>
                            4 estrelas ou mais
                        </option>

                        <option>
                            3 estrelas ou mais
                        </option>

                    </select>

                </div>

            </div>

            <button type="button" class="btn-search">

                <i class="bi bi-search me-1"></i>

                Pesquisar

            </button>

        </div>

    </div>

</section>

<!-- ==========================================
    CONTEÚDO
=========================================== -->

<main class="content">

    <!-- ======================================
    CATEGORIAS
    ======================================= -->

    <div class="section-label">
        Categorias
    </div>

    <div class="category-list">

        <button class="category active">
            Eletricista
        </button>

        <button class="category">
            Encanador
        </button>

        <button class="category">
            Pedreiro
        </button>

        <button class="category">
            Pintor
        </button>

        <button class="category">
            Marceneiro
        </button>

        <button class="category">
            Mecânico
        </button>

        <button class="category">
            Costureira
        </button>

        <button class="category">
            Diarista
        </button>

        <button class="category">
            Cuidador de idosos
        </button>

        <button class="category">
            Professor particular
        </button>

        <button class="category">
            Técnico em informática
        </button>

        <button class="category">
            Jardineiro
        </button>

        <button class="category">
            Chaveiro
        </button>

    </div>

    <!-- ======================================
    RESULTADOS
        ======================================= -->

    <div class="results-header">

        <h2 class="results-title">
            5 profissionais encontrados
        </h2>

        <span class="results-order">
            Ordenado por avaliação
        </span>

    </div>

    <!-- ======================================
    PROFISSIONAL 1
        ======================================= -->

    <article class="professional-card">

        <div class="avatar">
            JE
        </div>

        <div class="professional-info">

            <div class="professional-name-row">

                <span class="professional-name">
                    João Eletricista
                </span>

                <span class="professional-category">
                    Eletricista
                </span>

            </div>

            <div class="rating">

                <span class="stars">
                    ★★★★★
                </span>

                <span class="rating-text">
                    5,0 · 34 avaliações
                </span>

            </div>

            <div class="description">

                Instalações elétricas residenciais e comerciais,
                troca de quadros de energia, chuveiros e tomadas.
                Atendo emergências 24 horas em Muriaé e região.

            </div>

            <div class="professional-meta">

                <span>
                    <i class="bi bi-geo-alt"></i>
                    Centro, Muriaé
                </span>

                <span>
                    <i class="bi bi-house"></i>
                    Atende em domicílio
                </span>

                <span class="tag">
                    Atende emergência
                </span>

            </div>

        </div>

        <div class="professional-actions">

            <button class="btn-profile">
                Ver perfil
            </button>

            <button class="btn-whatsapp">

                <i class="bi bi-whatsapp"></i>

                WhatsApp

            </button>

        </div>

    </article>

    <!-- ======================================
    PROFISSIONAL 2
        ======================================= -->

    <article class="professional-card">

        <div class="avatar">
            DR
        </div>

        <div class="professional-info">

            <div class="professional-name-row">

                <span class="professional-name">
                    Dona Rita Diarista
                </span>

                <span class="professional-category">
                    Diarista
                </span>

            </div>

            <div class="rating">

                <span class="stars">
                    ★★★★★
                </span>

                <span class="rating-text">
                    5,0 · 41 avaliações
                </span>

            </div>

            <div class="description">

                Limpeza residencial completa,
                passadoria e organização de armários.

            </div>

            <div class="professional-meta">

                <span>
                    <i class="bi bi-geo-alt"></i>
                    Porto, Muriaé
                </span>

                <span>
                    <i class="bi bi-house"></i>
                    Atende em domicílio
                </span>

                <span class="tag">
                    Diária o meio diária
                </span>

            </div>

        </div>

        <div class="professional-actions">

            <button class="btn-profile">
                Ver perfil
            </button>

            <button class="btn-whatsapp">

                <i class="bi bi-whatsapp"></i>

                WhatsApp

            </button>

        </div>

    </article>

    <!-- ======================================
    PROFISSIONAL 3
        ======================================= -->

    <article class="professional-card">

        <div class="avatar">
            CE
        </div>

        <div class="professional-info">

            <div class="professional-name-row">

                <span class="professional-name">
                    Carlos Encanador
                </span>

                <span class="professional-category">
                    Encanador
                </span>

            </div>

            <div class="rating">

                <span class="stars">
                    ★★★★★
                </span>

                <span class="rating-text">
                    4,8 · 27 avaliações
                </span>

            </div>

            <div class="description">

                Desentupimento, caça-vazamentos com equipamento
                próprio e reparos hidráulicos em geral.

            </div>

            <div class="professional-meta">

                <span>
                    <i class="bi bi-geo-alt"></i>
                    Safira, Muriaé
                </span>

                <span>
                    <i class="bi bi-house"></i>
                    Atende em domicílio
                </span>

                <span class="tag">
                    Caça-vazamentos
                </span>

            </div>

        </div>

        <div class="professional-actions">

            <button class="btn-profile">
                Ver perfil
            </button>

            <button class="btn-whatsapp">

                <i class="bi bi-whatsapp"></i>

                WhatsApp

            </button>

        </div>

    </article>

    <!-- ======================================
    PROFISSIONAL 4
        ======================================= -->

    <article class="professional-card">

        <div class="avatar">
            TI
        </div>

        <div class="professional-info">

            <div class="professional-name-row">

                <span class="professional-name">
                    Tiago Informática
                </span>

                <span class="professional-category">
                    Técnico em informática
                </span>

            </div>

            <div class="rating">

                <span class="stars">
                    ★★★★★
                </span>

                <span class="rating-text">
                    4,6 · 22 avaliações
                </span>

            </div>

            <div class="description">

                Formatação, manutenção de notebooks,
                montagem de redes e instalação de câmeras
                de segurança.

            </div>

            <div class="professional-meta">

                <span>
                    <i class="bi bi-geo-alt"></i>
                    Bom Pastor, Muriaé
                </span>

                <span>
                    <i class="bi bi-house"></i>
                    Atende em domicílio
                </span>

                <span class="tag">
                    Atende em domicílio
                </span>

            </div>

        </div>

        <div class="professional-actions">

            <button class="btn-profile">
                Ver perfil
            </button>

            <button class="btn-whatsapp">

                <i class="bi bi-whatsapp"></i>

                WhatsApp

            </button>

        </div>

    </article>

    <!-- ======================================
    PROFISSIONAL 5
    ======================================= -->

    <article class="professional-card">

        <div class="avatar">
            MI
        </div>

        <div class="professional-info">

            <div class="professional-name-row">

                <span class="professional-name">
                    Maria Instalações
                </span>

                <span class="professional-category">
                    Eletricista
                </span>

            </div>

            <div class="rating">

                <span class="stars">
                    ★★★★★
                </span>

                <span class="rating-text">
                    4,5 · 18 avaliações
                </span>

            </div>

            <div class="description">

                Especialista em instalação residencial,
                quadros de energia, ventiladores de teto
                e iluminação em LED.

            </div>

            <div class="professional-meta">

                <span>
                    <i class="bi bi-geo-alt"></i>
                    Barra, Muriaé
                </span>

                <span>
                    <i class="bi bi-house"></i>
                    Atende em domicílio
                </span>

                <span class="tag">
                    Especialista em residencial
                </span>

            </div>

        </div>

        <div class="professional-actions">

            <button class="btn-profile">
                Ver perfil
            </button>

            <button class="btn-whatsapp">

                <i class="bi bi-whatsapp"></i>

                WhatsApp

            </button>

        </div>

    </article>

</main>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

    /*
    * Seleção visual das categorias
    */

    const categories =
        document.querySelectorAll('.category');

    categories.forEach(category => {

        category.addEventListener('click', () => {

            categories.forEach(item => {
                item.classList.remove('active');
            });

            category.classList.add('active');

        });

    });
</script>

<?= $this->endSection() ?>
