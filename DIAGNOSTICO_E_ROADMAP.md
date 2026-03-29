# Diagnóstico Técnico e Roadmap de Evolução: Âncora HUB Modular

Este documento apresenta o diagnóstico detalhado da base atual do projeto **Âncora**, as diretrizes para a modernização visual e arquitetural, e o roadmap estratégico para transformar a plataforma em um produto SaaS jurídico premium.

---

## 1. Diagnóstico da Base Atual

A análise do código-fonte revela uma estrutura sólida, porém com oportunidades claras de profissionalização e modernização.

### 1.1. Arquitetura de Software
*   **Padrão MVC Customizado:** O sistema utiliza uma implementação limpa de MVC em PHP. A separação entre `core` (núcleo), `app` (regras globais) e `modules` (domínios específicos) é adequada para escalabilidade.
*   **Modularidade:** A estrutura de módulos está bem definida. O `module_catalog.php` atua como o "registro da verdade" para o Hub, permitindo o controle de visibilidade e disponibilidade.
*   **Gestão de Estado:** O suporte a temas (light/dark) já está integrado ao banco de dados e à sessão, com persistência em `localStorage`.

### 1.2. Camada de UI/UX (Oportunidade de Melhoria)
*   **CSS Legado:** Atualmente, o sistema depende de arquivos CSS volumosos (`base.css`, `components.css`, `pages.css`) com seletores customizados. Isso dificulta a manutenção e a consistência visual em novos módulos.
*   **Componentização:** Muitos elementos visuais (cards, botões, formulários) são repetidos manualmente nas views, sem uma biblioteca de componentes padronizada.
*   **Responsividade:** Embora exista uma preocupação com o mobile, a falta de um framework utilitário como TailwindCSS torna o ajuste fino de layouts complexos mais trabalhoso.

### 1.3. Funcionalidades Core
*   **Módulo Propostas:** Funcional e bem estruturado, com um submódulo de "Documento Premium" que já possui lógica de renderização complexa.
*   **Módulo Clientes:** Em estágio avançado de modelagem (condomínios, unidades, entidades), pronto para se tornar o "Cérebro" de dados do sistema.

---

## 2. Proposta de Arquitetura Visual (TailwindCSS + DaisyUI)

A migração não será uma substituição destrutiva, mas uma **evolução incremental**.

### 2.1. O Pipeline de Estilos
Para manter a compatibilidade com hospedagens cPanel e VPS, utilizaremos o **Tailwind via CDN (desenvolvimento)** ou **Build Process (produção)**.
*   **TailwindCSS:** Para utilitários de espaçamento, grid, flexbox e tipografia.
*   **DaisyUI:** Para componentes semânticos (modais, dropdowns, badges, tabs) que respeitam o sistema de temas nativo do Tailwind.

### 2.2. Design System Premium
*   **Cores:** O vermelho institucional `#941415` será mapeado como a cor `primary` no tema DaisyUI.
*   **Superfícies:** Uso de sombras suaves (`shadow-sm` a `shadow-lg`) e bordas sutis para criar profundidade, fugindo do aspecto "flat" de CRUDs comuns.
*   **Tipografia:** Manutenção da fonte **Inter**, com escalas tipográficas rigorosas para garantir legibilidade jurídica.

---

## 3. Nova Organização de Componentes

Propomos a criação de uma estrutura de **Partials Reutilizáveis** em `app/views/components/`:

| Componente | Descrição | Tecnologia Base |
| :--- | :--- | :--- |
| `card-module.php` | Card interativo para o Hub Central. | Tailwind + Hover Effects |
| `data-table.php` | Tabela padronizada com ações e status. | DaisyUI Table |
| `form-field.php` | Input com label, validação e ícone. | Tailwind Forms |
| `premium-badge.php` | Indicador visual de status/categoria. | DaisyUI Badge |
| `modal-confirm.php` | Modal de confirmação (ex: logout, exclusão). | DaisyUI Modal |

---

## 4. Estratégia de Migração Incremental

A migração seguirá o princípio de **"Não quebrar o que funciona"**:

1.  **Sprint 0 (Fundação):** Injeção do TailwindCSS e DaisyUI no `layouts/master.php`. Configuração do tema customizado no `tailwind.config.js` (ou via objeto de configuração da CDN).
2.  **Sprint 1 (Shell):** Refatoração do Header e Footer. Implementação do novo Hub Central com cards modernos.
3.  **Sprint 2 (Módulos Ativos):** Atualização das listagens e formulários de Propostas e Clientes.
4.  **Sprint 3 (Premium):** Refinamento do Editor de Documentos e do Preview.

---

## 5. Próximos Passos (Código)

Iniciaremos a execução técnica pela **Fase 1**, focando no `layouts/master.php` e no `hub`.

### 5.1. Preparação do Layout Master
Atualizaremos o `<head>` para incluir as bibliotecas necessárias e preparar as variáveis de tema para o DaisyUI.

### 5.2. Refatoração do Hub
O arquivo `app/views/home/index.php` será transformado em uma grade de cards premium, utilizando o `accent` de cada módulo definido no catálogo.

---
**Âncora HUB Modular** - *Evoluindo a advocacia para o digital com excelência.*
