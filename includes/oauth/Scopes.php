<?php
/**
 * Definitie van alle ondersteunde OAuth/OIDC scopes.
 *
 * Elke scope heeft een machine-naam, een Nederlands label voor het
 * consent-scherm en een beschrijving voor de gebruiker.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

final class Scopes
{
    public const OPENID            = 'openid';
    public const PROFILE           = 'profile';
    public const EMAIL             = 'email';
    public const OFFLINE_ACCESS    = 'offline_access';

    public const BLOGS_READ        = 'blogs.read';
    public const BLOGS_WRITE       = 'blogs.write';
    public const BLOGS_ADMIN       = 'blogs.admin';
    public const COMMENTS_WRITE    = 'comments.write';
    public const FORUM_WRITE       = 'forum.write';
    public const PARTIJMETER_WRITE = 'partijmeter.write';
    public const MEDIA_WRITE       = 'media.write';
    public const NEWSLETTER_WRITE  = 'newsletter.write';
    public const POLLS_WRITE       = 'polls.write';
    public const ANALYTICS_READ    = 'analytics.read';
    public const MCP_READ          = 'mcp.read';
    public const MCP_WRITE         = 'mcp.write';

    /** @return array<string, array{label:string, description:string}> */
    public static function definitions(): array
    {
        return [
            self::OPENID => [
                'label' => 'Identiteit',
                'description' => 'Een unieke identifier van je account delen met deze applicatie.',
            ],
            self::PROFILE => [
                'label' => 'Profielgegevens',
                'description' => 'Je gebruikersnaam en basis profiel delen.',
            ],
            self::EMAIL => [
                'label' => 'E-mailadres',
                'description' => 'Je geverifieerde e-mailadres delen.',
            ],
            self::OFFLINE_ACCESS => [
                'label' => 'Offline toegang',
                'description' => 'De applicatie mag langer namens jou blijven werken via een refresh token.',
            ],
            self::BLOGS_READ => [
                'label' => 'Blogs lezen',
                'description' => 'Publieke blogs ophalen.',
            ],
            self::BLOGS_WRITE => [
                'label' => 'Blogs schrijven',
                'description' => 'Namens jou blogs aanmaken, bewerken en publiceren.',
            ],
            self::BLOGS_ADMIN => [
                'label' => 'Blogs modereren',
                'description' => 'Alle blogs van alle gebruikers beheren, publiceren, inplannen en verwijderen.',
            ],
            self::COMMENTS_WRITE => [
                'label' => 'Reacties plaatsen',
                'description' => 'Namens jou reacties plaatsen op blogs.',
            ],
            self::FORUM_WRITE => [
                'label' => 'Forum gebruiken',
                'description' => 'Namens jou topics en replies plaatsen in het forum.',
            ],
            self::PARTIJMETER_WRITE => [
                'label' => 'PartijMeter resultaten opslaan',
                'description' => 'Namens jou PartijMeter antwoorden en resultaten opslaan.',
            ],
            self::MEDIA_WRITE => [
                'label' => 'Media uploaden',
                'description' => 'Afbeeldingen en andere media uploaden naar de site (bv. blog-afbeeldingen).',
            ],
            self::NEWSLETTER_WRITE => [
                'label' => 'Nieuwsbrief beheren',
                'description' => 'Je eigen nieuwsbrief-aanmelding beheren (aan- of afmelden).',
            ],
            self::POLLS_WRITE => [
                'label' => 'Polls beheren',
                'description' => 'Blogpolls aanmaken en stemmen op polls.',
            ],
            self::ANALYTICS_READ => [
                'label' => 'Analytics lezen',
                'description' => 'Statistieken over blogs, forum en site-activiteit inzien.',
            ],
            self::MCP_READ => [
                'label' => 'MCP: lezen',
                'description' => 'Publieke PolitiekPraat-data lezen via het MCP-protocol.',
            ],
            self::MCP_WRITE => [
                'label' => 'MCP: schrijven',
                'description' => 'Namens jou schrijfacties uitvoeren via het MCP-protocol.',
            ],
        ];
    }

    /** @return string[] */
    public static function supported(): array
    {
        return array_keys(self::definitions());
    }

    /** @return string[] */
    public static function normalize(string $scope): array
    {
        $scopes = preg_split('/\s+/', trim($scope)) ?: [];
        $scopes = array_values(array_unique(array_filter($scopes, static fn($s) => $s !== '')));
        $supported = self::supported();
        return array_values(array_intersect($scopes, $supported));
    }

    public static function format(array $scopes): string
    {
        return implode(' ', $scopes);
    }
}
