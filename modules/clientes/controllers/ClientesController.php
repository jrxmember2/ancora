<?php
declare(strict_types=1);

final class ClientesController extends BaseController
{
    private function requireModule(): void
    {
        require_superadmin();
        require_enabled_module('clientes');
    }

    private function normalizeRows(array $rows, string $key): array
    {
        $result = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $value = trim((string) ($row[$key] ?? ''));
            if ($value === '') {
                continue;
            }
            $clean = array_map(static fn($item) => is_string($item) ? trim($item) : $item, $row);
            $result[] = $clean;
        }
        return $result;
    }

    private function addressFromPost(string $prefix): array
    {
        return [
            'street' => trim((string) $this->post($prefix . '_street', '')),
            'number' => trim((string) $this->post($prefix . '_number', '')),
            'complement' => trim((string) $this->post($prefix . '_complement', '')),
            'neighborhood' => trim((string) $this->post($prefix . '_neighborhood', '')),
            'city' => trim((string) $this->post($prefix . '_city', '')),
            'state' => trim((string) $this->post($prefix . '_state', '')),
            'zip' => trim((string) $this->post($prefix . '_zip', '')),
            'notes' => trim((string) $this->post($prefix . '_notes', '')),
        ];
    }

    private function entityPayloadFromPost(string $scope, string $roleTag = 'outro'): array
    {
        return [
            'entity_type' => $this->post('entity_type', 'pf') === 'pj' ? 'pj' : 'pf',
            'profile_scope' => $scope,
            'role_tag' => trim((string) $this->post('role_tag', $roleTag)) ?: $roleTag,
            'display_name' => trim((string) $this->post('display_name', '')),
            'legal_name' => trim((string) $this->post('legal_name', '')),
            'cpf_cnpj' => trim((string) $this->post('cpf_cnpj', '')),
            'rg_ie' => trim((string) $this->post('rg_ie', '')),
            'gender' => trim((string) $this->post('gender', '')),
            'nationality' => trim((string) $this->post('nationality', '')),
            'birth_date' => trim((string) $this->post('birth_date', '')),
            'profession' => trim((string) $this->post('profession', '')),
            'marital_status' => trim((string) $this->post('marital_status', '')),
            'pis' => trim((string) $this->post('pis', '')),
            'spouse_name' => trim((string) $this->post('spouse_name', '')),
            'father_name' => trim((string) $this->post('father_name', '')),
            'mother_name' => trim((string) $this->post('mother_name', '')),
            'children_info' => trim((string) $this->post('children_info', '')),
            'ctps' => trim((string) $this->post('ctps', '')),
            'cnae' => trim((string) $this->post('cnae', '')),
            'state_registration' => trim((string) $this->post('state_registration', '')),
            'municipal_registration' => trim((string) $this->post('municipal_registration', '')),
            'opening_date' => trim((string) $this->post('opening_date', '')),
            'legal_representative' => trim((string) $this->post('legal_representative', '')),
            'phones' => $this->normalizeRows((array) $this->post('phones', []), 'number'),
            'emails' => $this->normalizeRows((array) $this->post('emails', []), 'email'),
            'primary_address' => $this->addressFromPost('primary_address'),
            'billing_address' => $this->addressFromPost('billing_address'),
            'shareholders' => $this->normalizeRows((array) $this->post('shareholders', []), 'name'),
            'notes' => trim((string) $this->post('notes', '')),
            'description' => trim((string) $this->post('description', '')),
            'is_active' => $this->post('is_active', '1') === '1',
            'inactive_reason' => trim((string) $this->post('inactive_reason', '')),
            'contract_end_date' => trim((string) $this->post('contract_end_date', '')),
            'created_by' => auth_id() ?? 0,
            'updated_by' => auth_id() ?? 0,
        ];
    }

    private function validateEntity(array $data): array
    {
        $errors = [];
        if ($data['display_name'] === '') {
            $errors[] = 'Informe o nome principal.';
        }
        if ($data['entity_type'] === 'pf' && $data['profile_scope'] === 'avulso' && $data['cpf_cnpj'] === '') {
            $errors[] = 'Para cliente avulso PF, o CPF é obrigatório.';
        }
        if ($data['entity_type'] === 'pj' && $data['profile_scope'] === 'avulso' && $data['cpf_cnpj'] === '') {
            $errors[] = 'Para cliente avulso PJ, o CNPJ é obrigatório.';
        }
        if (!$data['is_active'] && $data['inactive_reason'] === '') {
            $errors[] = 'Informe o motivo da inativação.';
        }
        return $errors;
    }

    private function recordTimeline(string $relatedType, int $relatedId, string $note): void
    {
        $user = auth_user();
        if (!$user) {
            return;
        }
        ClientTimeline::create([
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'note' => $note,
            'user_id' => (int) $user['id'],
            'user_email' => (string) $user['email'],
        ]);
    }

    private function uploadAttachments(string $relatedType, int $relatedId): void
    {
        if (empty($_FILES['attachments']) || !isset($_FILES['attachments']['name']) || !is_array($_FILES['attachments']['name'])) {
            return;
        }

        $uploadDir = ROOT_PATH . '/storage/uploads/clientes/' . $relatedType . '/' . $relatedId;
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return;
        }

        $names = $_FILES['attachments']['name'];
        $tmpNames = $_FILES['attachments']['tmp_name'];
        $sizes = $_FILES['attachments']['size'];
        $errors = $_FILES['attachments']['error'];
        $types = $_FILES['attachments']['type'];
        $roles = (array) ($_POST['attachment_role'] ?? []);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        foreach ($names as $index => $originalName) {
            if (($errors[$index] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }
            $originalName = (string) $originalName;
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $role = $roles[$index] ?? 'documento';
            $allowed = ['pdf', 'png', 'jpg', 'jpeg'];
            if (!in_array($extension, $allowed, true)) {
                continue;
            }
            if ($role === 'contrato' && $extension !== 'pdf') {
                continue;
            }
            $size = (int) ($sizes[$index] ?? 0);
            if ($size <= 0 || $size > 8 * 1024 * 1024) {
                continue;
            }
            $storedName = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
            $destination = $uploadDir . '/' . $storedName;
            if (!move_uploaded_file((string) $tmpNames[$index], $destination)) {
                continue;
            }
            $mime = $finfo ? (string) finfo_file($finfo, $destination) : ((string) ($types[$index] ?? 'application/octet-stream'));
            ClientAttachment::create([
                'related_type' => $relatedType,
                'related_id' => $relatedId,
                'file_role' => in_array($role, ['documento', 'contrato', 'outro'], true) ? $role : 'documento',
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'relative_path' => str_replace(ROOT_PATH, '', $destination),
                'mime_type' => $mime,
                'file_size' => $size,
                'uploaded_by' => auth_id() ?? 0,
            ]);
        }

        if ($finfo) {
            finfo_close($finfo);
        }
    }

    private function commonViewData(): array
    {
        return [
            'condominiumTypes' => ClientType::allByScope('condominium'),
            'unitTypes' => ClientType::allByScope('unit'),
            'entitiesAll' => ClientEntity::dropdown(),
            'syndics' => ClientEntity::dropdown(['role_tag' => 'sindico']),
            'administradorasList' => ClientEntity::dropdown(['role_tag' => 'administradora']),
            'condominiumsDropdown' => ClientCondominium::dropdown(),
        ];
    }

    public function index(): void
    {
        $this->requireModule();
        $this->view('clientes/views/index', array_merge([
            'title' => 'Clientes',
            'currentRoute' => 'clientes',
            'entityCounts' => ClientEntity::counts(),
            'condominiumCounts' => ClientCondominium::counts(),
            'unitCounts' => ClientUnit::counts(),
            'recentEntities' => array_slice(ClientEntity::allFiltered(), 0, 8),
            'recentCondominiums' => array_slice(ClientCondominium::allFiltered(), 0, 6),
        ], $this->commonViewData()));
    }

    public function avulsos(): void
    {
        $this->requireModule();
        $filters = [
            'q' => trim((string) $this->get('q', '')),
            'entity_type' => trim((string) $this->get('entity_type', '')),
            'is_active' => $this->get('is_active', ''),
            'profile_scope' => 'avulso',
        ];
        $items = ClientEntity::allFiltered($filters);
        $this->view('clientes/views/avulsos/index', [
            'title' => 'Clientes avulsos',
            'currentRoute' => 'clientes/avulsos',
            'items' => $items,
            'filters' => $filters,
        ]);
    }

    public function avulsoCreate(): void
    {
        $this->requireModule();
        $this->view('clientes/views/avulsos/form', [
            'title' => 'Novo cliente avulso',
            'currentRoute' => 'clientes/avulsos',
            'item' => null,
            'attachments' => [],
            'timeline' => [],
            'entityRoleFixed' => 'avulso',
        ]);
    }

    public function avulsoStore(): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $payload = $this->entityPayloadFromPost('avulso', 'avulso');
        $errors = $this->validateEntity($payload);
        if ($errors) {
            $_SESSION['flash_error'] = implode(' ', $errors);
            redirect('/clientes/avulsos/novo');
        }
        $duplicates = ClientEntity::duplicateHints(null, $payload['cpf_cnpj'], $payload['display_name']);
        if ($duplicates) {
            $_SESSION['flash_error'] = 'Atenção: já existem registros semelhantes na base. O cadastro foi salvo mesmo assim, conforme regra definida.';
        }
        $id = ClientEntity::create($payload);
        $this->uploadAttachments('entity', $id);
        app_log(auth_id(), auth_user()['email'] ?? '', 'create_client_entity', 'client_entities', $id, 'Cadastro de cliente avulso: ' . $payload['display_name']);
        $this->recordTimeline('entity', $id, 'Cadastro inicial realizado no módulo Clientes.');
        $_SESSION['flash_success'] = 'Cliente avulso cadastrado com sucesso.';
        redirect('/clientes/avulsos/' . $id . '/editar');
    }

    public function avulsoEdit(string $id): void
    {
        $this->requireModule();
        $item = ClientEntity::find((int) $id);
        if (!$item || $item['profile_scope'] !== 'avulso') {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }
        $this->view('clientes/views/avulsos/form', [
            'title' => 'Editar cliente avulso',
            'currentRoute' => 'clientes/avulsos',
            'item' => $item,
            'attachments' => ClientAttachment::listFor('entity', (int) $id),
            'timeline' => ClientTimeline::listFor('entity', (int) $id),
            'entityRoleFixed' => 'avulso',
        ]);
    }

    public function avulsoUpdate(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $payload = $this->entityPayloadFromPost('avulso', 'avulso');
        $errors = $this->validateEntity($payload);
        if ($errors) {
            $_SESSION['flash_error'] = implode(' ', $errors);
            redirect('/clientes/avulsos/' . (int) $id . '/editar');
        }
        ClientEntity::update((int) $id, $payload);
        $this->uploadAttachments('entity', (int) $id);
        $note = trim((string) $this->post('timeline_note', ''));
        if ($note !== '') {
            $this->recordTimeline('entity', (int) $id, $note);
        }
        app_log(auth_id(), auth_user()['email'] ?? '', 'update_client_entity', 'client_entities', (int) $id, 'Atualização de cliente avulso: ' . $payload['display_name']);
        $_SESSION['flash_success'] = 'Cliente atualizado com sucesso.';
        redirect('/clientes/avulsos/' . (int) $id . '/editar');
    }

    public function avulsoDelete(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        ClientEntity::delete((int) $id);
        app_log(auth_id(), auth_user()['email'] ?? '', 'delete_client_entity', 'client_entities', (int) $id, 'Exclusão de cliente avulso');
        $_SESSION['flash_success'] = 'Cliente excluído com sucesso.';
        redirect('/clientes/avulsos');
    }

    public function contatos(): void
    {
        $this->requireModule();
        $filters = [
            'q' => trim((string) $this->get('q', '')),
            'entity_type' => trim((string) $this->get('entity_type', '')),
            'role_tag' => trim((string) $this->get('role_tag', '')),
            'is_active' => $this->get('is_active', ''),
            'profile_scope' => 'contato',
        ];
        $items = ClientEntity::allFiltered($filters);
        $this->view('clientes/views/contatos/index', [
            'title' => 'Contatos reaproveitáveis',
            'currentRoute' => 'clientes/contatos',
            'items' => $items,
            'filters' => $filters,
        ]);
    }

    public function contatoCreate(): void
    {
        $this->requireModule();
        $this->view('clientes/views/contatos/form', [
            'title' => 'Novo contato',
            'currentRoute' => 'clientes/contatos',
            'item' => null,
            'attachments' => [],
            'timeline' => [],
        ]);
    }

    public function contatoStore(): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $payload = $this->entityPayloadFromPost('contato', trim((string) $this->post('role_tag', 'outro')) ?: 'outro');
        $errors = $this->validateEntity($payload);
        if ($errors) {
            $_SESSION['flash_error'] = implode(' ', $errors);
            redirect('/clientes/contatos/novo');
        }
        $id = ClientEntity::create($payload);
        $this->uploadAttachments('entity', $id);
        app_log(auth_id(), auth_user()['email'] ?? '', 'create_contact_entity', 'client_entities', $id, 'Cadastro de contato: ' . $payload['display_name']);
        $this->recordTimeline('entity', $id, 'Contato cadastrado no módulo Clientes.');
        $_SESSION['flash_success'] = 'Contato cadastrado com sucesso.';
        redirect('/clientes/contatos/' . $id . '/editar');
    }

    public function contatoEdit(string $id): void
    {
        $this->requireModule();
        $item = ClientEntity::find((int) $id);
        if (!$item || $item['profile_scope'] !== 'contato') {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }
        $this->view('clientes/views/contatos/form', [
            'title' => 'Editar contato',
            'currentRoute' => 'clientes/contatos',
            'item' => $item,
            'attachments' => ClientAttachment::listFor('entity', (int) $id),
            'timeline' => ClientTimeline::listFor('entity', (int) $id),
        ]);
    }

    public function contatoUpdate(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $payload = $this->entityPayloadFromPost('contato', trim((string) $this->post('role_tag', 'outro')) ?: 'outro');
        $errors = $this->validateEntity($payload);
        if ($errors) {
            $_SESSION['flash_error'] = implode(' ', $errors);
            redirect('/clientes/contatos/' . (int) $id . '/editar');
        }
        ClientEntity::update((int) $id, $payload);
        $this->uploadAttachments('entity', (int) $id);
        $note = trim((string) $this->post('timeline_note', ''));
        if ($note !== '') {
            $this->recordTimeline('entity', (int) $id, $note);
        }
        app_log(auth_id(), auth_user()['email'] ?? '', 'update_contact_entity', 'client_entities', (int) $id, 'Atualização de contato: ' . $payload['display_name']);
        $_SESSION['flash_success'] = 'Contato atualizado com sucesso.';
        redirect('/clientes/contatos/' . (int) $id . '/editar');
    }

    public function contatoDelete(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        ClientEntity::delete((int) $id);
        app_log(auth_id(), auth_user()['email'] ?? '', 'delete_contact_entity', 'client_entities', (int) $id, 'Exclusão de contato');
        $_SESSION['flash_success'] = 'Contato excluído com sucesso.';
        redirect('/clientes/contatos');
    }

    public function condominios(): void
    {
        $this->requireModule();
        $filters = [
            'q' => trim((string) $this->get('q', '')),
            'type_id' => (int) $this->get('type_id', 0),
            'syndico_entity_id' => (int) $this->get('syndico_entity_id', 0),
            'is_active' => $this->get('is_active', ''),
        ];
        $this->view('clientes/views/condominios/index', array_merge([
            'title' => 'Condomínios',
            'currentRoute' => 'clientes/condominios',
            'items' => ClientCondominium::allFiltered($filters),
            'filters' => $filters,
        ], $this->commonViewData()));
    }

    private function condominiumPayloadFromPost(): array
    {
        return [
            'name' => trim((string) $this->post('name', '')),
            'condominium_type_id' => (int) $this->post('condominium_type_id', 0),
            'has_blocks' => $this->post('has_blocks', '0') === '1',
            'cnpj' => trim((string) $this->post('cnpj', '')),
            'cnae' => trim((string) $this->post('cnae', '')),
            'state_registration' => trim((string) $this->post('state_registration', '')),
            'municipal_registration' => trim((string) $this->post('municipal_registration', '')),
            'address' => $this->addressFromPost('address'),
            'syndico_entity_id' => (int) $this->post('syndico_entity_id', 0),
            'administradora_entity_id' => (int) $this->post('administradora_entity_id', 0),
            'bank_details' => trim((string) $this->post('bank_details', '')),
            'characteristics' => trim((string) $this->post('characteristics', '')),
            'is_active' => $this->post('is_active', '1') === '1',
            'inactive_reason' => trim((string) $this->post('inactive_reason', '')),
            'contract_end_date' => trim((string) $this->post('contract_end_date', '')),
            'created_by' => auth_id() ?? 0,
            'updated_by' => auth_id() ?? 0,
            'blocks' => array_values(array_filter(array_map(static fn($v) => trim((string) $v), (array) $this->post('blocks', [])))),
        ];
    }

    public function condominioCreate(): void
    {
        $this->requireModule();
        $this->view('clientes/views/condominios/form', array_merge([
            'title' => 'Novo condomínio',
            'currentRoute' => 'clientes/condominios',
            'item' => null,
            'attachments' => [],
            'timeline' => [],
        ], $this->commonViewData()));
    }

    public function condominioStore(): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $payload = $this->condominiumPayloadFromPost();
        if ($payload['name'] === '') {
            $_SESSION['flash_error'] = 'Informe o nome do condomínio.';
            redirect('/clientes/condominios/novo');
        }
        if (!$payload['is_active'] && $payload['inactive_reason'] === '') {
            $_SESSION['flash_error'] = 'Informe o motivo da inativação.';
            redirect('/clientes/condominios/novo');
        }
        $id = ClientCondominium::create($payload);
        ClientBlock::replaceForCondominium($id, $payload['has_blocks'] ? $payload['blocks'] : []);
        $this->uploadAttachments('condominium', $id);
        app_log(auth_id(), auth_user()['email'] ?? '', 'create_client_condominium', 'client_condominiums', $id, 'Cadastro de condomínio: ' . $payload['name']);
        $this->recordTimeline('condominium', $id, 'Condomínio cadastrado no módulo Clientes.');
        $_SESSION['flash_success'] = 'Condomínio cadastrado com sucesso.';
        redirect('/clientes/condominios/' . $id . '/editar');
    }

    public function condominioEdit(string $id): void
    {
        $this->requireModule();
        $item = ClientCondominium::find((int) $id);
        if (!$item) {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }
        $this->view('clientes/views/condominios/form', array_merge([
            'title' => 'Editar condomínio',
            'currentRoute' => 'clientes/condominios',
            'item' => $item,
            'attachments' => ClientAttachment::listFor('condominium', (int) $id),
            'timeline' => ClientTimeline::listFor('condominium', (int) $id),
        ], $this->commonViewData()));
    }

    public function condominioUpdate(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $payload = $this->condominiumPayloadFromPost();
        if ($payload['name'] === '') {
            $_SESSION['flash_error'] = 'Informe o nome do condomínio.';
            redirect('/clientes/condominios/' . (int) $id . '/editar');
        }
        ClientCondominium::update((int) $id, $payload);
        ClientBlock::replaceForCondominium((int) $id, $payload['has_blocks'] ? $payload['blocks'] : []);
        $this->uploadAttachments('condominium', (int) $id);
        $note = trim((string) $this->post('timeline_note', ''));
        if ($note !== '') {
            $this->recordTimeline('condominium', (int) $id, $note);
        }
        app_log(auth_id(), auth_user()['email'] ?? '', 'update_client_condominium', 'client_condominiums', (int) $id, 'Atualização de condomínio: ' . $payload['name']);
        $_SESSION['flash_success'] = 'Condomínio atualizado com sucesso.';
        redirect('/clientes/condominios/' . (int) $id . '/editar');
    }

    public function condominioDelete(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        ClientCondominium::delete((int) $id);
        app_log(auth_id(), auth_user()['email'] ?? '', 'delete_client_condominium', 'client_condominiums', (int) $id, 'Exclusão de condomínio');
        $_SESSION['flash_success'] = 'Condomínio excluído com sucesso.';
        redirect('/clientes/condominios');
    }

    private function persistInlineEntity(string $modePrefix, string $defaultRole): ?int
    {
        $existingId = (int) $this->post($modePrefix . '_entity_id', 0);
        $mode = $this->post($modePrefix . '_mode', 'existing');
        if ($mode === 'existing') {
            return $existingId ?: null;
        }

        $name = trim((string) $this->post($modePrefix . '_display_name', ''));
        $document = trim((string) $this->post($modePrefix . '_cpf_cnpj', ''));
        if ($name === '' && $document === '') {
            return null;
        }

        $payload = [
            'entity_type' => $this->post($modePrefix . '_entity_type', 'pf') === 'pj' ? 'pj' : 'pf',
            'profile_scope' => 'contato',
            'role_tag' => $defaultRole,
            'display_name' => $name,
            'legal_name' => trim((string) $this->post($modePrefix . '_legal_name', '')),
            'cpf_cnpj' => $document,
            'rg_ie' => '',
            'gender' => '',
            'nationality' => '',
            'birth_date' => '',
            'profession' => '',
            'marital_status' => '',
            'pis' => '',
            'spouse_name' => '',
            'father_name' => '',
            'mother_name' => '',
            'children_info' => '',
            'ctps' => '',
            'cnae' => '',
            'state_registration' => '',
            'municipal_registration' => '',
            'opening_date' => '',
            'legal_representative' => '',
            'phones' => $this->normalizeRows((array) $this->post($modePrefix . '_phones', []), 'number'),
            'emails' => $this->normalizeRows((array) $this->post($modePrefix . '_emails', []), 'email'),
            'primary_address' => [],
            'billing_address' => $this->addressFromPost($modePrefix . '_billing_address'),
            'shareholders' => [],
            'notes' => trim((string) $this->post($modePrefix . '_notes', '')),
            'description' => '',
            'is_active' => true,
            'inactive_reason' => '',
            'contract_end_date' => '',
            'created_by' => auth_id() ?? 0,
            'updated_by' => auth_id() ?? 0,
        ];
        return ClientEntity::create($payload);
    }

    public function unidades(): void
    {
        $this->requireModule();
        $filters = [
            'q' => trim((string) $this->get('q', '')),
            'condominium_id' => (int) $this->get('condominium_id', 0),
            'block_id' => (int) $this->get('block_id', 0),
            'unit_type_id' => (int) $this->get('unit_type_id', 0),
        ];
        $this->view('clientes/views/unidades/index', array_merge([
            'title' => 'Unidades',
            'currentRoute' => 'clientes/unidades',
            'items' => ClientUnit::allFiltered($filters),
            'filters' => $filters,
        ], $this->commonViewData()));
    }

    public function unidadeCreate(): void
    {
        $this->requireModule();
        $this->view('clientes/views/unidades/form', array_merge([
            'title' => 'Nova unidade',
            'currentRoute' => 'clientes/unidades',
            'item' => null,
        ], $this->commonViewData()));
    }

    public function unidadeStore(): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $ownerId = $this->persistInlineEntity('owner', 'proprietario');
        $tenantId = $this->persistInlineEntity('tenant', 'locatario');
        $payload = [
            'condominium_id' => (int) $this->post('condominium_id', 0),
            'block_id' => (int) $this->post('block_id', 0),
            'unit_type_id' => (int) $this->post('unit_type_id', 0),
            'unit_number' => trim((string) $this->post('unit_number', '')),
            'owner_entity_id' => $ownerId,
            'tenant_entity_id' => $tenantId,
            'owner_notes' => trim((string) $this->post('owner_notes', '')),
            'tenant_notes' => trim((string) $this->post('tenant_notes', '')),
            'created_by' => auth_id() ?? 0,
            'updated_by' => auth_id() ?? 0,
        ];
        if ($payload['condominium_id'] <= 0 || $payload['unit_number'] === '') {
            $_SESSION['flash_error'] = 'Informe condomínio e número da unidade.';
            redirect('/clientes/unidades/novo');
        }
        $id = ClientUnit::create($payload);
        app_log(auth_id(), auth_user()['email'] ?? '', 'create_client_unit', 'client_units', $id, 'Cadastro de unidade ' . $payload['unit_number']);
        $this->recordTimeline('unit', $id, 'Unidade cadastrada no módulo Clientes.');
        $_SESSION['flash_success'] = 'Unidade cadastrada com sucesso.';
        redirect('/clientes/unidades/' . $id . '/editar');
    }

    public function unidadeEdit(string $id): void
    {
        $this->requireModule();
        $item = ClientUnit::find((int) $id);
        if (!$item) {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }
        $owner = !empty($item['owner_entity_id']) ? ClientEntity::find((int) $item['owner_entity_id']) : null;
        $tenant = !empty($item['tenant_entity_id']) ? ClientEntity::find((int) $item['tenant_entity_id']) : null;
        $this->view('clientes/views/unidades/form', array_merge([
            'title' => 'Editar unidade',
            'currentRoute' => 'clientes/unidades',
            'item' => $item,
            'owner' => $owner,
            'tenant' => $tenant,
            'timeline' => ClientTimeline::listFor('unit', (int) $id),
        ], $this->commonViewData()));
    }

    public function unidadeUpdate(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $ownerId = $this->persistInlineEntity('owner', 'proprietario');
        $tenantId = $this->persistInlineEntity('tenant', 'locatario');
        $payload = [
            'condominium_id' => (int) $this->post('condominium_id', 0),
            'block_id' => (int) $this->post('block_id', 0),
            'unit_type_id' => (int) $this->post('unit_type_id', 0),
            'unit_number' => trim((string) $this->post('unit_number', '')),
            'owner_entity_id' => $ownerId,
            'tenant_entity_id' => $tenantId,
            'owner_notes' => trim((string) $this->post('owner_notes', '')),
            'tenant_notes' => trim((string) $this->post('tenant_notes', '')),
            'updated_by' => auth_id() ?? 0,
        ];
        if ($payload['condominium_id'] <= 0 || $payload['unit_number'] === '') {
            $_SESSION['flash_error'] = 'Informe condomínio e número da unidade.';
            redirect('/clientes/unidades/' . (int) $id . '/editar');
        }
        ClientUnit::update((int) $id, $payload);
        $note = trim((string) $this->post('timeline_note', ''));
        if ($note !== '') {
            $this->recordTimeline('unit', (int) $id, $note);
        }
        app_log(auth_id(), auth_user()['email'] ?? '', 'update_client_unit', 'client_units', (int) $id, 'Atualização da unidade ' . $payload['unit_number']);
        $_SESSION['flash_success'] = 'Unidade atualizada com sucesso.';
        redirect('/clientes/unidades/' . (int) $id . '/editar');
    }

    public function unidadeDelete(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        ClientUnit::delete((int) $id);
        app_log(auth_id(), auth_user()['email'] ?? '', 'delete_client_unit', 'client_units', (int) $id, 'Exclusão de unidade');
        $_SESSION['flash_success'] = 'Unidade excluída com sucesso.';
        redirect('/clientes/unidades');
    }

    public function config(): void
    {
        $this->requireModule();
        $this->view('clientes/views/config/index', [
            'title' => 'Configurações do módulo Clientes',
            'currentRoute' => 'clientes/config',
            'condominiumTypes' => ClientType::allByScope('condominium'),
            'unitTypes' => ClientType::allByScope('unit'),
        ]);
    }

    public function configTypeStore(): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $scope = $this->post('scope', 'unit');
        $scope = in_array($scope, ['condominium', 'unit'], true) ? $scope : 'unit';
        $name = trim((string) $this->post('name', ''));
        if ($name === '') {
            $_SESSION['flash_error'] = 'Informe o nome do tipo.';
            redirect('/clientes/config');
        }
        ClientType::create($scope, $name);
        app_log(auth_id(), auth_user()['email'] ?? '', 'create_client_type', 'client_types', null, 'Novo tipo em clientes: ' . $scope . ' - ' . $name);
        $_SESSION['flash_success'] = 'Tipo cadastrado com sucesso.';
        redirect('/clientes/config');
    }

    public function attachmentDownload(string $id): void
    {
        $this->requireModule();
        $attachment = ClientAttachment::find((int) $id);
        if (!$attachment) {
            http_response_code(404);
            exit('Arquivo não encontrado.');
        }
        $path = ROOT_PATH . $attachment['relative_path'];
        if (!is_file($path)) {
            http_response_code(404);
            exit('Arquivo não encontrado.');
        }
        header('Content-Type: ' . $attachment['mime_type']);
        header('Content-Length: ' . (string) filesize($path));
        header('Content-Disposition: attachment; filename="' . basename((string) $attachment['original_name']) . '"');
        readfile($path);
        exit;
    }

    public function attachmentDelete(string $id): void
    {
        $this->requireModule();
        $this->validateCsrfOrFail();
        $attachment = ClientAttachment::find((int) $id);
        if ($attachment) {
            $path = ROOT_PATH . $attachment['relative_path'];
            if (is_file($path)) {
                @unlink($path);
            }
            ClientAttachment::delete((int) $id);
        }
        $_SESSION['flash_success'] = 'Anexo removido com sucesso.';
        $referer = $_SERVER['HTTP_REFERER'] ?? base_url('/clientes');
        header('Location: ' . $referer);
        exit;
    }
}
