<?php
declare(strict_types=1);

final class ProposalDocumentOption
{
    private static function moneyToDecimal(mixed $value): ?float
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $value = str_replace(['R$', ' '], '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        if (!is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

    public static function allByDocument(int $documentId): array
    {
        $stmt = Database::connection()->prepare('
            SELECT * FROM proposal_document_options
            WHERE proposal_document_id = :document_id
            ORDER BY sort_order ASC, id ASC
        ');
        $stmt->execute(['document_id' => $documentId]);
        return $stmt->fetchAll();
    }

    public static function deleteByDocument(int $documentId): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM proposal_document_options WHERE proposal_document_id = :document_id');
        return $stmt->execute(['document_id' => $documentId]);
    }

    public static function createMany(int $documentId, array $options): void
    {
        $sql = 'INSERT INTO proposal_document_options (
                    proposal_document_id, sort_order, title, scope_title,
                    scope_html, fee_label, amount_value, amount_text,
                    payment_terms, is_recommended
                ) VALUES (
                    :proposal_document_id, :sort_order, :title, :scope_title,
                    :scope_html, :fee_label, :amount_value, :amount_text,
                    :payment_terms, :is_recommended
                )';

        $stmt = Database::connection()->prepare($sql);

        foreach ($options as $index => $option) {
            $title = trim((string) ($option['title'] ?? ''));
            $scopeTitle = trim((string) ($option['scope_title'] ?? ''));
            $scopeHtml = trim((string) ($option['scope_html'] ?? ''));
            $feeLabel = trim((string) ($option['fee_label'] ?? ''));
            $amountText = trim((string) ($option['amount_text'] ?? ''));
            $paymentTerms = trim((string) ($option['payment_terms'] ?? ''));

            if (
                $title === '' &&
                $scopeTitle === '' &&
                $scopeHtml === '' &&
                $feeLabel === '' &&
                $amountText === '' &&
                $paymentTerms === '' &&
                self::moneyToDecimal($option['amount_value'] ?? null) === null
            ) {
                continue;
            }

            $stmt->execute([
                'proposal_document_id' => $documentId,
                'sort_order' => $index + 1,
                'title' => $title,
                'scope_title' => $scopeTitle !== '' ? $scopeTitle : null,
                'scope_html' => $scopeHtml !== '' ? $scopeHtml : null,
                'fee_label' => $feeLabel !== '' ? $feeLabel : null,
                'amount_value' => self::moneyToDecimal($option['amount_value'] ?? null),
                'amount_text' => $amountText !== '' ? $amountText : null,
                'payment_terms' => $paymentTerms !== '' ? $paymentTerms : null,
                'is_recommended' => !empty($option['is_recommended']) ? 1 : 0,
            ]);
        }
    }
}