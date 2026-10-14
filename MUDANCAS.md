# Achei Profissional — alterações da etapa "área pública e perfis"

Copie as pastas deste zip por cima do projeto (`app/` e `public/`), mantendo os caminhos.
Nenhuma migration foi criada ou alterada e nenhuma tabela mudou.

## Arquivos ALTERADOS (autorizados)
| Arquivo | O que mudou |
|---|---|
| `app/Config/Routes.php` | Acrescentou `'Profissionais'` e `'Perfil'` à lista de arquivos de rota |
| `app/Config/Filters.php` | Registrou o alias `auth` (`AuthFilter`) |
| `app/Controllers/Public/Home.php` | Passou a consultar o banco (busca com filtros e paginação) |
| `app/Views/Public/Home/index.php` | Mesmo layout; filtros viraram formulário GET e os cards vêm do banco |
| `app/Views/Templates/partials/navbar.php` | Link "Meu perfil" quando logado |
| `app/Controllers/Auth/Register.php` | Os dois cadastros (`registerUsuarioComumDados` e `registerProfissionalDados`) passaram a validar e salvar `foto_perfil` (e `foto_capa`, no profissional), opcionais, com o `ImagemUploadService`. Dois métodos novos (`regrasFotosOpcionais`, `salvarFotosDoCadastro`). Uma falha ao salvar foto não impede o cadastro |

## Arquivos NOVOS
- Rotas: `Config/Routes/Profissionais.php`, `Config/Routes/Perfil.php`
- Filtro: `Filters/AuthFilter.php` (exige login e, opcionalmente, o tipo: `auth:PROFISSIONAL`, `auth:COMUM`)
- Controllers: `Public/Profissionais.php` (perfil público, avaliar, denunciar), `Public/QuemSomos.php`,
  `Perfil/Inicio.php`, `Perfil/PerfilBase.php`, `Perfil/Profissional.php`, `Perfil/Contratante.php`, `Perfil/Conta.php` (excluir conta)
- Services: `BuscaProfissionaisService.php`, `ImagemUploadService.php`
- Models: `AvaliacoesModel.php`, `DenunciasModel.php`
- Helper: `Helpers/profissional_helper.php`
- Views: `Public/Profissionais/ver.php`, `Public/QuemSomos/index.php`, `Perfil/profissional.php`,
  `Perfil/contratante.php`, `Perfil/partials/endereco.php`, `Perfil/partials/excluir_conta.php`, `Templates/partials/flash.php`
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
| `POST /perfil/excluir-conta` | qualquer usuário logado |

Todos os POSTs novos passam pelo filtro `csrf`.

## Decisões para encaixar as views no banco
- Só aparecem na busca profissionais com `usuarios.status = 'ATIVO'`.
- Usuário logado que abre a busca sem escolher cidade vê, por padrão, os profissionais da cidade do endereço dele (com aviso e link "ver todas as cidades"). Se ele escolher outra cidade ou "Todas", a escolha é respeitada; anônimos veem tudo.
- Filtro de cidade/bairro considera o endereço do profissional **e** as áreas de atuação (cidade, bairro ou estado).
- Avaliação: uma por contratante por profissional (reenviar atualiza a anterior). O nome do autor aparece como "Maria S.".
- Denúncia: sem anexo (a tabela `denuncias` não tem coluna para isso). O motivo "anúncio suspeito" grava `FRAUDE`.
  Denúncia repetida enquanto a anterior está `PENDENTE`/`EM_ANALISE` é ignorada.
- Contratante sem "Pessoa Jurídica" (a tabela `usuarios_comuns` só tem CPF).
- Fotos: JPG/PNG/WEBP até 2MB, salvas em `public/uploads/perfil` e `public/uploads/capas` (no cadastro do profissional e em "Meu perfil"). Convém ignorar `public/uploads/` no git.
- Não é possível ficar sem categoria nem sem área de atuação (o filtro de cadastro exige as duas).
- O `slug` não muda ao editar o nome, para não quebrar links compartilhados.
- Excluir conta (profissional ou comum): exige a senha atual e uma confirmação; a exclusão é definitiva (as tabelas não têm `deleted_at`).
  O banco apaga em cascata perfil, categorias, horários, áreas, galeria, favoritos e avaliações recebidas; os endereços são apagados pelo controller
  (a FK é `RESTRICT`) e as fotos saem do disco. Avaliações feitas por um contratante permanecem, sem nome ("Usuário"), e as denúncias permanecem
  sem vínculo com a conta apagada, para o admin.
- Horários continuam sendo editados na tela original `profissional/horarios` (é preciso informar todos de novo).

## Não alterado, mas vale saber
- O `.env` do projeto contém uma senha de app do Gmail em texto: troque-a se o repositório já foi compartilhado.
- As telas de admin/denúncias/categorias da pasta View não foram usadas.
