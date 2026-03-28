<?php
declare(strict_types = 1)
;

final class ProposalDocument
{
    public static function findByPropostaId(int $propostaId): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM proposal_documents WHERE proposta_id = :proposta_id LIMIT 1');
        $stmt->execute(['proposta_id' => $propostaId]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $sql = 'INSERT INTO proposal_documents (
                    proposta_id, template_id, document_title, proposal_kind,
                    client_display_name, attention_to, attention_role, cover_subtitle,
                    intro_context, scope_intro, closing_message, validity_days,
                    show_institutional, show_services, show_extra_services, show_contacts_page,
                    cover_image_path, created_by, updated_by
                ) VALUES (
                    :proposta_id, :template_id, :document_title, :proposal_kind,
                    :client_display_name, :attention_to, :attention_role, :cover_subtitle,
                    :intro_context, :scope_intro, :closing_message, :validity_days,
                    :show_institutional, :show_services, :show_extra_services, :show_contacts_page,
                    :cover_image_path, :created_by, :updated_by
                )';

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($data);

        return Database::lastInsertId('proposal_documents');
    }

    public static function update(int $id, array $data): bool    {
        $sql = 'UPDATE proposal_documents SET
                template_id = :template_id,
                document_title = :document_title,
                proposal_kind = :proposal_kind,
                client_display_name = :client_display_name,
                attention_to = :attention_to,
                attention_role = :attention_role,
                cover_subtitle = :cover_subtitle,
                intro_context = :intro_context,
                scope_intro = :scope_intro,
                closing_message = :closing_message,
                validity_days = :validity_days,
                show_institutional = :show_institutional,
                show_services = :show_services,
                show_extra_services = :show_extra_services,
                show_contacts_page = :show_contacts_page,
                cover_image_path = :cover_image_path,
                updated_by = :updated_by
            WHERE id = :id';

        $stmt = Database::connection()->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'template_id' => $data['template_id'],
            'document_title' => $data['document_title'],
            'proposal_kind' => $data['proposal_kind'],
            'client_display_name' => $data['client_display_name'],
            'attention_to' => $data['attention_to'],
            'attention_role' => $data['attention_role'],
            'cover_subtitle' => $data['cover_subtitle'],
            'intro_context' => $data['intro_context'],
            'scope_intro' => $data['scope_intro'],
            'closing_message' => $data['closing_message'],
            'validity_days' => $data['validity_days'],
            'show_institutional' => $data['show_institutional'],
            'show_services' => $data['show_services'],
            'show_extra_services' => $data['show_extra_services'],
            'show_contacts_page' => $data['show_contacts_page'],
            'cover_image_path' => $data['cover_image_path'],
            'updated_by' => $data['updated_by'],
        ]);    }
}