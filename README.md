# Âncora HUB Modular

Projeto PHP modular com HUB central, módulo de Propostas e módulo de Clientes.

## O que foi ajustado neste pacote

- build real de **Tailwind CSS + DaisyUI**
- arquivo compilado em `public/assets/css/app.css`
- `package.json`, `tailwind.config.js` e `postcss.config.js`
- `Dockerfile` pronto para **GitHub + EasyPanel**
- Apache configurado para servir `public/` como webroot
- `.gitignore` e `.dockerignore` ajustados
- remoção de `.env` e dump legado com dados reais
- correção de paths quebrados em branding e documento premium
- endurecimento básico de autenticação e checagens de módulo
- `000_full_install.sql` limpo para instalação inicial

## Stack

- PHP 8.4 + Apache
- MariaDB/MySQL
- Tailwind CSS 3
- DaisyUI 4
- JavaScript vanilla

## Estrutura principal

- `app/` núcleo do sistema
- `modules/` módulos de negócio
- `public/` front controller, assets e uploads públicos
- `storage/` logs, sessões e temporários
- `database/migrations/` scripts SQL
- `src/styles/` fonte do CSS do Tailwind
- `docker/` arquivos auxiliares de container

## Desenvolvimento local do frontend

Instale as dependências:

```bash
npm install
```

Gerar CSS:

```bash
npm run build
```

Modo watch:

```bash
npm run dev
```

## Variáveis de ambiente

Use `.env.example` como base.

Campos principais:

```env
APP_NAME="Âncora"
APP_URL="https://ancora.seudominio.com"
APP_TIMEZONE="America/Sao_Paulo"
DEFAULT_MODULE="propostas"

DB_CONNECTION="mysql"
DB_HOST="seu-host"
DB_PORT="3306"
DB_DATABASE="ancora"
DB_USERNAME="ancora"
DB_PASSWORD="senha-forte"
DB_CHARSET="utf8mb4"
```

## Instalação do banco

Importe apenas:

```bash
mysql -h HOST -u USER -p DATABASE < database/migrations/000_full_install.sql
```

### Login inicial

- **E-mail:** `junior@serratech.br`
- **Senha:** `Ancora@123`

Troque a senha logo após o primeiro acesso.

## Deploy com Docker / EasyPanel

O projeto já possui `Dockerfile` na raiz.

O container:

- compila o Tailwind na etapa de build
- sobe PHP 8.4 com Apache
- usa `public/` como DocumentRoot
- cria automaticamente as pastas de `storage/` e `uploads/`

Consulte `GUIA_DEPLOY_GITHUB_EASYPANEL.md` para o passo a passo.
