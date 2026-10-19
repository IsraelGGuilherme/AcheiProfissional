// Modais de bloqueio e exclusão: levam o id do usuário clicado para o formulário
document.addEventListener('DOMContentLoaded', () => {
  const modalBloqueio = document.getElementById('modalBloqueio');
  if (modalBloqueio) {
    modalBloqueio.addEventListener('show.bs.modal', event => {
      const botao = event.relatedTarget;
      if (!botao) return;
      const form = document.getElementById('form_bloqueio');
      document.getElementById('bloqueioUserId').value = botao.dataset.userId;
      form.action = `${form.dataset.actionBase}/${botao.dataset.userId}`;
    });
  }

  const modalExclusao = document.getElementById('modalExclusao');
  if (modalExclusao) {
    modalExclusao.addEventListener('show.bs.modal', event => {
      const botao = event.relatedTarget;
      if (!botao) return;
      const form = document.getElementById('form_exclusao');
      form.action = `${form.dataset.actionBase}/${botao.dataset.userId}`;
    });
  }
});