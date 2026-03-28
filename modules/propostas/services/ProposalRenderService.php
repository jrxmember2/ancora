<?php
declare(strict_types=1);

final class ProposalRenderService
{
    private static function publicUrl(?string $path, string $fallback = ''): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return $fallback;
        }

        if (preg_match('~^(https?:)?//~', $path)) {
            return $path;
        }

        return base_url($path);
    }

    public static function buildByPropostaId(int $propostaId): ?array
    {
        $proposta = Proposta::find($propostaId);
        if (!$proposta) {
            return null;
        }

        $document = ProposalDocument::findByPropostaId($propostaId);
        if (!$document) {
            return null;
        }

        $template = ProposalTemplate::find((int) $document['template_id']);
        $options = ProposalDocumentOption::allByDocument((int) $document['id']);

        $brandingLogoDark = Setting::get('branding_logo_dark_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
        $brandingLogoLight = Setting::get('branding_logo_light_path', '/imgs/logomarca.svg') ?: '/imgs/logomarca.svg';
        $premiumLogoVariant = Setting::get('branding_premium_logo_variant', 'light') ?: 'light';
        $premiumLogoVariant = $premiumLogoVariant === 'dark' ? 'dark' : 'light';

        $coverImageUrl = self::publicUrl($document['cover_image_path'] ?? '', '');
        $rebecaImageUrl = base_url('/assets/imgs/templates/rebeca-premium.png');

        $updatedAt = $document['updated_at'] ?? $document['created_at'] ?? $proposta['updated_at'] ?? $proposta['created_at'] ?? date('Y-m-d H:i:s');

        return [
            'branding' => [
                'company_name' => Setting::get('app_company', 'Empresa') ?: 'Empresa',
                'company_address' => Setting::get('company_address', '') ?: '',
                'company_phone' => Setting::get('company_phone', '(27) 9.9603-4719') ?: '(27) 9.9603-4719',
                'company_email' => Setting::get('company_email', 'contato@rebecamedina.com.br') ?: 'contato@rebecamedina.com.br',
                'company_website' => Setting::get('company_website', 'www.rebecamedina.com.br') ?: 'www.rebecamedina.com.br',
                'company_social_primary' => Setting::get('company_social_primary', '@rebecamedina.oficial') ?: '@rebecamedina.oficial',
                'company_social_secondary' => Setting::get('company_social_secondary', '@rebecamedinaadvocacia') ?: '@rebecamedinaadvocacia',
                'logo_light' => self::publicUrl($brandingLogoLight, base_url('/imgs/logomarca.svg')),
                'logo_dark' => self::publicUrl($brandingLogoDark, base_url('/imgs/logomarca.svg')),
                'premium_logo_variant' => $premiumLogoVariant,
                'logo_premium' => self::publicUrl(
                    $premiumLogoVariant === 'dark' ? $brandingLogoDark : $brandingLogoLight,
                    base_url('/imgs/logomarca.svg')
                    ),
            ],
            'assets' => [
                'cover_image_url' => $coverImageUrl,
                'rebeca_image_url' => $rebecaImageUrl,
            ],
            'meta' => [
                'updated_at' => $updatedAt,
                'updated_at_br' => date('d/m/Y', strtotime($updatedAt)),
            ],
            'proposta' => $proposta,
            'document' => $document,
            'template' => $template,
            'options' => $options,
        ];
    }
}