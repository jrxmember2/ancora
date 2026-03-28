<?php
declare(strict_types = 1)
;

$router->get('/', [HomeController::class , 'index']);
$router->get('/home', [HomeController::class , 'index']);
$router->get('/hub', [HomeController::class , 'index']);
$router->get('/desktop', [HomeController::class , 'index']);
$router->get('/login', [AuthController::class , 'showLogin']);
$router->post('/login', [AuthController::class , 'login']);
$router->get('/logout', [AuthController::class , 'logout']);
$router->post('/theme/save', [ThemeController::class , 'save']);

$router->get('/dashboard', [DashboardController::class , 'index']);
$router->get('/dashboard/details', [DashboardController::class , 'details']);

$router->get('/busca', [SearchController::class , 'index']);

$router->get('/propostas', [PropostaController::class , 'index']);
$router->get('/propostas/export/csv', [PropostaController::class , 'exportCsv']);
$router->get('/propostas/nova', [PropostaController::class , 'create']);
$router->post('/propostas/store', [PropostaController::class , 'store']);
$router->get('/propostas/{id}/imprimir', [PropostaController::class , 'printView']);
$router->post('/propostas/{id}/anexos/upload', [PropostaController::class , 'uploadAttachment']);
$router->get('/propostas/{id}/anexos/{attachmentId}/download', [PropostaController::class , 'downloadAttachment']);
$router->post('/propostas/{id}/anexos/{attachmentId}/delete', [PropostaController::class , 'deleteAttachment']);
$router->get('/propostas/{id}', [PropostaController::class , 'show']);
$router->get('/propostas/{id}/editar', [PropostaController::class , 'edit']);
$router->post('/propostas/{id}/update', [PropostaController::class , 'update']);
$router->post('/propostas/{id}/delete', [PropostaController::class , 'delete']);
$router->get('/propostas/{id}/documento', [ProposalDocumentController::class , 'edit']);
$router->post('/propostas/{id}/documento/save', [ProposalDocumentController::class , 'save']);
$router->get('/propostas/{id}/documento/preview', [ProposalDocumentController::class , 'preview']);
$router->get('/propostas/{id}/documento/pdf', [ProposalDocumentController::class , 'print']);

$router->get('/config', [ConfigController::class , 'index']);
$router->post('/config/administradoras/store', [ConfigController::class , 'storeAdministradora']);
$router->post('/config/administradoras/{id}/update', [ConfigController::class , 'updateAdministradora']);
$router->post('/config/administradoras/{id}/delete', [ConfigController::class , 'deleteAdministradora']);
$router->post('/config/servicos/store', [ConfigController::class , 'storeServico']);
$router->post('/config/servicos/{id}/update', [ConfigController::class , 'updateServico']);
$router->post('/config/servicos/{id}/delete', [ConfigController::class , 'deleteServico']);
$router->post('/config/status/store', [ConfigController::class , 'storeStatus']);
$router->post('/config/status/{id}/update', [ConfigController::class , 'updateStatus']);
$router->post('/config/status/{id}/delete', [ConfigController::class , 'deleteStatus']);
$router->post('/config/formas-envio/store', [ConfigController::class , 'storeFormaEnvio']);
$router->post('/config/formas-envio/{id}/update', [ConfigController::class , 'updateFormaEnvio']);
$router->post('/config/formas-envio/{id}/delete', [ConfigController::class , 'deleteFormaEnvio']);
$router->post('/config/usuarios/store', [ConfigController::class , 'storeUsuario']);
$router->post('/config/usuarios/{id}/update', [ConfigController::class , 'updateUsuario']);
$router->post('/config/usuarios/{id}/delete', [ConfigController::class , 'deleteUsuario']);
$router->post('/config/branding/save', [ConfigController::class , 'saveBranding']);
$router->post('/config/favicon/save', [ConfigController::class , 'saveFavicon']);
$router->post('/config/branding/save', [ConfigController::class , 'saveBranding']);
$router->post('/config/favicon/save', [ConfigController::class , 'saveFavicon']);
$router->post('/config/modules/save', [ConfigController::class , 'saveModules']);

$router->get('/logs', [LogController::class , 'index']);

$router->get('/clientes', [ClientesController::class , 'index']);
$router->get('/clientes/avulsos', [ClientesController::class , 'avulsos']);
$router->get('/clientes/avulsos/novo', [ClientesController::class , 'avulsoCreate']);
$router->post('/clientes/avulsos/store', [ClientesController::class , 'avulsoStore']);
$router->get('/clientes/avulsos/{id}/editar', [ClientesController::class , 'avulsoEdit']);
$router->post('/clientes/avulsos/{id}/update', [ClientesController::class , 'avulsoUpdate']);
$router->post('/clientes/avulsos/{id}/delete', [ClientesController::class , 'avulsoDelete']);
$router->get('/clientes/contatos', [ClientesController::class , 'contatos']);
$router->get('/clientes/contatos/novo', [ClientesController::class , 'contatoCreate']);
$router->post('/clientes/contatos/store', [ClientesController::class , 'contatoStore']);
$router->get('/clientes/contatos/{id}/editar', [ClientesController::class , 'contatoEdit']);
$router->post('/clientes/contatos/{id}/update', [ClientesController::class , 'contatoUpdate']);
$router->post('/clientes/contatos/{id}/delete', [ClientesController::class , 'contatoDelete']);
$router->get('/clientes/condominios', [ClientesController::class , 'condominios']);
$router->get('/clientes/condominios/novo', [ClientesController::class , 'condominioCreate']);
$router->post('/clientes/condominios/store', [ClientesController::class , 'condominioStore']);
$router->get('/clientes/condominios/{id}/editar', [ClientesController::class , 'condominioEdit']);
$router->post('/clientes/condominios/{id}/update', [ClientesController::class , 'condominioUpdate']);
$router->post('/clientes/condominios/{id}/delete', [ClientesController::class , 'condominioDelete']);
$router->get('/clientes/unidades', [ClientesController::class , 'unidades']);
$router->get('/clientes/unidades/novo', [ClientesController::class , 'unidadeCreate']);
$router->post('/clientes/unidades/store', [ClientesController::class , 'unidadeStore']);
$router->get('/clientes/unidades/{id}/editar', [ClientesController::class , 'unidadeEdit']);
$router->post('/clientes/unidades/{id}/update', [ClientesController::class , 'unidadeUpdate']);
$router->post('/clientes/unidades/{id}/delete', [ClientesController::class , 'unidadeDelete']);
$router->get('/clientes/config', [ClientesController::class , 'config']);
$router->post('/clientes/config/types/store', [ClientesController::class , 'configTypeStore']);
$router->get('/clientes/anexos/{id}/download', [ClientesController::class , 'attachmentDownload']);
$router->post('/clientes/anexos/{id}/delete', [ClientesController::class , 'attachmentDelete']);
