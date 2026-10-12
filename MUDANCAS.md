
## Arquivos ALTERADOS (autorizados)
| Arquivo | O que mudou |
|---|---|
| `app/Config/Routes.php` | Acrescentou `'Profissionais'` e `'Perfil'` à lista de arquivos de rota |
| `app/Config/Filters.php` | Registrou o alias `auth` (`AuthFilter`) |
| `app/Controllers/Public/Home.php` | Passou a consultar o banco (busca com filtros e paginação) |
| `app/Views/Public/Home/index.php` | Mesmo layout; filtros viraram formulário GET e os cards vêm do banco |
| `app/Views/Templates/partials/navbar.php` | Link "Meu perfil" quando logado |
| `app/Controllers/Auth/Register.php` | `registerProfissionalDados` passou a validar e salvar `foto_perfil` e `foto_capa` (opcionais) usando o `ImagemUploadService`. Uma falha ao salvar foto não impede o cadastro |

## Arquivos NOVOS
- Rotas: `Config/Routes/Profissionais.php`, `Config/Routes/Perfil.php`
- Filtro: `Filters/AuthFilter.php` (exige login e, opcionalmente, o tipo: `auth:PROFISSIONAL`, `auth:COMUM`)
- Controllers: `Public/Profissionais.php` (perfil público, avaliar, denunciar), `Public/QuemSomos.php`,
  `Perfil/Inicio.php`, `Perfil/PerfilBase.php`, `Perfil/Profissional.php`, `Perfil/Contratante.php`
- Services: `BuscaProfissionaisService.php`, `ImagemUploadService.php`
- Models: `AvaliacoesModel.php`, `DenunciasModel.php`
- Helper: `Helpers/profissional_helper.php`
- Views: `Public/Profissionais/ver.php`, `Public/QuemSomos/index.php`, `Perfil/profissional.php`,
  `Perfil/contratante.php`, `Perfil/partials/endereco.php`, `Templates/partials/flash.php`
- Front: `public/assets/css/modules/profile.css`, `public/assets/js/modules/perfil.js`

## Rotas novas
| Rota | Quem acessa |
|---|---|
| `GET /quemsomos` | todos |
| `GET /profissionais/{slug}` | todos |
| `POST /profissionais/{slug}/avaliar` | usuário comum logado |
| `POST /profissionais/{slug}/denunciar` | qualquer usuário logado |
| `GET /perfil` | logado (redireciona conforme o tipo) |
| `GET/POST /perfil/profissional` e subrotas (`categorias`, `endereco`, `locais`, `locais/{id}/remover`) | profissional |
| `GET/POST /perfil/contratante` e `endereco` | usuário comum |

Todos os POSTs novos passam pelo filtro `csrf`.

## Decisões para encaixar as views no banco
- Só aparecem na busca profissionais com `usuarios.status = 'ATIVO'`.
- Filtro de cidade/bairro considera o endereço do profissional **e** as áreas de atuação (cidade, bairro ou estado).
- Avaliação: uma por contratante por profissional (reenviar atualiza a anterior). O nome do autor aparece como "Maria S.".
- Denúncia: sem anexo (a tabela `denuncias` não tem coluna para isso). O motivo "anúncio suspeito" grava `FRAUDE`.
  Denúncia repetida enquanto a anterior está `PENDENTE`/`EM_ANALISE` é ignorada.
- Contratante sem "Pessoa Jurídica" (a tabela `usuarios_comuns` só tem CPF).
- Fotos: JPG/PNG/WEBP até 2MB, salvas em `public/uploads/perfil` e `public/uploads/capas` (no cadastro do profissional e em "Meu perfil"). Convém ignorar `public/uploads/` no git.
- Não é possível ficar sem categoria nem sem área de atuação (o filtro de cadastro exige as duas).
- O `slug` não muda ao editar o nome, para não quebrar links compartilhados.
- Horários continuam sendo editados na tela original `profissional/horarios` (é preciso informar todos de novo).
