<?php $currentRoute = $currentRoute ?? 'clientes'; ?>
<div class="module-subnav mb-24">
    <a class="btn <?= str_starts_with($currentRoute, 'clientes') && $currentRoute === 'clientes' ? 'btn-primary' : 'btn-ghost'; ?>" href="<?= htmlspecialchars(base_url('/clientes')); ?>">Visão geral</a>
    <a class="btn <?= str_starts_with($currentRoute, 'clientes/avulsos') ? 'btn-primary' : 'btn-ghost'; ?>" href="<?= htmlspecialchars(base_url('/clientes/avulsos')); ?>">Avulsos</a>
    <a class="btn <?= str_starts_with($currentRoute, 'clientes/contatos') ? 'btn-primary' : 'btn-ghost'; ?>" href="<?= htmlspecialchars(base_url('/clientes/contatos')); ?>">Contatos</a>
    <a class="btn <?= str_starts_with($currentRoute, 'clientes/condominios') ? 'btn-primary' : 'btn-ghost'; ?>" href="<?= htmlspecialchars(base_url('/clientes/condominios')); ?>">Condomínios</a>
    <a class="btn <?= str_starts_with($currentRoute, 'clientes/unidades') ? 'btn-primary' : 'btn-ghost'; ?>" href="<?= htmlspecialchars(base_url('/clientes/unidades')); ?>">Unidades</a>
    <a class="btn <?= str_starts_with($currentRoute, 'clientes/config') ? 'btn-primary' : 'btn-ghost'; ?>" href="<?= htmlspecialchars(base_url('/clientes/config')); ?>">Tipos</a>
</div>
