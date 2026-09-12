/**
 * Utilitários do Módulo de Autenticação
 */

const AuthUI = {
    /**
     * Inicializa a alternância de exibição de senha (mostrar/ocultar)
     * @param {string|HTMLElement} button
     * @param {string|HTMLElement} input
     */
    initPasswordToggle(button, input) {
        const btn = typeof button === 'string' ? document.querySelector(button) : button;
        const inp = typeof input === 'string' ? document.querySelector(input) : input;

        if (!btn || !inp) return;

        btn.addEventListener('click', () => {
            const isPassword = inp.type === 'password';
            inp.type = isPassword ? 'text' : 'password';
            btn.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';
        });
    },

    /**
     * Exibe erros no container de alerta do cliente
     * @param {HTMLElement} container
     * @param {string[]} messages
     */
    showErrors(container, messages) {
        if (!container || !messages || messages.length === 0) return;

        container.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i>' +
            messages.join('<br><i class="bi bi-exclamation-triangle-fill me-2"></i>');
        container.classList.remove('d-none');
    },

    /**
     * Limpa e oculta erros do container de alerta
     * @param {HTMLElement} container
     */
    clearErrors(container) {
        if (!container) return;
        container.innerHTML = '';
        container.classList.add('d-none');
    }
};

