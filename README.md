# Plataforma de Autônomos

Plataforma web desenvolvida como projeto de extensão acadêmica, com o objetivo de facilitar a conexão entre **clientes e profissionais autônomos**, permitindo que usuários encontrem profissionais, consultem informações sobre seus serviços, localização, horários de atendimento e avaliações.

O projeto é desenvolvido utilizando **PHP com CodeIgniter 4**, seguindo o padrão MVC, e utiliza **MySQL** como banco de dados.

## 🛠️ Tecnologias utilizadas

* PHP 8+
* CodeIgniter 4
* MySQL
* Composer
* Git / GitHub
* HTML, CSS e JavaScript
* Bootstrap

## 📋 Pré-requisitos

Antes de iniciar o projeto, certifique-se de possuir instalado:

* **PHP 8 ou superior**
* **Composer**
* **MySQL 8 ou superior**
* **Git**
* Um servidor web compatível, caso não seja utilizado o servidor embutido do PHP

Também é necessário que as extensões PHP exigidas pelo CodeIgniter 4 estejam habilitadas.

---

## 🚀 Instalação

### 1. Clonar o repositório

Clone o projeto utilizando Git:

```bash
git clone <URL_DO_REPOSITORIO>
```

Entre na pasta do projeto:

```bash
cd <NOME_DO_PROJETO>
```

### 2. Instalar as dependências

Execute:

```bash
composer install
```

Isso instalará as dependências necessárias do CodeIgniter 4.

---

## ⚙️ Configuração do ambiente

O CodeIgniter utiliza um arquivo `.env` para armazenar as configurações específicas do ambiente.

Na raiz do projeto existe um arquivo de exemplo chamado:

```text
env
```

Faça uma cópia dele:

```bash
cp env .env
```

No Windows, também é possível simplesmente copiar o arquivo `env` e renomeá-lo para:

```text
.env
```

### 3. Configurar o `.env`

Abra o arquivo `.env` e configure, inicialmente, o ambiente:

```ini
CI_ENVIRONMENT = development
```

Depois configure a conexão com o banco de dados:

```ini
database.default.hostname = localhost
database.default.database = nome_do_banco
database.default.username = usuario_do_banco
database.default.password = senha_do_banco
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Substitua os valores de acordo com a configuração do seu ambiente.

Por exemplo:

```ini
database.default.hostname = localhost
database.default.database = plataforma_autonomos
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

> **Importante:** o arquivo `.env` não deve ser enviado ao GitHub, pois pode conter informações sensíveis, como credenciais do banco de dados.

---

## 🗄️ Configuração do banco de dados

Antes de executar as migrations, crie o banco de dados no MySQL.

Por exemplo:

```sql
CREATE DATABASE plataforma_autonomos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Depois, confira se o nome utilizado no `.env` corresponde ao banco criado:

```ini
database.default.database = plataforma_autonomos
```

---

## 🧱 Executando as migrations

Com o banco de dados criado e o `.env` configurado, execute as migrations do CodeIgniter.

Na raiz do projeto:

```bash
php spark migrate
```

O CodeIgniter executará as migrations pendentes e criará a estrutura do banco de dados.

Para verificar o estado das migrations:

```bash
php spark migrate:status
```

### Reverter a última migration

Caso seja necessário desfazer a última migration executada:

```bash
php spark migrate:rollback
```

> Tenha cuidado ao utilizar o rollback em um banco que já contenha dados.

---

## 🌱 Executando Seeds

Caso o projeto possua **Seeders** para inserir dados iniciais, eles podem ser executados através do Spark.

Exemplo:

```bash
php spark db:seed NomeDoSeeder
```

Substitua `NomeDoSeeder` pelo nome da classe do Seeder.

---

## ▶️ Executando o projeto

Após configurar o banco e executar as migrations, o projeto pode ser iniciado utilizando o servidor de desenvolvimento do CodeIgniter:

```bash
php spark serve
```

Por padrão, a aplicação ficará disponível em:

```text
http://localhost:8080
```

Para utilizar outra porta:

```bash
php spark serve --port 8081
```

---

## 📁 Estrutura principal do projeto

A estrutura segue o padrão do CodeIgniter 4:

```text
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Database/
│   │   ├── Migrations/
│   │   └── Seeds/
│   ├── Models/
│   ├── Views/
│   └── ...
│
├── public/
├── writable/
├── tests/
├── .env
├── composer.json
├── env
└── spark
```

### Principais diretórios

**`app/`**
Contém o código principal da aplicação.

**`app/Controllers/`**
Contém os controllers responsáveis por receber as requisições e coordenar as operações da aplicação.

**`app/Models/`**
Contém os Models utilizados para comunicação e manipulação dos dados.

**`app/Views/`**
Contém as interfaces e páginas da aplicação.

**`app/Database/Migrations/`**
Contém as migrations responsáveis pela criação e alteração da estrutura do banco de dados.

**`app/Database/Seeds/`**
Contém os Seeders utilizados para inserir dados iniciais no banco.

**`public/`**
Diretório público da aplicação.

**`writable/`**
Diretório utilizado pelo CodeIgniter para arquivos que precisam ser gravados durante a execução da aplicação, como logs e cache.

---

## 🔄 Fluxo para configurar o projeto em uma nova máquina

De forma resumida:

```bash
git clone <URL_DO_REPOSITORIO>
cd <NOME_DO_PROJETO>

composer install

cp env .env
```

Depois:

1. Criar o banco de dados MySQL.
2. Configurar as credenciais no `.env`.
3. Executar as migrations.
4. Executar os Seeders, caso necessários.
5. Iniciar o servidor.

```bash
php spark migrate
php spark db:seed NomeDoSeeder
php spark serve
```

A aplicação estará disponível em:

```text
http://localhost:8080
```

---

## 👥 Desenvolvimento

O projeto é desenvolvido de forma colaborativa utilizando Git e GitHub.

Recomenda-se criar branches para novas funcionalidades e manter commits pequenos e descritivos.

Exemplo:

```bash
git checkout -b feature/nova-funcionalidade
```

Após concluir as alterações:

```bash
git add .
git commit -m "feat: adiciona nova funcionalidade"
git push origin feature/nova-funcionalidade
```

---

## 📄 Licença

Este projeto foi desenvolvido para fins acadêmicos como parte de um projeto de extensão.


## 🎨 Views do protótipo visual

As páginas do protótipo foram convertidas para views PHP em `app/Views` e podem ser acessadas pelas seguintes rotas:

| Página | Rota |
|---|---|
| Busca de profissionais | `/` ou `/busca` |
| Login | `/login` |
| Perfil do contratante | `/perfil/contratante` |
| Perfil do profissional | `/perfil/profissional` |
| Administração | `/admin` |

O controlador `App\Controllers\Visual` renderiza as telas, enquanto `App\Models\DemoDataModel` fornece dados estáticos exclusivamente para a apresentação visual. Não foi implementada persistência, autenticação ou processamento real dos formulários nesta etapa.

Para iniciar o projeto, execute `composer install` e depois `php spark serve`. A aplicação ficará disponível em `http://localhost:8080`.
