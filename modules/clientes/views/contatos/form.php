<section class="page-header">
    <h1 class="page-title"><?= $item ? 'Editar contato' : 'Novo contato'; ?></h1>
    <p class="page-subtitle">Cadastro reaproveitável para uso em condomínios e unidades.</p>
</section>
<?php View::partial('clientes/views/partials/entity_form', [
    'currentRoute' => $currentRoute,
    'item' => $item,
    'attachments' => $attachments,
    'timeline' => $timeline,
    'action' => $item ? base_url('/clientes/contatos/' . $item['id'] . '/update') : base_url('/clientes/contatos/store'),
    'backUrl' => base_url('/clientes/contatos'),
]); ?>
