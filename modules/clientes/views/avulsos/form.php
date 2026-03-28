<section class="page-header">
    <h1 class="page-title"><?= $item ? 'Editar cliente avulso' : 'Novo cliente avulso'; ?></h1>
    <p class="page-subtitle">Cadastro completo com abas lógicas, anexos e timeline.</p>
</section>
<?php View::partial('clientes/views/partials/entity_form', [
    'currentRoute' => $currentRoute,
    'item' => $item,
    'attachments' => $attachments,
    'timeline' => $timeline,
    'entityRoleFixed' => $entityRoleFixed,
    'action' => $item ? base_url('/clientes/avulsos/' . $item['id'] . '/update') : base_url('/clientes/avulsos/store'),
    'backUrl' => base_url('/clientes/avulsos'),
]); ?>
