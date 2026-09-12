/**
 * Utilitários Universais de Máscaras de Entrada
 */

const Masks = {
    /**
     * Aplica máscara de CPF (000.000.000-00)
     * @param {string} value
     * @returns {string}
     */
    cpf(value) {
        let val = value.replace(/\D/g, '');
        if (val.length > 11) {
            val = val.substring(0, 11);
        }

        if (val.length > 9) {
            return val.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
        }
        if (val.length > 6) {
            return val.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
        }
        if (val.length > 3) {
            return val.replace(/(\d{3})(\d{1,3})/, '$1.$2');
        }
        return val;
    },

    /**
     * Aplica máscara dinâmica de Telefone ((00) 00000-0000 ou (00) 0000-0000)
     * @param {string} value
     * @returns {string}
     */
    phone(value) {
        let val = value.replace(/\D/g, '');
        if (val.length > 11) {
            val = val.substring(0, 11);
        }

        if (val.length > 10) {
            return val.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
        if (val.length > 6) {
            return val.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        }
        if (val.length > 2) {
            return val.replace(/(\d{2})(\d{0,5})/, '($1) $2');
        }
        if (val.length > 0) {
            return val.replace(/(\d{0,2})/, '($1');
        }
        return val;
    },

    /**
     * Remove caracteres não numéricos
     * @param {string} value
     * @returns {string}
     */
    clean(value) {
        return (value || '').replace(/\D/g, '');
    },

    /**
     * Vincula a máscara ao evento input de um elemento HTML
     * @param {HTMLInputElement} input
     * @param {'cpf'|'phone'} type
     * @param {Function} [onInputCallback]
     */
    bind(input, type, onInputCallback) {
        if (!input) return;

        input.addEventListener('input', (e) => {
            const formatted = Masks[type](e.target.value);
            e.target.value = formatted;
            if (typeof onInputCallback === 'function') {
                onInputCallback(formatted, Masks.clean(formatted));
            }
        });
    }
};

// Vinculação automática via atributos data-mask="cpf" ou data-mask="phone"
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[data-mask="cpf"]').forEach(input => {
        Masks.bind(input, 'cpf');
    });

    document.querySelectorAll('input[data-mask="phone"]').forEach(input => {
        Masks.bind(input, 'phone');
    });
});

