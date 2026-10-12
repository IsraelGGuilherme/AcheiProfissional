/**
 * Scripts das telas "Meu perfil" (profissional e contratante).
 *
 * - máscaras: input[data-mask="cpf|cnpj|documento|cep|phone"]
 * - prévia de imagem: input[type=file][data-preview="#seletor"]
 * - selects em cascata Estado > Cidade > Bairro: container [data-cascata]
 * - lista de categorias com busca e contador: [data-checklist]
 */

(function () {

    'use strict';

    const baseUrl = (document.querySelector('meta[name="base-url"]') || {}).content || '';

    /* ---------- Máscaras ---------- */

    const apenasDigitos = (v) => (v || '').replace(/\D/g, '');

    const mascaras = {
        cpf(v) {
            const d = apenasDigitos(v).slice(0, 11);
            return d
                .replace(/^(\d{3})(\d)/, '$1.$2')
                .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1-$2');
        },
        cnpj(v) {
            const d = apenasDigitos(v).slice(0, 14);
            return d
                .replace(/^(\d{2})(\d)/, '$1.$2')
                .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1/$2')
                .replace(/(\d{4})(\d)/, '$1-$2');
        },
        cep(v) {
            return apenasDigitos(v).slice(0, 8).replace(/^(\d{5})(\d)/, '$1-$2');
        },
        phone(v) {
            const d = apenasDigitos(v).slice(0, 11);
            if (d.length > 10) return d.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            if (d.length > 6) return d.replace(/^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3');
            if (d.length > 2) return d.replace(/^(\d{2})(\d{0,5})$/, '($1) $2');
            return d.length ? '(' + d : '';
        },
    };

    function aplicarMascara(input) {
        const tipo = input.dataset.mask;

        const formatar = () => {
            let funcao = mascaras[tipo];

            if (tipo === 'documento') {
                const pj = document.querySelector('input[name="tipo_pessoa"]:checked');
                funcao = pj && pj.value === 'PJ' ? mascaras.cnpj : mascaras.cpf;
            }

            if (funcao) {
                input.value = funcao(input.value);
            }
        };

        input.addEventListener('input', formatar);
        formatar();
    }

    document.querySelectorAll('input[data-mask]').forEach(aplicarMascara);

    // Trocar PF/PJ reaplica a máscara do documento e ajusta o rótulo.
    document.querySelectorAll('input[name="tipo_pessoa"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const campo = document.querySelector('input[data-mask="documento"]');
            const rotulo = document.getElementById('rotuloDocumento');
            const pj = radio.value === 'PJ';

            if (rotulo) {
                rotulo.textContent = pj ? 'CNPJ' : 'CPF';
            }

            if (campo) {
                campo.value = pj ? mascaras.cnpj(campo.value) : mascaras.cpf(campo.value);
            }
        });
    });

    /* ---------- Prévia de imagem ---------- */

    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        input.addEventListener('change', () => {
            const alvo = document.querySelector(input.dataset.preview);
            const arquivo = input.files && input.files[0];

            if (!alvo || !arquivo) {
                return;
            }

            if (arquivo.size > 2 * 1024 * 1024) {
                alert('A imagem deve ter no máximo 2MB.');
                input.value = '';
                return;
            }

            const url = URL.createObjectURL(arquivo);

            if (alvo.tagName === 'IMG') {
                alvo.src = url;
            } else {
                alvo.style.backgroundImage = 'url("' + url + '")';
                alvo.innerHTML = '';
            }
        });
    });

    /* ---------- Selects em cascata ---------- */

    async function buscarFilhos(paiId) {
        const resposta = await fetch(baseUrl + 'enderecos/filhos/' + encodeURIComponent(paiId));

        if (!resposta.ok) {
            throw new Error('Falha ao buscar localidades');
        }

        return resposta.json();
    }

    function preencher(select, itens, textoPadrao, comOutro) {
        select.innerHTML = '';

        const padrao = document.createElement('option');
        padrao.value = '';
        padrao.textContent = textoPadrao;
        select.appendChild(padrao);

        itens
            .sort((a, b) => a.nome.localeCompare(b.nome, 'pt-BR'))
            .forEach((item) => {
                const opcao = document.createElement('option');
                opcao.value = item.id;
                opcao.textContent = item.nome;
                select.appendChild(opcao);
            });

        if (comOutro) {
            const outro = document.createElement('option');
            outro.value = 'outro';
            outro.textContent = 'Outro (digitar o nome)';
            select.appendChild(outro);
        }
    }

    document.querySelectorAll('[data-cascata]').forEach((bloco) => {

        const ufSelect = bloco.querySelector('[data-uf]');
        const cidadeSelect = bloco.querySelector('[data-cidade]');
        const bairroSelect = bloco.querySelector('[data-bairro]');
        const outroWrap = bloco.querySelector('[data-bairro-outro]');
        const alvoId = bloco.querySelector('[data-localidade-id]'); // só no formulário de áreas de atuação
        const permiteOutro = bloco.dataset.cascata === 'endereco';

        function atualizarOutro() {
            if (!outroWrap || !bairroSelect) return;

            const mostrar = permiteOutro && (bairroSelect.value === 'outro' || bairroSelect.options.length <= 2);
            outroWrap.style.display = mostrar ? '' : 'none';
        }

        function atualizarLocalidade() {
            if (!alvoId) return;

            const escolhido = [bairroSelect, cidadeSelect, ufSelect]
                .filter(Boolean)
                .map((s) => s.value)
                .find((v) => v && v !== 'outro');

            alvoId.value = escolhido || '';
        }

        ufSelect.addEventListener('change', async () => {
            preencher(cidadeSelect, [], 'Carregando...');

            if (bairroSelect) {
                preencher(bairroSelect, [], bloco.dataset.textoBairro || 'Selecione o bairro...', permiteOutro);
            }

            if (ufSelect.value) {
                try {
                    const filhos = await buscarFilhos(ufSelect.value);
                    preencher(cidadeSelect, filhos.filter((f) => f.tipo === 'MUNICIPIO'), 'Selecione a cidade...');
                } catch (e) {
                    preencher(cidadeSelect, [], 'Não foi possível carregar');
                }
            } else {
                preencher(cidadeSelect, [], 'Selecione a cidade...');
            }

            atualizarOutro();
            atualizarLocalidade();
        });

        cidadeSelect.addEventListener('change', async () => {
            if (bairroSelect) {
                preencher(bairroSelect, [], 'Carregando...', false);

                if (cidadeSelect.value) {
                    try {
                        const filhos = await buscarFilhos(cidadeSelect.value);
                        preencher(bairroSelect, filhos.filter((f) => f.tipo === 'BAIRRO'), bloco.dataset.textoBairro || 'Selecione o bairro...', permiteOutro);
                    } catch (e) {
                        preencher(bairroSelect, [], 'Não foi possível carregar', permiteOutro);
                    }
                } else {
                    preencher(bairroSelect, [], bloco.dataset.textoBairro || 'Selecione o bairro...', permiteOutro);
                }
            }

            atualizarOutro();
            atualizarLocalidade();
        });

        if (bairroSelect) {
            bairroSelect.addEventListener('change', () => {
                atualizarOutro();
                atualizarLocalidade();
            });
        }

        atualizarOutro();
        atualizarLocalidade();

        // Evita enviar "outro" como se fosse um id de bairro.
        const form = bloco.closest('form') || bloco;
        form.addEventListener('submit', (evento) => {
            if (alvoId && !alvoId.value) {
                evento.preventDefault();
                alert('Selecione ao menos o estado que deseja adicionar.');
            }
        });
    });

    /* ---------- Lista de categorias (modal) ---------- */

    document.querySelectorAll('[data-checklist]').forEach((bloco) => {

        const busca = bloco.querySelector('[data-checklist-busca]');
        const itens = Array.from(bloco.querySelectorAll('[data-checklist-item]'));
        const contador = bloco.querySelector('[data-checklist-contador]');

        const normalizar = (t) => t.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

        function atualizarContador() {
            if (contador) {
                contador.textContent = itens.filter((i) => i.querySelector('input').checked).length;
            }
        }

        if (busca) {
            busca.addEventListener('input', () => {
                const termo = normalizar(busca.value.trim());

                itens.forEach((item) => {
                    item.style.display = normalizar(item.textContent).includes(termo) ? '' : 'none';
                });
            });
        }

        itens.forEach((item) => item.querySelector('input').addEventListener('change', atualizarContador));
        atualizarContador();
    });

})();
