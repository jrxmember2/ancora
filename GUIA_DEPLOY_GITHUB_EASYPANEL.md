# Guia Completo de Deploy: GitHub + EasyPanel (VPS Ubuntu 24)

## Índice

1. [Preparação do Repositório GitHub](#1-preparação-do-repositório-github)
2. [Configuração do EasyPanel](#2-configuração-do-easypanel)
3. [Deploy do Projeto](#3-deploy-do-projeto)
4. [Configuração de Banco de Dados](#4-configuração-de-banco-de-dados)
5. [SSL/HTTPS](#5-ssl-https)
6. [Variáveis de Ambiente](#6-variáveis-de-ambiente)
7. [Manutenção e Backup](#7-manutenção-e-backup)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Preparação do Repositório GitHub

### 1.1. Criar o Repositório

1. Acesse [github.com](https://github.com) e faça login
2. Clique em **"New repository"** (botão verde)
3. Preencha os dados:
   - **Repository name**: `ancora-hub-modular`
   - **Description**: `Plataforma SaaS jurídica modular para gestão de propostas, clientes e processos`
   - **Visibility**: `Private` (recomendado para código comercial)
   - **Initialize repository**: Deixe desmarcado (você já tem código local)

4. Clique em **"Create repository"**

### 1.2. Configurar .gitignore

Crie um arquivo `.gitignore` na raiz do projeto com o seguinte conteúdo:

```gitignore
# Ambiente
.env
.env.local
.env.*.local

# Logs
storage/logs/*
!storage/logs/.gitkeep

# Sessions
storage/sessions/*
!storage/sessions/.gitkeep

# Uploads temporários
storage/temp/*
!storage/temp/.gitkeep

# Uploads de usuários (opcional - pode sincronizar via S3)
public/uploads/*
storage/uploads/*
!public/uploads/.gitkeep
!storage/uploads/.gitkeep

# IDE
.vscode/
.idea/
*.swp
*.swo
*~
.DS_Store

# Node modules (se usar Tailwind build)
node_modules/
package-lock.json
yarn.lock

# Composer
vendor/
composer.lock (opcional - para produção, considere versionar)

# Cache
*.cache
.cache/

# Arquivos de sistema
Thumbs.db
.AppleDouble
.LSOverride
```

### 1.3. Inicializar Git Localmente

```bash
cd /home/ubuntu/ancora_work/ancora_modular

# Inicializar repositório
git init

# Adicionar todos os arquivos
git add .

# Commit inicial
git commit -m "Initial commit: Âncora HUB Modular com TailwindCSS + DaisyUI"

# Adicionar remote (substitua SEU_USUARIO)
git remote add origin https://github.com/SEU_USUARIO/ancora-hub-modular.git

# Push para main branch
git branch -M main
git push -u origin main
```

### 1.4. Configurar Deploy Key (SSH)

Para deploy automático do EasyPanel:

```bash
# Gerar chave SSH (se não tiver)
ssh-keygen -t ed25519 -C "easypanel@ancora.local" -f ~/.ssh/easypanel_deploy

# Copiar chave pública
cat ~/.ssh/easypanel_deploy.pub
```

No GitHub:
1. Vá para **Settings** → **Deploy keys** → **Add deploy key**
2. Cole a chave pública
3. Marque **"Allow write access"** (para CI/CD)

---

## 2. Configuração do EasyPanel

### 2.1. Acessar EasyPanel

1. Acesse o painel EasyPanel da sua VPS (geralmente `https://seu-dominio.com:3000`)
2. Faça login com suas credenciais

### 2.2. Criar Aplicação

1. Clique em **"Applications"** → **"New Application"**
2. Preencha os dados:
   - **Name**: `ancora-hub`
   - **Domain**: `ancora.seu-dominio.com` (ou seu domínio)
   - **Repository URL**: `https://github.com/SEU_USUARIO/ancora-hub-modular.git`
   - **Branch**: `main`
   - **Build Command**: (deixe vazio para PHP)
   - **Start Command**: (deixe vazio para PHP)

3. Clique em **"Create"**

### 2.3. Configurar Servidor Web

1. No EasyPanel, vá para **Applications** → **ancora-hub** → **Settings**
2. Selecione **"Nginx"** como servidor web
3. Configure o **Root Path**: `/public`
4. Ative **"PHP"** e selecione versão **8.1+**

---

## 3. Deploy do Projeto

### 3.1. Deploy Manual via Git

```bash
# SSH para a VPS
ssh root@seu-vps-ip

# Navegar para o diretório de aplicações
cd /var/www/ainda-hub

# Clonar repositório
git clone https://github.com/SEU_USUARIO/ancora-hub-modular.git .

# Instalar dependências PHP
composer install --no-dev --optimize-autoloader

# Definir permissões
chmod -R 755 storage/
chmod -R 755 public/uploads/
chown -R www-data:www-data .
```

### 3.2. Deploy Automático via EasyPanel

1. No EasyPanel, vá para **Applications** → **ancora-hub** → **Deployments**
2. Clique em **"Deploy Now"** para fazer o primeiro deploy
3. Configure **Webhooks** no GitHub para deploy automático:
   - Vá para **GitHub** → **Settings** → **Webhooks** → **Add webhook**
   - **Payload URL**: `https://easypanel.seu-dominio.com/webhooks/github`
   - **Content type**: `application/json`
   - **Events**: Selecione `Push events`

---

## 4. Configuração de Banco de Dados

### 4.1. Criar Banco de Dados no EasyPanel

1. Vá para **Databases** → **New Database**
2. Selecione **MySQL 8.0**
3. Preencha:
   - **Name**: `ancora_db`
   - **Username**: `ancora_user`
   - **Password**: (gere uma senha forte)

4. Clique em **"Create"**

### 4.2. Executar Migrations

```bash
# SSH para a VPS
ssh root@seu-vps-ip

# Navegar para o projeto
cd /var/www/ancora-hub

# Executar migrations
php scripts/bootstrap.php

# Ou manualmente via MySQL
mysql -u ancora_user -p ancora_db < database/migrations/000_full_install.sql
mysql -u ancora_user -p ancora_db < database/migrations/010_clientes_module.sql
mysql -u ancora_user -p ancora_db < database/migrations/2026_03_module_hub.sql
mysql -u ancora_user -p ancora_db < database/migrations/2026_03_proposta_premium.sql
mysql -u ancora_user -p ancora_db < database/migrations/2026_03_desktop_permissions.sql
```

### 4.3. Criar Usuário Administrativo

```bash
# Conectar ao MySQL
mysql -u ancora_user -p ancora_db

# Executar SQL (substitua os valores)
INSERT INTO users (name, email, password, role, created_at) VALUES (
    'Administrador',
    'admin@seu-dominio.com',
    '$2y$10$...',  -- Hash bcrypt da senha
    'superadmin',
    NOW()
);
```

Para gerar o hash bcrypt, use PHP:
```php
echo password_hash('sua_senha_forte', PASSWORD_BCRYPT);
```

---

## 5. SSL/HTTPS

### 5.1. Configurar SSL no EasyPanel

1. Vá para **Applications** → **ancora-hub** → **SSL**
2. Clique em **"Generate Certificate"** (Let's Encrypt)
3. Selecione seu domínio
4. Clique em **"Issue Certificate"**

O EasyPanel renovará automaticamente a cada 90 dias.

### 5.2. Redirecionar HTTP para HTTPS

No EasyPanel, vá para **Applications** → **ancora-hub** → **Settings**:
- Ative **"Force HTTPS"**
- Ative **"Redirect HTTP to HTTPS"**

---

## 6. Variáveis de Ambiente

### 6.1. Criar .env em Produção

SSH para a VPS e crie o arquivo `.env`:

```bash
ssh root@seu-vps-ip
cd /var/www/ancora-hub
nano .env
```

Preencha com:

```env
# Aplicação
APP_NAME="Âncora HUB Modular"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ancora.seu-dominio.com

# Banco de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=ancora_db
DB_USER=ancora_user
DB_PASSWORD=sua_senha_forte_aqui

# Segurança
SESSION_TIMEOUT=3600
CSRF_TOKEN_LENGTH=32

# Email (opcional - para notificações)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.seu-provedor.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@seu-dominio.com
MAIL_PASSWORD=sua_senha_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seu-dominio.com
MAIL_FROM_NAME="Âncora HUB"

# S3/Storage (opcional - para uploads)
STORAGE_DRIVER=local
# Se usar S3:
# AWS_ACCESS_KEY_ID=sua_chave
# AWS_SECRET_ACCESS_KEY=sua_secret
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=seu-bucket
```

Salve com `Ctrl+O`, `Enter`, `Ctrl+X`.

### 6.2. Definir Permissões

```bash
chmod 600 .env
chown www-data:www-data .env
```

---

## 7. Manutenção e Backup

### 7.1. Backup Automático do Banco de Dados

No EasyPanel, vá para **Databases** → **ancora_db** → **Backups**:
- Clique em **"Enable Automatic Backups"**
- Selecione frequência (diária recomendada)
- Configure retenção (30 dias mínimo)

### 7.2. Backup de Arquivos

```bash
# SSH para a VPS
ssh root@seu-vps-ip

# Criar backup
tar -czf /backups/ancora-$(date +%Y%m%d).tar.gz /var/www/ancora-hub

# Enviar para S3 (opcional)
aws s3 cp /backups/ancora-$(date +%Y%m%d).tar.gz s3://seu-bucket/backups/
```

### 7.3. Monitoramento

No EasyPanel:
1. Vá para **Monitoring**
2. Configure alertas para:
   - CPU > 80%
   - Memória > 85%
   - Disco > 90%
   - Erro HTTP 5xx

### 7.4. Logs

```bash
# Ver logs da aplicação
tail -f /var/www/ancora-hub/storage/logs/app.log

# Ver logs do Nginx
tail -f /var/log/nginx/error.log

# Ver logs do PHP
tail -f /var/log/php-fpm.log
```

---

## 8. Troubleshooting

### Problema: "Permission Denied" ao fazer deploy

**Solução:**
```bash
ssh root@seu-vps-ip
chown -R www-data:www-data /var/www/ancora-hub
chmod -R 755 /var/www/ancora-hub
chmod -R 777 /var/www/ancora-hub/storage
```

### Problema: Banco de dados não conecta

**Solução:**
```bash
# Verificar credenciais no .env
cat /var/www/ancora-hub/.env | grep DB_

# Testar conexão
mysql -h localhost -u ancora_user -p -e "SELECT 1"
```

### Problema: Erro 500 após deploy

**Solução:**
```bash
# Verificar logs
tail -f /var/www/ancora-hub/storage/logs/app.log

# Limpar cache (se houver)
rm -rf /var/www/ancora-hub/storage/cache/*

# Reexecutar migrations
php /var/www/ancora-hub/scripts/bootstrap.php
```

### Problema: Uploads não funcionam

**Solução:**
```bash
# Verificar permissões
chmod -R 777 /var/www/ancora-hub/public/uploads
chmod -R 777 /var/www/ancora-hub/storage/uploads

# Verificar espaço em disco
df -h
```

---

## Checklist de Deploy

- [ ] Repositório criado no GitHub
- [ ] `.gitignore` configurado
- [ ] Código enviado para GitHub
- [ ] Aplicação criada no EasyPanel
- [ ] Banco de dados criado
- [ ] Migrations executadas
- [ ] Usuário admin criado
- [ ] `.env` configurado em produção
- [ ] SSL/HTTPS ativado
- [ ] Webhooks do GitHub configurados
- [ ] Backup automático ativado
- [ ] Monitoramento configurado
- [ ] Domínio apontando para VPS
- [ ] Teste de acesso: `https://ancora.seu-dominio.com`

---

## Próximas Etapas

1. **Monitoramento**: Configure alertas no EasyPanel
2. **CI/CD**: Configure GitHub Actions para testes automáticos
3. **CDN**: Configure Cloudflare para cache estático
4. **Email**: Configure serviço de email para notificações
5. **Backup Externo**: Configure backup em S3 ou Google Cloud Storage

---

**Suporte**: Para dúvidas sobre EasyPanel, consulte [docs.easypanel.io](https://docs.easypanel.io)
