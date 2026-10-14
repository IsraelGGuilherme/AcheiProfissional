<?php

/**
 * Bloco "Excluir conta" (profissional e contratante).
 * Recebe: $tipoConta ('PROFISSIONAL' ou 'COMUM').
 */

$ehProfissional = ($tipoConta ?? '') === 'PROFISSIONAL';

?>

<section class="ap-card" id="excluir-conta" style="border-color:#f0c9c9;">

    <h2 style="color:var(--ap-danger);">Excluir conta</h2>

    <p>
        Ao excluir sua conta, seus dados e seu acesso são apagados de forma definitiva.
        <?php if ($ehProfissional): ?>
            Seu perfil público, suas categorias, horários, áreas de atuação e as avaliações que você recebeu deixam de existir.
        <?php else: ?>
            As avaliações que você já fez continuam no perfil dos profissionais, mas sem o seu nome.
        <?php endif; ?>
        Essa ação não pode ser desfeita.
    </p>

    <button type="button" class="ap-btn ap-btn-danger" data-bs-toggle="modal" data-bs-target="#modalExcluirConta">
        <i class="bi bi-trash"></i> Excluir minha conta
    </button>

</section>

<div class="modal fade" id="modalExcluirConta" tabindex="-1" aria-labelledby="tituloExcluirConta" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px;">

            <form method="post" action="<?= base_url('perfil/excluir-conta') ?>">

                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="tituloExcluirConta" style="font-size:16px;">Excluir minha conta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">

                    <p class="ap-text">Para confirmar, digite a sua senha atual.</p>

                    <div class="ap-field">
                        <label for="senhaExcluir">Senha</label>
                        <input type="password" class="ap-input" id="senhaExcluir" name="senha" autocomplete="current-password" required>
                    </div>

                    <label class="ap-check" style="align-items:flex-start;">
                        <input type="checkbox" name="confirmar" value="1" required style="margin-top:3px;">
                        <span>Entendo que a exclusão é definitiva e não poderá ser desfeita.</span>
                    </label>

                </div>

                <div class="modal-footer">
                    <button type="button" class="ap-btn ap-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="ap-btn ap-btn-danger">Excluir definitivamente</button>
                </div>

            </form>

        </div>
    </div>
</div>
