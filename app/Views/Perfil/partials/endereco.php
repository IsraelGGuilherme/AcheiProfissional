<?php

/**
 * Formulário de endereço compartilhado (profissional e contratante).
 * Recebe: $action (URL do POST) e $endereco (retorno de PerfilBase::dadosEndereco()).
 */

$end      = $endereco['endereco'];
$ufAtual  = $endereco['ufId'];
$bairroId = old('bairro_id', $end->bairro_id ?? '');
$outro    = old('bairro_outro', $end->bairro_outro ?? '');

?>

<form method="post" action="<?= esc($action, 'attr') ?>" novalidate>

    <?= csrf_field() ?>

    <div class="ap-form-row">

        <div class="ap-field">
            <label for="cep">CEP</label>
            <input type="text" class="ap-input" id="cep" name="cep" inputmode="numeric" data-mask="cep" maxlength="9" value="<?= esc(old('cep', $end->cep ?? ''), 'attr') ?>" required>
        </div>

        <div class="ap-field">
            <label for="numero">Número</label>
            <input type="text" class="ap-input" id="numero" name="numero" maxlength="20" value="<?= esc(old('numero', $end->numero ?? ''), 'attr') ?>" required>
        </div>

    </div>

    <div class="ap-form-row">

        <div class="ap-field">
            <label for="logradouro">Logradouro</label>
            <input type="text" class="ap-input" id="logradouro" name="logradouro" maxlength="150" value="<?= esc(old('logradouro', $end->logradouro ?? ''), 'attr') ?>" required>
        </div>

        <div class="ap-field">
            <label for="complemento">Complemento <span class="ap-muted">(opcional)</span></label>
            <input type="text" class="ap-input" id="complemento" name="complemento" maxlength="100" value="<?= esc(old('complemento', $end->complemento ?? ''), 'attr') ?>">
        </div>

    </div>

    <div data-cascata="endereco">

        <div class="ap-form-row tres">

            <div class="ap-field">
                <label for="enderecoUf">Estado</label>
                <select class="ap-select" id="enderecoUf" name="uf_id" data-uf>
                    <option value="">Selecione o estado...</option>
                    <?php foreach ($endereco['ufs'] as $uf): ?>
                        <option value="<?= $uf->id ?>" <?= (int) $ufAtual === (int) $uf->id ? 'selected' : '' ?>><?= esc($uf->nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="ap-field">
                <label for="enderecoCidade">Cidade</label>
                <select class="ap-select" id="enderecoCidade" name="cidade_id" data-cidade required>
                    <option value="">Selecione a cidade...</option>
                    <?php foreach ($endereco['cidades'] as $cidade): ?>
                        <option value="<?= $cidade->id ?>" <?= (int) old('cidade_id', $end->cidade_id ?? 0) === (int) $cidade->id ? 'selected' : '' ?>><?= esc($cidade->nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="ap-field">
                <label for="enderecoBairro">Bairro</label>
                <select class="ap-select" id="enderecoBairro" name="bairro_id" data-bairro>
                    <option value="">Selecione o bairro...</option>
                    <?php foreach ($endereco['bairros'] as $bairro): ?>
                        <option value="<?= $bairro->id ?>" <?= (string) $bairroId === (string) $bairro->id ? 'selected' : '' ?>><?= esc($bairro->nome) ?></option>
                    <?php endforeach; ?>
                    <option value="outro" <?= ($bairroId === '' || $bairroId === null) && $outro !== '' ? 'selected' : '' ?>>Outro (digitar o nome)</option>
                </select>
            </div>

        </div>

        <div class="ap-field" data-bairro-outro>
            <label for="bairroOutro">Nome do bairro</label>
            <input type="text" class="ap-input" id="bairroOutro" name="bairro_outro" maxlength="150" value="<?= esc($outro, 'attr') ?>" placeholder="Digite o nome do bairro">
            <div class="ap-help">Use quando o seu bairro não aparecer na lista.</div>
        </div>

    </div>

    <button type="submit" class="ap-btn ap-btn-primary">Salvar endereço</button>

</form>
