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
        // CNPJ aceita letras e números (formato alfanumérico): AA.AAA.AAA/AAAA-DD
        cnpj(v) {
            const d = (v || '').toUpperCase().replace(/[^0-9A-Z]/g, '').slice(0, 14);
            let saida = '';

            for (let i = 0; i < d.length; i++) {
                if (i === 2 || i === 5) saida += '.';
                if (i === 8) saida += '/';
                if (i === 12) saida += '-';
                saida += d[i];
            }

            return saida;
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
                campo.setAttribute('inputmode', pj ? 'text' : 'numeric');
            }

            // a verificação de CNPJ só faz sentido para pessoa jurídica
            ['btnVerificarCnpj', 'blocoCnpjFonte'].forEach((id) => {
                const el = document.getElementById(id);
                if (el) el.hidden = !pj;
            });

            const resultado = document.getElementById('cnpjResultado');
            if (resultado) resultado.hidden = true;
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


    /* ---------- Verificação de CNPJ (OpenCNPJ) ---------- */

    (function () {

        const botao     = document.getElementById('btnVerificarCnpj');
        const campo     = document.getElementById('cpf_cnpj');
        const fonte     = document.getElementById('cnpjFonte');
        const resultado = document.getElementById('cnpjResultado');

        if (!botao || !campo || !fonte || !resultado) {
            return;
        }

        const form = campo.closest('form');
        const nomeToken = 'csrf_test_name';

        // Mesmo cálculo do servidor (módulo 11; letras valem o código ASCII menos 48).
        function cnpjValido(texto) {
            const c = (texto || '').toUpperCase().replace(/[^0-9A-Z]/g, '');

            if (!/^[0-9A-Z]{12}[0-9]{2}$/.test(c) || /^(\d)\1{13}$/.test(c)) {
                return false;
            }

            const digito = (base) => {
                let soma = 0;
                for (let i = base.length - 1, p = 0; i >= 0; i--, p++) {
                    soma += (base.charCodeAt(i) - 48) * (2 + (p % 8));
                }
                const resto = soma % 11;
                return resto < 2 ? 0 : 11 - resto;
            };

            return digito(c.slice(0, 12)) === Number(c[12]) && digito(c.slice(0, 13)) === Number(c[13]);
        }

        // O CSRF é regenerado a cada requisição: sem atualizar os campos escondidos, "Salvar" falharia depois da consulta.
        function atualizarToken(novo) {
            if (!novo) return;

            document.querySelectorAll('input[name="' + nomeToken + '"]').forEach((input) => { input.value = novo; });
        }

        function tokenAtual() {
            const input = form ? form.querySelector('input[name="' + nomeToken + '"]') : null;
            return input ? input.value : '';
        }

        function limpar() {
            while (resultado.firstChild) resultado.removeChild(resultado.firstChild);
        }

        function linha(rotulo, valor) {
            if (!valor) return null;

            const div = document.createElement('div');
            const forte = document.createElement('strong');
            forte.textContent = rotulo + ': ';
            div.appendChild(forte);
            div.appendChild(document.createTextNode(valor));

            return div;
        }

        function mostrar(tipo, mensagem, dados) {
            limpar();
            resultado.className = 'ap-cnpj-resultado ' + tipo; // ok | alerta | erro
            resultado.hidden = false;

            const titulo = document.createElement('div');
            titulo.className = 'ap-cnpj-titulo';
            const icone = document.createElement('i');
            icone.className = 'bi ' + (tipo === 'ok' ? 'bi-check-circle-fill' : (tipo === 'alerta' ? 'bi-exclamation-triangle-fill' : 'bi-x-circle-fill'));
            titulo.appendChild(icone);
            titulo.appendChild(document.createTextNode(' ' + mensagem));
            resultado.appendChild(titulo);

            if (!dados || !dados.nome) {
                return;
            }

            const e = dados.endereco || {};
            const rua = [e.logradouro, e.numero].filter(Boolean).join(', ');
            const local = [e.bairro, [e.municipio, e.uf].filter(Boolean).join(' - ')].filter(Boolean).join(' · ');

            [
                linha('Razão social', dados.nome),
                linha('Nome fantasia', dados.fantasia),
                linha('Situação', dados.situacao),
                linha('Endereço', [rua, e.complemento, local, e.cep].filter(Boolean).join(' · ')),
                linha('Fonte', dados.dataset_nome),
            ].forEach((el) => { if (el) resultado.appendChild(el); });

            if (dados.mei === true) {
                const selo = document.createElement('span');
                selo.className = 'ap-cnpj-selo';
                selo.textContent = 'MEI';
                resultado.appendChild(selo);
            }

            if (dados.dataset !== 'receita') {
                const nota = document.createElement('div');
                nota.className = 'ap-help';
                nota.textContent = dados.consta_no_dataset ? 'A empresa também consta nesta base.' : 'A empresa não tem registro nesta base.';
                resultado.appendChild(nota);
            }

            const acoes = document.createElement('div');
            acoes.className = 'ap-cnpj-acoes';

            [['Usar razão social como nome', dados.nome], ['Usar nome fantasia como nome', dados.fantasia]].forEach(([texto, valor]) => {
                if (!valor) return;

                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'ap-btn ap-btn-outline ap-btn-small';
                b.textContent = texto;
                b.addEventListener('click', () => {
                    const nome = document.getElementById('nome');
                    if (nome) nome.value = valor;
                });
                acoes.appendChild(b);
            });

            if (acoes.children.length) resultado.appendChild(acoes);
        }

        botao.addEventListener('click', async () => {

            if (!cnpjValido(campo.value)) {
                mostrar('erro', 'CNPJ inválido. Confira os números digitados.');
                return;
            }

            botao.disabled = true;
            const textoOriginal = botao.innerHTML;
            botao.textContent = 'Consultando...';

            try {
                const corpo = new URLSearchParams();
                corpo.set('cnpj', campo.value);
                corpo.set('dataset', fonte.value);
                corpo.set(nomeToken, tokenAtual());

                const resposta = await fetch(botao.dataset.url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                    body: corpo,
                    credentials: 'same-origin',
                });

                let dados = null;
                try { dados = await resposta.json(); } catch (e) { /* resposta que não é JSON */ }

                if (dados && dados.csrf) {
                    atualizarToken(dados.csrf);
                }

                if (!dados) {
                    mostrar('erro', resposta.status === 403
                        ? 'Sua sessão expirou. Recarregue a página e tente de novo.'
                        : 'Não foi possível consultar agora. Tente novamente.');
                } else if (dados.sucesso) {
                    mostrar('ok', dados.mensagem, dados);
                } else if (dados.codigo === 'inativo') {
                    mostrar('alerta', dados.mensagem, dados);
                } else {
                    mostrar('erro', dados.mensagem || 'Não foi possível consultar agora.');
                }

            } catch (erro) {
                mostrar('erro', 'Sem conexão com o servidor. Tente novamente.');
            } finally {
                botao.disabled = false;
                botao.innerHTML = textoOriginal;
            }
        });

        // Mudou o CNPJ ou a base: o resultado anterior deixa de valer.
        [campo, fonte].forEach((el) => el.addEventListener('input', () => { resultado.hidden = true; }));
        fonte.addEventListener('change', () => { resultado.hidden = true; });

    })();


    /* ---------- Abas do perfil: abrir pela âncora (#galeria, #atuacao...) e lembrar a aba ---------- */

    (function () {

        const abas = document.querySelectorAll('#abasPerfil [data-bs-toggle="tab"]');

        if (!abas.length || !window.bootstrap) {
            return;
        }

        function ativar(hash) {
            if (!hash) return;

            const aba = document.querySelector('#abasPerfil [data-bs-target="' + hash + '"]');

            if (aba) {
                window.bootstrap.Tab.getOrCreateInstance(aba).show();
            }
        }

        // Depois de salvar, o servidor redireciona para /perfil/profissional#galeria (por exemplo): abre essa aba.
        ativar(window.location.hash);

        window.addEventListener('hashchange', () => ativar(window.location.hash));

        // Ao trocar de aba, a URL passa a apontar para ela (recarregar a página mantém a aba).
        abas.forEach((aba) => aba.addEventListener('shown.bs.tab', () => {
            history.replaceState(null, '', aba.dataset.bsTarget);
        }));

    })();

})();
