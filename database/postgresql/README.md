# Migração MySQL -> PostgreSQL

## Estratégia recomendada para o seu caso
Como a base original veio pronta em MySQL e com dados reais, o melhor caminho é usar um MySQL temporário e então migrar para PostgreSQL com `pgloader`.

### Passo 1 - subir um MySQL temporário
No EasyPanel, crie um serviço MySQL ou MariaDB temporário apenas para receber o dump legado.

### Passo 2 - importar o dump atual
Importe no MySQL temporário o arquivo `database/migrations/000_full_install.sql`.

### Passo 3 - instalar pgloader na VPS
```bash
apt-get update && apt-get install -y pgloader
```

### Passo 4 - migrar para o Postgres final
Exemplo:
```bash
pgloader   mysql://MYSQL_USER:MYSQL_PASS@MYSQL_HOST/controlproposta   pgsql://ancora:SENHA@POSTGRES_HOST:5432/ancora
```

### Passo 5 - revisar pontos sensíveis
- enums do MySQL foram tratados no código do app como `VARCHAR`, então não existe dependência rígida de enum no PHP
- datas de follow-up, validade e dashboards já foram ajustadas no código para MySQL/PostgreSQL
- `UPSERT` de configurações (`app_settings`) já foi adaptado para `ON CONFLICT`

## Instalação limpa
Se você quiser fazer uma instalação limpa em PostgreSQL sem migrar dados antigos, ainda será necessário criar manualmente um usuário inicial na tabela `users` ou importar dados mínimos. Este pacote foi preparado principalmente para **migração do sistema existente**.
