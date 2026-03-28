# Deploy do Âncora no EasyPanel

## O que este pacote entrega
- suporte a **PostgreSQL** no núcleo PHP
- build de CSS com **Tailwind CSS 4 + daisyUI 5**
- `Dockerfile` pronto para o EasyPanel
- estrutura de persistência para `storage/` e uploads
- visual refinado no shell principal (login, header, hub e componentes base)

## Pré-requisitos
- EasyPanel já funcionando
- domínio `ancora.serratech.tec.br` apontando para a VPS
- repositório Git privado com este projeto

## Serviços que você vai criar no EasyPanel
1. Projeto: `ancora`
2. Serviço `postgres-ancora` do tipo **Postgres**
3. Serviço `app-ancora` do tipo **App** usando este repositório

## Variáveis de ambiente do App
```env
APP_NAME=Âncora
APP_URL=https://ancora.serratech.tec.br
APP_TIMEZONE=America/Sao_Paulo
DEFAULT_MODULE=propostas
DB_CONNECTION=pgsql
DB_HOST=postgres-ancora
DB_PORT=5432
DB_DATABASE=ancora
DB_USERNAME=ancora
DB_PASSWORD=SUA_SENHA
DB_CHARSET=utf8
```

## Mounts recomendados
- `/var/www/html/storage/logs`
- `/var/www/html/storage/sessions`
- `/var/www/html/storage/temp`
- `/var/www/html/public/assets/uploads`

## Domínio
No serviço `app-ancora`:
- adicione `ancora.serratech.tec.br`
- deixe a **Proxy Port** em `80`

## Banco de dados
### Cenário recomendado
Como a fonte original está em MySQL, a rota mais segura é:
1. subir um **MySQL temporário** no EasyPanel
2. importar o dump legado nesse MySQL temporário
3. rodar `pgloader` do MySQL temporário para o `postgres-ancora`
4. apontar o app para o Postgres

Veja `database/postgresql/README.md`.

## Build local opcional
```bash
npm install
npm run build
```
O EasyPanel também vai compilar os assets durante o deploy, via Dockerfile.
