# Guia de Deploy - GitHub + EasyPanel

## 1. Subir para o GitHub

Na pasta do projeto:

```bash
git init
git add .
git commit -m "chore: prepara ancora hub para github e easypanel"
git branch -M main
git remote add origin https://github.com/SEU_USUARIO/ancora-hub.git
git push -u origin main
```

> Não envie `.env`, `node_modules` nem uploads. O `.gitignore` já está preparado.

## 2. Criar o banco no EasyPanel

Crie um serviço de **MariaDB**.

Sugestão:

- Database name: `ancora`
- Username: `ancora`
- Password: senha forte gerada no painel

Guarde:

- host interno do serviço
- porta
- banco
- usuário
- senha

## 3. Criar a aplicação no EasyPanel

Crie um novo **App Service** usando o repositório do GitHub.

### Fonte

- Source: GitHub Repository
- Repository: seu repositório
- Branch: `main`

### Build

Como existe `Dockerfile` na raiz, o EasyPanel pode buildar a imagem diretamente dele.

### Porta

- Container port: `80`

## 4. Variáveis de ambiente no EasyPanel

Adicione estas variáveis:

```env
APP_NAME=Âncora
APP_URL=https://ancora.seudominio.com
APP_TIMEZONE=America/Sao_Paulo
DEFAULT_MODULE=propostas

DB_CONNECTION=mysql
DB_HOST=HOST_INTERNO_DO_MARIADB
DB_PORT=3306
DB_DATABASE=ancora
DB_USERNAME=ancora
DB_PASSWORD=SUA_SENHA
DB_CHARSET=utf8mb4
```

## 5. Domínio

No serviço da aplicação:

- configure o domínio final
- ative HTTPS/SSL

## 6. Importar a base

Após o banco estar no ar, importe o arquivo:

```bash
database/migrations/000_full_install.sql
```

Você pode importar de três formas:

- cliente SQL externo
- terminal do container do banco
- ferramenta de administração conectada ao MariaDB

## 7. Primeiro acesso

Login inicial:

- E-mail: `junior@serratech.br`
- Senha: `Ancora@123`

Troque a senha após entrar.

## 8. Como funciona o build

O `Dockerfile` faz duas etapas:

1. usa Node para gerar `public/assets/css/app.css`
2. sobe PHP 8.4 + Apache com rewrite habilitado

O Apache já está apontando para `public/`.

## 9. Estrutura importante em produção

Persistem no próprio volume do app:

- `storage/`
- `public/uploads/`
- `public/assets/uploads/branding/`

Se quiser um setup mais robusto depois, o próximo passo é separar uploads para volume dedicado ou storage externo.

## 10. Checklist final

- repositório no GitHub criado
- `.env` fora do GitHub
- MariaDB criado no EasyPanel
- variáveis preenchidas
- domínio configurado
- SQL importado
- deploy executado
- login realizado
