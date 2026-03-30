<?php
declare(strict_types = 1)
;

final class ProposalDocumentController extends BaseController
{
    public function edit(string $id): void
    {
        require_auth();
        require_enabled_module('propostas');

        $proposta = Proposta::find((int)$id);
        if (!$proposta) {
            http_response_code(404);
            exit('Proposta não encontrada.');
        }

        $document = ProposalDocument::findByPropostaId((int)$id);
        $options = $document ?ProposalDocumentOption::allByDocument((int)$document['id']) : [];
        $templates = ProposalTemplate::allActive();

        $this->view('propostas/views/documentos/edit', [
            'title' => 'Documento Premium',
            'currentRoute' => 'propostas',
            'proposta' => $proposta,
            'document' => $document,
            'options' => $options,
            'templates' => $templates,
        ]);
    }

    public function save(string $id): void
    {
        require_auth();
        require_enabled_module('propostas');
        $this->validateCsrfOrFail();

        $user = $_SESSION['auth_user'] ?? null;
        $userId = (int)($user['id'] ?? 0);

        $existing = ProposalDocument::findByPropostaId((int)$id);

        $payload = [
            'proposta_id' => (int)$id,
            'template_id' => (int)($_POST['template_id'] ?? 0),
            'document_title' => trim((string)($_POST['document_title'] ?? 'Proposta de Honorários')),
            'proposal_kind' => trim((string)($_POST['proposal_kind'] ?? '')),
            'client_display_name' => trim((string)($_POST['client_display_name'] ?? '')),
            'attention_to' => trim((string)($_POST['attention_to'] ?? '')),
            'attention_role' => trim((string)($_POST['attention_role'] ?? '')),
            'cover_subtitle' => trim((string)($_POST['cover_subtitle'] ?? '')),
            'intro_context' => trim((string)($_POST['intro_context'] ?? '')),
            'scope_intro' => trim((string)($_POST['scope_intro'] ?? '')),
            'closing_message' => trim((string)($_POST['closing_message'] ?? '')),
            'validity_days' => (int)($_POST['validity_days'] ?? 30),
            'show_institutional' => !empty($_POST['show_institutional']) ? 1 : 0,
            'show_services' => !empty($_POST['show_services']) ? 1 : 0,
            'show_extra_services' => !empty($_POST['show_extra_services']) ? 1 : 0,
            'show_contacts_page' => !empty($_POST['show_contacts_page']) ? 1 : 0,
            'cover_image_path' => trim((string) ($_POST['cover_image_path'] ?? '')),
            'created_by' => $userId,
            'updated_by' => $userId,
        ];

        if ($existing) {
            ProposalDocument::update((int)$existing['id'], $payload);
            $documentId = (int)$existing['id'];
        }
        else {
            $documentId = ProposalDocument::create($payload);
        }

        $options = $_POST['options'] ?? [];
        ProposalDocumentOption::deleteByDocument($documentId);
        ProposalDocumentOption::createMany($documentId, (array)$options);

        $_SESSION['flash_success'] = 'Documento premium salvo com sucesso.';
        redirect('/propostas/' . (int)$id . '/documento');
    }

    public function preview(string $id): void
    {
        require_auth();
        require_enabled_module('propostas');

        $render = ProposalRenderService::buildByPropostaId((int)$id);
        if (!$render) {
            $_SESSION['flash_error'] = 'Salve o Documento Premium antes de visualizar o preview.';
            redirect('/propostas/' . (int)$id . '/documento');
        }

        $this->view('propostas/views/documentos/preview', [
            'title' => 'Preview da Proposta Premium',
            'currentRoute' => 'propostas',
            'render' => $render,
        ]);
    }

    public function print(string $id): void
    {
        require_auth();
        require_enabled_module('propostas');

        $render = ProposalRenderService::buildByPropostaId((int)$id);
        if (!$render) {
            $_SESSION['flash_error'] = 'Salve o Documento Premium antes de gerar o PDF.';
            redirect('/propostas/' . (int)$id . '/documento');
        }

        require ROOT_PATH . '/modules/propostas/views/documentos/print.php';
    }
}