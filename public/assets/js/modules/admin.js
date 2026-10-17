/* admin.js - interações da view de Administração (Bootstrap 5 JS deve estar carregado no layout) */
document.addEventListener('DOMContentLoaded', () => {
  const dashboard = document.getElementById('adminDashboard');
  const adminScreen = document.getElementById('telaAdministradores');

  // Alterna entre dashboard e tela exclusiva de administradores
  document.querySelectorAll('[data-admin-view]').forEach(el => {
    el.addEventListener('click', e => {
      e.preventDefault();
      const showAdmins = el.dataset.adminView === 'admins';
      dashboard.classList.toggle('d-none', showAdmins);
      adminScreen.classList.toggle('d-none', !showAdmins);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });

  // Categorias
  const input = document.getElementById('novaCategoria');
  const chips = document.getElementById('categoryChips');

  const bindRemove = chip => chip.querySelector('button').addEventListener('click', () => chip.remove());
  chips.querySelectorAll('.category-chip').forEach(bindRemove);

  const criarCategoria = () => {
    const nome = input.value.trim();
    if (!nome) return;
    const chip = document.createElement('span');
    chip.className = 'category-chip';
    chip.append(nome + ' ');
    chip.insertAdjacentHTML('beforeend', '<button type="button"><i class="bi bi-x"></i></button>');
    bindRemove(chip);
    chips.appendChild(chip);
    input.value = '';
  };

  document.getElementById('btnCriarCategoria').addEventListener('click', criarCategoria);
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); criarCategoria(); }
  });
});

// Colocar o valor do ususário clicado no modal de bloqueio
document.getElementById('modalBloqueio').addEventListener('show.bs.modal', function (event) {
  const botao = event.relatedTarget;

  document.getElementById('bloqueioUserId').value = botao.dataset.userId;
  document.getElementById('form_bloqueio').action = `admin/bloqueio/${botao.dataset.userId}`;
});

