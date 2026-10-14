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

        <form method="get" action="<?= base_url('/') ?>" class="search-box text-start" id="formBusca">

            <div class="row g-2">

                <!-- Categoria -->

                <div class="col-md-3">

                    <label class="filter-label" for="filtroCategoria">
                        Categoria
                    </label>

                    <select class="form-select filter-select" name="categoria" id="filtroCategoria">

                        <option value="">
                            Todas as categorias
                        </option>

                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria->id ?>" <?= (int) $filtros['categoria'] === (int) $categoria->id ? 'selected' : '' ?>>
                                <?= esc($categoria->nome) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Cidade -->

                <div class="col-md-3">

                    <label class="filter-label" for="filtroCidade">
                        Cidade
                    </label>

                    <select class="form-select filter-select" name="cidade" id="filtroCidade">

                        <option value="">
                            Todas as cidades
                        </option>

                        <?php foreach ($cidades as $cidade): ?>
                            <option value="<?= $cidade->id ?>" <?= (int) $filtros['cidade'] === (int) $cidade->id ? 'selected' : '' ?>>
                                <?= esc($cidade->nome) ?> - <?= esc($cidade->uf) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Bairro -->

                <div class="col-md-3">

                    <label class="filter-label" for="filtroBairro">
                        Bairro
                    </label>

                    <select class="form-select filter-select" name="bairro" id="filtroBairro">

                        <option value="">
                            Todos os bairros
                        </option>

                        <?php foreach ($bairros as $bairro): ?>
                            <option value="<?= $bairro->id ?>" <?= (int) $filtros['bairro'] === (int) $bairro->id ? 'selected' : '' ?>>
                                <?= esc($bairro->nome) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Avaliação -->

                <div class="col-md-3">

                    <label class="filter-label" for="filtroNota">
                        Avaliação
                    </label>

                    <select class="form-select filter-select" name="nota" id="filtroNota">

                        <option value="">
                            Qualquer nota
                        </option>

                        <option value="5" <?= $filtros['nota'] === 5 ? 'selected' : '' ?>>
                            5 estrelas
                        </option>

                        <option value="4" <?= $filtros['nota'] === 4 ? 'selected' : '' ?>>
                            4 estrelas ou mais
                        </option>

                        <option value="3" <?= $filtros['nota'] === 3 ? 'selected' : '' ?>>
                            3 estrelas ou mais
                        </option>

                    </select>

                </div>

            </div>

            <button type="submit" class="btn-search">

                <i class="bi bi-search me-1"></i>

                Pesquisar

            </button>

        </form>

    </div>

</section>


<!-- ==========================================
    CONTEÚDO
=========================================== -->

<?php
    // Mantém os filtros atuais ao trocar de categoria ou de página.
    $linkBusca = static function (array $sobrescrever = []) use ($filtros) {
        $params = array_filter(array_merge($filtros, ['pagina' => null], $sobrescrever), static fn ($v) => $v !== null && $v !== '' && $v !== 0);

        // "cidade" vai sempre na URL (mesmo vazia), para a escolha "Todas as cidades" não voltar ao padrão do cadastro.
        $params['cidade'] = $params['cidade'] ?? '';
        $query  = http_build_query($params);

        return base_url('/') . ($query !== '' ? '?' . $query : '');
    };
?>

<main class="content">

    <!-- ======================================
    CATEGORIAS
    ======================================= -->

    <div class="section-label">
        Categorias
    </div>

    <div class="category-list">

        <?php foreach ($categorias as $categoria): ?>
            <a href="<?= $linkBusca(['categoria' => (int) $filtros['categoria'] === (int) $categoria->id ? 0 : (int) $categoria->id]) ?>"
               class="category text-decoration-none <?= (int) $filtros['categoria'] === (int) $categoria->id ? 'active' : '' ?>">
                <?= esc($categoria->nome) ?>
            </a>
        <?php endforeach; ?>

    </div>


    <!-- ======================================
    RESULTADOS
    ======================================= -->

    <div class="results-header">

        <h2 class="results-title">
            <?php $total = $resultado['total']; ?>
            <?= $total === 0 ? 'Nenhum profissional encontrado' : $total . ($total === 1 ? ' profissional encontrado' : ' profissionais encontrados') ?>
        </h2>

        <span class="results-order">
            Ordenado por avaliação
        </span>

    </div>

    <?php if (!empty($cidadePadrao)): ?>
        <div class="alert alert-light border py-2 px-3 mb-3" role="status" style="font-size:12px;">
            <i class="bi bi-geo-alt"></i>
            Mostrando profissionais de <strong><?= esc($cidadePadrao->nome) ?> - <?= esc($cidadePadrao->uf) ?></strong>, a cidade do seu cadastro.
            Para ver outras localidades, use o filtro de cidade ou
            <a href="<?= $linkBusca(['cidade' => 0]) ?>">veja todas as cidades</a>.
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success py-2 px-3 mb-3" role="alert">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger py-2 px-3 mb-3" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($resultado['itens'])): ?>

        <div class="alert alert-light border text-center py-4">
            Tente remover algum filtro ou escolher outra categoria.
        </div>

    <?php endif; ?>

    <?php foreach ($resultado['itens'] as $prof): ?>

        <?php
            $categoriasProf = $prof->categorias;
            $linkZap        = link_whatsapp($prof->whatsapp);
            $local          = trim(implode(', ', array_filter([$prof->bairro_nome, $prof->cidade_nome])));
        ?>

        <article class="professional-card">

            <div class="avatar">
                <?php if ($prof->foto_perfil): ?>
                    <img src="<?= esc(url_foto($prof->foto_perfil), 'attr') ?>" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                <?php else: ?>
                    <?= esc(iniciais_nome($prof->nome)) ?>
                <?php endif; ?>
            </div>

            <div class="professional-info">

                <div class="professional-name-row">

                    <span class="professional-name">
                        <?= esc($prof->nome) ?>
                    </span>

                </div>

                <?php if (!empty($categoriasProf)): ?>
                    <div class="d-flex flex-wrap gap-1 mb-1">
                        <?php foreach ($categoriasProf as $nomeCategoria): ?>
                            <span class="professional-category">
                                <?= esc($nomeCategoria) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="rating">

                    <?php if ((int) $prof->total_avaliacoes > 0): ?>
                        <span class="stars">
                            <?= estrelas_texto($prof->media) ?>
                        </span>
                    <?php endif; ?>

                    <span class="rating-text">
                        <?php if ((int) $prof->total_avaliacoes > 0): ?>
                            <?= formata_media($prof->media) ?> ·
                        <?php endif; ?>
                        <?= rotulo_avaliacoes((int) $prof->total_avaliacoes) ?>
                    </span>

                </div>

                <?php if (!empty($prof->descricao)): ?>
                    <div class="description">
                        <?= esc(mb_strimwidth($prof->descricao, 0, 220, '...')) ?>
                    </div>
                <?php endif; ?>

                <div class="professional-meta">

                    <?php if ($local !== ''): ?>
                        <span>
                            <i class="bi bi-geo-alt"></i>
                            <?= esc($local) ?>
                        </span>
                    <?php endif; ?>

                </div>

            </div>

            <div class="professional-actions">

                <a href="<?= base_url('profissionais/' . $prof->slug) ?>" class="btn-profile text-decoration-none d-flex align-items-center justify-content-center">
                    Ver perfil
                </a>

                <?php if ($linkZap): ?>
                    <a href="<?= esc($linkZap, 'attr') ?>" target="_blank" rel="noopener" class="btn-whatsapp text-decoration-none d-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-whatsapp"></i>
                        WhatsApp
                    </a>
                <?php endif; ?>

            </div>

        </article>

    <?php endforeach; ?>


    <!-- ======================================
    PAGINAÇÃO
    ======================================= -->

    <?php if ($resultado['paginas'] > 1): ?>

        <nav class="d-flex justify-content-center align-items-center gap-3 mt-3" aria-label="Paginação dos resultados">

            <?php if ($resultado['pagina'] > 1): ?>
                <a class="btn-profile text-decoration-none d-inline-flex align-items-center justify-content-center px-3" style="width:auto;" href="<?= $linkBusca(['pagina' => $resultado['pagina'] - 1]) ?>">
                    Anterior
                </a>
            <?php endif; ?>

            <span class="results-order">
                Página <?= $resultado['pagina'] ?> de <?= $resultado['paginas'] ?>
            </span>

            <?php if ($resultado['pagina'] < $resultado['paginas']): ?>
                <a class="btn-profile text-decoration-none d-inline-flex align-items-center justify-content-center px-3" style="width:auto;" href="<?= $linkBusca(['pagina' => $resultado['pagina'] + 1]) ?>">
                    Próxima
                </a>
            <?php endif; ?>

        </nav>

    <?php endif; ?>

</main>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

    /*
    * Carrega os bairros da cidade escolhida (reaproveita a rota enderecos/filhos).
    * Sem JavaScript, o filtro de bairro aparece depois de pesquisar pela cidade.
    */

    (function () {

        const selectCidade = document.getElementById('filtroCidade');
        const selectBairro = document.getElementById('filtroBairro');

        function limparBairros() {
            selectBairro.innerHTML = '<option value="">Todos os bairros</option>';
        }

        selectCidade.addEventListener('change', async () => {

            limparBairros();

            const cidadeId = selectCidade.value;

            if (!cidadeId) {
                return;
            }

            try {
                const resposta = await fetch('<?= base_url('enderecos/filhos') ?>/' + encodeURIComponent(cidadeId));

                if (!resposta.ok) {
                    return;
                }

                const bairros = await resposta.json();

                bairros
                    .filter(item => item.tipo === 'BAIRRO')
                    .sort((a, b) => a.nome.localeCompare(b.nome, 'pt-BR'))
                    .forEach(item => {
                        const opcao = document.createElement('option');
                        opcao.value = item.id;
                        opcao.textContent = item.nome;
                        selectBairro.appendChild(opcao);
                    });

            } catch (erro) {
                /* mantém só "Todos os bairros" */
            }

        });

    })();

</script>

<?= $this->endSection() ?>
