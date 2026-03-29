# Guia de Componentes UI: TailwindCSS + DaisyUI

## Visão Geral

Este documento padroniza os componentes visuais do Âncora HUB Modular usando **TailwindCSS** e **DaisyUI**. Todos os componentes devem ser reutilizados nos módulos de **Propostas**, **Clientes**, **Configurações** e **Logs**.

---

## 1. Botões

### Botão Primário (Ação Principal)
```html
<button class="btn btn-primary">
    <i class="fa-solid fa-plus"></i>
    Novo Item
</button>
```

### Botão Secundário (Ação Alternativa)
```html
<button class="btn btn-secondary">
    <i class="fa-solid fa-undo"></i>
    Cancelar
</button>
```

### Botão Perigo (Exclusão/Crítica)
```html
<button class="btn btn-error">
    <i class="fa-solid fa-trash"></i>
    Deletar
</button>
```

### Botão Sucesso (Confirmação)
```html
<button class="btn btn-success">
    <i class="fa-solid fa-check"></i>
    Salvar
</button>
```

### Botão Outline (Menos Destaque)
```html
<button class="btn btn-outline">
    <i class="fa-solid fa-download"></i>
    Exportar
</button>
```

### Botão Pequeno (Tabelas/Listas)
```html
<button class="btn btn-sm btn-ghost">
    <i class="fa-solid fa-edit"></i>
</button>
```

---

## 2. Cards

### Card Padrão
```html
<div class="card bg-base-100 shadow-md border border-base-300">
    <div class="card-body">
        <h2 class="card-title">Título do Card</h2>
        <p>Conteúdo do card aqui.</p>
        <div class="card-actions justify-end">
            <button class="btn btn-primary btn-sm">Ação</button>
        </div>
    </div>
</div>
```

### Card com Header
```html
<div class="card bg-base-100 shadow-md border border-base-300">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <h2 class="card-title">Título</h2>
            <button class="btn btn-ghost btn-sm btn-circle">
                <i class="fa-solid fa-ellipsis-v"></i>
            </button>
        </div>
        <p>Conteúdo aqui.</p>
    </div>
</div>
```

### Card Destacado (KPI)
```html
<div class="card bg-gradient-to-br from-primary/10 to-primary/5 border border-primary/20 shadow-md">
    <div class="card-body">
        <p class="text-sm text-base-content/60">Métrica</p>
        <h2 class="text-3xl font-bold text-primary">1.234</h2>
        <p class="text-xs text-base-content/50">+12% vs. mês anterior</p>
    </div>
</div>
```

---

## 3. Tabelas

### Tabela Responsiva
```html
<div class="overflow-x-auto">
    <table class="table table-compact w-full border border-base-300 rounded-lg">
        <thead class="bg-base-200">
            <tr>
                <th>Coluna 1</th>
                <th>Coluna 2</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr class="hover:bg-base-200 transition-colors">
                <td>Dados 1</td>
                <td>Dados 2</td>
                <td>
                    <button class="btn btn-ghost btn-xs">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="btn btn-ghost btn-xs text-error">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

---

## 4. Formulários

### Input Padrão
```html
<div class="form-control w-full">
    <label class="label">
        <span class="label-text">Nome do Campo</span>
    </label>
    <input type="text" placeholder="Digite aqui..." class="input input-bordered w-full" />
</div>
```

### Input com Erro
```html
<div class="form-control w-full">
    <label class="label">
        <span class="label-text">Email</span>
    </label>
    <input type="email" placeholder="seu@email.com" class="input input-bordered input-error w-full" />
    <label class="label">
        <span class="label-text-alt text-error">Email inválido</span>
    </label>
</div>
```

### Select/Dropdown
```html
<div class="form-control w-full">
    <label class="label">
        <span class="label-text">Selecione uma opção</span>
    </label>
    <select class="select select-bordered w-full">
        <option disabled selected>Escolha uma opção</option>
        <option>Opção 1</option>
        <option>Opção 2</option>
    </select>
</div>
```

### Textarea
```html
<div class="form-control w-full">
    <label class="label">
        <span class="label-text">Observações</span>
    </label>
    <textarea class="textarea textarea-bordered h-24" placeholder="Digite suas observações..."></textarea>
</div>
```

### Checkbox
```html
<div class="form-control">
    <label class="label cursor-pointer">
        <span class="label-text">Concordo com os termos</span>
        <input type="checkbox" class="checkbox checkbox-primary" />
    </label>
</div>
```

### Radio
```html
<div class="form-control">
    <label class="label cursor-pointer">
        <span class="label-text">Opção 1</span>
        <input type="radio" name="opcao" class="radio radio-primary" checked />
    </label>
    <label class="label cursor-pointer">
        <span class="label-text">Opção 2</span>
        <input type="radio" name="opcao" class="radio radio-primary" />
    </label>
</div>
```

---

## 5. Alertas e Notificações

### Alert Sucesso
```html
<div class="alert alert-success shadow-lg">
    <div>
        <i class="fa-solid fa-check-circle"></i>
        <span>Operação realizada com sucesso!</span>
    </div>
</div>
```

### Alert Erro
```html
<div class="alert alert-error shadow-lg">
    <div>
        <i class="fa-solid fa-exclamation-circle"></i>
        <span>Ocorreu um erro ao processar a solicitação.</span>
    </div>
</div>
```

### Alert Aviso
```html
<div class="alert alert-warning shadow-lg">
    <div>
        <i class="fa-solid fa-exclamation-triangle"></i>
        <span>Atenção: Esta ação não pode ser desfeita.</span>
    </div>
</div>
```

### Alert Info
```html
<div class="alert alert-info shadow-lg">
    <div>
        <i class="fa-solid fa-info-circle"></i>
        <span>Informação importante para você.</span>
    </div>
</div>
```

---

## 6. Modais

### Modal Padrão
```html
<dialog id="meuModal" class="modal">
    <div class="modal-box w-11/12 max-w-md">
        <h3 class="font-bold text-lg">Confirmar Ação</h3>
        <p class="py-4">Tem certeza que deseja continuar?</p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-secondary">Cancelar</button>
            </form>
            <button class="btn btn-primary">Confirmar</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>Fechar</button>
    </form>
</dialog>

<button class="btn" onclick="meuModal.showModal()">Abrir Modal</button>
```

---

## 7. Badges e Tags

### Badge Sucesso
```html
<div class="badge badge-success">Ativo</div>
```

### Badge Erro
```html
<div class="badge badge-error">Inativo</div>
```

### Badge Aviso
```html
<div class="badge badge-warning">Pendente</div>
```

### Badge Info
```html
<div class="badge badge-info">Informação</div>
```

### Badge com Ícone
```html
<div class="badge badge-primary gap-2">
    <i class="fa-solid fa-star"></i>
    Premium
</div>
```

---

## 8. Breadcrumb

```html
<div class="breadcrumbs text-sm">
    <ul>
        <li><a href="/hub">Hub</a></li>
        <li><a href="/propostas">Propostas</a></li>
        <li>Detalhes</li>
    </ul>
</div>
```

---

## 9. Tabs

```html
<div class="tabs tabs-bordered">
    <input type="radio" name="my_tabs" class="tab" aria-label="Tab 1" checked />
    <div class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        Conteúdo da aba 1
    </div>

    <input type="radio" name="my_tabs" class="tab" aria-label="Tab 2" />
    <div class="tab-content bg-base-100 border-base-300 rounded-box p-6">
        Conteúdo da aba 2
    </div>
</div>
```

---

## 10. Pagination

```html
<div class="join">
    <button class="join-item btn">«</button>
    <button class="join-item btn btn-active">1</button>
    <button class="join-item btn">2</button>
    <button class="join-item btn">3</button>
    <button class="join-item btn">»</button>
</div>
```

---

## Cores Institucionais

| Uso | Cor | Código |
|-----|-----|--------|
| Primária | Vermelho | `#941415` |
| Primária Focus | Vermelho Escuro | `#b71c1c` |
| Sucesso | Verde | `#10b981` |
| Aviso | Laranja | `#f59e0b` |
| Erro | Vermelho Claro | `#ef4444` |
| Info | Azul | `#3b82f6` |

---

## Convenções de Classe

- **Espaçamento**: Use `p-4`, `m-4`, `gap-4` (múltiplos de 4px)
- **Bordas**: Use `border border-base-300` para consistência
- **Sombras**: Use `shadow-md` ou `shadow-lg` para destaque
- **Responsividade**: Sempre use prefixos `md:`, `lg:` para breakpoints
- **Transições**: Use `transition-all duration-300` para suavidade

---

## Exemplo de Página Completa

```html
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-base-content mb-2">Título da Página</h1>
        <p class="text-base-content/60">Descrição breve da página</p>
    </div>

    <!-- Ações -->
    <div class="flex gap-2 mb-6">
        <button class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            Novo
        </button>
        <button class="btn btn-outline">
            <i class="fa-solid fa-download"></i>
            Exportar
        </button>
    </div>

    <!-- Conteúdo -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card bg-gradient-to-br from-primary/10 to-primary/5 border border-primary/20">
            <div class="card-body">
                <p class="text-sm text-base-content/60">Total</p>
                <h2 class="text-3xl font-bold text-primary">1.234</h2>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="card bg-base-100 shadow-md border border-base-300">
        <div class="card-body">
            <h2 class="card-title">Lista de Itens</h2>
            <div class="overflow-x-auto">
                <table class="table table-compact w-full">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-base-200">
                            <td>Item 1</td>
                            <td><div class="badge badge-success">Ativo</div></td>
                            <td>
                                <button class="btn btn-ghost btn-xs">Editar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
```

---

**Nota**: Todos os componentes devem ser testados em temas light e dark. O DaisyUI cuida automaticamente da adaptação de cores.
