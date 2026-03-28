# Âncora

## Observação sobre o build CSS
Esta versão está preparada para deploy estável no EasyPanel usando a folha de estilos consolidada em `public/build/app.css` a partir dos arquivos de `public/assets/css/`. O pipeline Tailwind/daisyUI foi mantido no repositório para evolução posterior, mas o Dockerfile de produção atual não executa `npm run build`, evitando falhas de `@apply` com classes de componente personalizadas.

# Âncora HUB — pacote refatorado para EasyPanel

Este pacote foi preparado a partir da sua fonte original para facilitar:

- deploy em **VPS com EasyPanel**
- migração de **MySQL para PostgreSQL**
- build de estilos com **Tailwind CSS 4 + daisyUI 5**
- publicação via **GitHub + Dockerfile**

## O que já foi ajustado

### Infraestrutura
- `Dockerfile` pronto para App Service do EasyPanel
- configuração Apache em `docker/apache/`
- `.env.example` com base em PostgreSQL
- diretórios persistentes preparados para `storage/` e uploads

### Banco de dados
- núcleo `Database` adaptado para **MySQL e PostgreSQL**
- helpers SQL para diferenças entre os bancos
- ajustes de `lastInsertId()` para PostgreSQL
- ajustes de buscas `LIKE`, datas, dashboards e follow-up
- `UPSERT` de configurações compatível com PostgreSQL

### Front-end
- pipeline com Tailwind + daisyUI
- `resources/css/app.css` com temas claro/escuro e ponte para o CSS legado
- login e HUB inicial refinados visualmente
- layout principal preparado para novo visual sem quebrar o legado

## Estrutura relevante
- `app/` núcleo do sistema
- `modules/` módulos de negócio (`propostas` e `clientes`)
- `public/` front controller, assets e uploads públicos
- `storage/` logs, sessões e temporários
- `database/migrations/` SQL legado
- `docs/easypanel-deploy.md` passo a passo de deploy
- `database/postgresql/README.md` estratégia de migração MySQL → PostgreSQL

## Importante
A fonte original veio de uma base voltada para hospedagem compartilhada e MySQL. Este pacote já está preparado para o novo caminho, mas a migração ideal continua sendo:

1. subir o app no EasyPanel
2. criar o PostgreSQL
3. importar o legado em um MySQL temporário
4. migrar com `pgloader`
5. validar os fluxos do sistema

## Build de CSS
Durante o deploy pelo Dockerfile, o ambiente de build vai executar o pipeline de CSS.

Localmente, a ideia é usar:

```bash
npm install
npm run build
```

## Próximos arquivos para leitura
- `docs/easypanel-deploy.md`
- `database/postgresql/README.md`
