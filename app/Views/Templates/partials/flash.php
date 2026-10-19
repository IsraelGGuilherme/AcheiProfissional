<?php

/**
 * Mensagens de retorno (sucesso/erro) usadas pelas telas de perfil e do profissional.
 */

$sucesso = session()->getFlashdata('success');
$erro    = session()->getFlashdata('error');

?>

<?php if ($sucesso): ?>
    <div class="alert alert-success py-2 px-3 mb-3 font-sm" role="alert">
        <?= esc($sucesso) ?>
    </div>
<?php endif; ?>


<?php if ($erro && !is_array($erro)): ?>
    <div class="alert alert-danger py-2 px-3 mb-3 font-sm" role="alert">
        <?= esc($erro) ?>
    </div>
<?php endif; ?>

<?php // Para quando o erro vir de uma validação com o $this->validator, o erro vem em formato de array 
?>
<?php if ($erro && is_array($erro)): ?>
    <?php foreach ($erro as $key => $e): ?>
        <div class="alert alert-danger py-2 px-3 mb-3 font-sm" role="alert">
            <?= esc("Erro no campo $key: $e") ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>