<?php
/**
 * Modal de bloqueio de usuário, compartilhado pelas telas de denúncias.
 * O admin.js preenche o id do usuário e o action do formulário ao abrir (data-user-id / data-user-name no botão).
 *
 * @var string $retorno caminho (relativo ao site) para onde voltar depois de bloquear, ex.: 'admin/denuncias'
 */
$retorno = $retorno ?? 'admin/denuncias';
?>
<div class="modal fade modal-admin" id="modalBloqueio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:430px">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fs-6"><i class="bi bi-lock me-1"></i> Bloquear usuário</h5>
                    <div class="section-subtitle">O bloqueio não remove o cadastro.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form action="" method="post" id="form_bloqueio" data-action-base="<?= site_url('admin/bloqueio') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">

                    <input type="hidden" name="user_id" id="bloqueioUserId">
                    <input type="hidden" name="retorno" value="<?= esc($retorno, 'attr') ?>">

                    <label class="form-label">Motivo</label>
                    <textarea class="form-control" rows="3" name="motivo" required minlength="10" maxlength="255"
                        placeholder="Informe o motivo do bloqueio (mínimo de 10 caracteres)..."></textarea>

                    <div class="input-help">Enquanto estiver bloqueado, o usuário não poderá utilizar as funcionalidades que exigem acesso à plataforma.</div>
                    <div class="input-help"><i class="bi bi-envelope me-1"></i>O usuário receberá um e-mail avisando do bloqueio e informando o motivo.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-row" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-admin"><i class="bi bi-lock me-1"></i> Confirmar bloqueio</button>
                </div>
            </form>
        </div>
    </div>
</div>
