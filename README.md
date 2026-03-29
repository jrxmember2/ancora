# Âncora - HUB Modular

Projeto PHP modular para deploy em hospedagem compartilhada, com HUB central, módulo Propostas e módulo Clientes.

## Estrutura
- `app/` núcleo do sistema
- `modules/` módulos de negócio
- `public/` front controller, assets e uploads públicos
- `storage/` uploads e sessões
- `database/migrations/` scripts SQL

## Deploy rápido
1. Extraia a pasta `ancora_modular` em `/home/USUARIO/public_html/ancora`
2. Faça o subdomínio apontar para `/home/USUARIO/public_html/ancora/public`
3. Copie `.env.example` para `.env` e ajuste URL e banco
4. Importe `database/migrations/000_full_install.sql`
5. Acesse `/login`

## Usuário inicial
Após importar o dump legado, utilize o usuário já existente da base antiga.
Se for instalação nova sem usuários, insira um manualmente na tabela `users` com senha gerada por `password_hash`.

## Observações
- O build mantém compatibilidade imediata com a base atual de propostas.
- O módulo Clientes já está incluído e habilitado.
- Há script de migração inicial para síndicos/administradoras do legado.
