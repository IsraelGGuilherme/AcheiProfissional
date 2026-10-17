**Rode `php spark migrate` ** Há duas migrations novas, só de `ADD COLUMN` (nenhuma migration antiga foi alterada):
- `2026-10-15-120000_AddDescricaoToProfissionalImagensGaleria.php`: coluna `descricao` (legenda) em `profissional_imagens_galeria`.
- `2026-10-16-100000_AddGaleriaImagemIdToDenuncias.php`: coluna `galeria_imagem_id` em `denuncias` (FK com `ON DELETE SET NULL`).

## Arquivos ALTERADOS (autorizados)
| Arquivo | O que mudou |
|---|---|
| `app/Config/Routes.php` | Acrescentou `'Profissionais'` e `'Perfil'` à lista de arquivos de rota |
| `app/Config/Filters.php` | Registrou o alias `auth` (`AuthFilter`) |
| `app/Controllers/Public/Home.php` | Passou a consultar o banco (busca com filtros e paginação) |
| `app/Views/Public/Home/index.php` | Mesmo layout; filtros viraram formulário GET e os cards vêm do banco |
| `app/Views/Templates/partials/navbar.php` | Link "Meu perfil" quando logado |
| `app/Services/AuthService.php` | `login()` e `montarSessaoUsuario()` passaram a apagar `registro_completo` da sessão. Antes, quem criava ou entrava em outra conta no mesmo navegador sem clicar em "Sair" herdava a marca da conta anterior: o `RegistrationFlowFilter` pulava as etapas e a conta nova ficava `PENDENTE` para sempre (e não aparecia na busca) |
| `app/Controllers/Auth/Register.php` | Os dois cadastros (`registerUsuarioComumDados` e `registerProfissionalDados`) passaram a validar e salvar `foto_perfil` (e `foto_capa`, no profissional), opcionais, com o `ImagemUploadService`. Dois métodos novos (`regrasFotosOpcionais`, `salvarFotosDoCadastro`). Uma falha ao salvar foto não impede o cadastro |

## Arquivos NOVOS
- Fotos nas avaliações: `Services/AvaliacaoService.php`, `Models/AvaliacaoImagensModel.php` (a tabela `avaliacoes_imagens` já existia; não precisa de migration)
- Galeria de trabalhos: `Controllers/Perfil/Galeria.php`, `Services/GaleriaService.php`, `Models/GaleriaModel.php` e a migration acima
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
| `POST /profissionais/{slug}/denunciar` | qualquer usuário logado (perfil, avaliação ou foto da galeria) |
| `GET /perfil` | logado (redireciona conforme o tipo) |
| `GET/POST /perfil/profissional` e subrotas (`categorias`, `endereco`, `locais`, `locais/{id}/remover`) | profissional |
| `GET/POST /perfil/contratante` e `endereco` | usuário comum |
| `POST /perfil/profissional/galeria`, `.../galeria/{id}/legenda`, `.../galeria/{id}/remover` | profissional |
| `POST /perfil/excluir-conta` | qualquer usuário logado |

Todos os POSTs novos passam pelo filtro `csrf`.

## Decisões para encaixar as views no banco
- **Sem etapa de ativação.** O profissional aparece na busca e tem página pública assim que o cadastro está completo
  (tem categoria, endereço, área de atuação e horário) e a conta não está `BLOQUEADO`. O status `PENDENTE`/`ATIVO` deixou
  de decidir a visibilidade (a regra está em `BuscaProfissionaisService`, calculada a partir dos dados).
- **Para o admin:** `Services/ContaService.php` tem `bloquear($id)` (some da busca, a página pública dá 404 e o login é
  encerrado), `desbloquear($id)` e `excluir($id)` (exclusão definitiva, já tratando a FK `RESTRICT` de `enderecos` e as imagens).
- Usuário logado que abre a busca sem escolher cidade vê, por padrão, os profissionais da cidade do endereço dele (com aviso e link "ver todas as cidades"). Se ele escolher outra cidade ou "Todas", a escolha é respeitada; anônimos veem tudo.
- Filtro de cidade/bairro considera o endereço do profissional **e** as áreas de atuação (cidade, bairro ou estado).
- Avaliação: uma por contratante por profissional (reenviar atualiza a anterior). O nome do autor aparece como "Maria S.".
- **Denúncia de avaliação e de foto:** além do perfil, dá para denunciar uma avaliação (`denuncias.avaliacao_id`; as fotos da avaliação vão junto) e uma
  foto da galeria (`denuncias.galeria_imagem_id`, coluna nova). Cada alvo tem a sua própria denúncia em aberto por usuário. Não pode denunciar a
  própria avaliação, o próprio perfil nem as próprias fotos. O profissional pode denunciar avaliações que recebeu. Na página pública há o botão
  "Denunciar" em cada avaliação e na ampliação das fotos (só para quem está logado).
- **Atenção para o admin:** a FK original `denuncias.avaliacao_id` é `ON DELETE CASCADE`. Ao excluir uma avaliação denunciada
  (`AvaliacaoService::excluirAvaliacao`), as denúncias dela somem junto. Se quiserem manter o histórico, marquem a denúncia como `RESOLVIDA` antes
  de excluir a avaliação, ou troquem essa FK para `SET NULL` com uma migration. Já a denúncia de foto da galeria permanece quando a foto é removida.
- Denúncia: sem anexo (a tabela `denuncias` não tem coluna para isso). O motivo "anúncio suspeito" grava `FRAUDE`.
  Denúncia repetida enquanto a anterior está `PENDENTE`/`EM_ANALISE` é ignorada.
- Contratante sem "Pessoa Jurídica" (a tabela `usuarios_comuns` só tem CPF).
- Fotos: JPG/PNG/WEBP até 2MB, salvas em `public/uploads/perfil` e `public/uploads/capas` (no cadastro do profissional e em "Meu perfil"). Convém ignorar `public/uploads/` no git.
- Não é possível ficar sem categoria nem sem área de atuação (o filtro de cadastro exige as duas).
- O `slug` não muda ao editar o nome, para não quebrar links compartilhados.
- Excluir conta (profissional ou comum), via `ContaService::excluir()`: exige a senha atual e uma confirmação; a exclusão é definitiva (as tabelas não têm `deleted_at`).
  O banco apaga em cascata perfil, categorias, horários, áreas, galeria, favoritos e avaliações recebidas; os endereços são apagados pelo controller
  (a FK é `RESTRICT`) e as fotos saem do disco. Avaliações feitas por um contratante permanecem, sem nome ("Usuário"), e as denúncias permanecem
  sem vínculo com a conta apagada, para o admin.
- **Galeria de trabalhos:** até 12 fotos por profissional, cada uma com legenda opcional (até 300 caracteres), no "Meu perfil".
  Aparece na página pública em "Trabalhos realizados" (grade, mais nova primeiro, ampliação com setas e Esc). As fotos são reduzidas
  a no máximo 1600 px no maior lado e as de celular giradas pelo EXIF são endireitadas (precisa da extensão GD do PHP; sem ela a foto é salva do jeito que veio).
  O admin pode tirar uma foto denunciada com `GaleriaService::remover($fotoId)`.
- **Fotos nas avaliações:** o contratante pode anexar até 4 fotos (JPG/PNG/WEBP, 2MB cada, reduzidas a 1280 px) ao avaliar. Ao editar a
  avaliação ele pode remover fotos e adicionar outras (o total continua limitado a 4). As fotos aparecem como miniaturas na avaliação e abrem
  ampliadas, com setas só entre as fotos daquela avaliação. Tudo é validado antes de gravar: se uma foto for inválida, nada é salvo.
  Ao excluir a conta, as fotos que o contratante enviou saem do banco e do disco (a avaliação em si permanece, sem nome); ao excluir um profissional,
  as fotos das avaliações que ele recebeu também são apagadas do disco.
  Para o admin: `AvaliacaoService::excluirFoto($fotoId)` e `AvaliacaoService::excluirAvaliacao($avaliacaoId)`.
- Horários continuam sendo editados na tela original `profissional/horarios` (é preciso informar todos de novo).