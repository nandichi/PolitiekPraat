<?php
/**
 * View-helpers voor de Midterms 2026 sectie.
 * Worden geladen door controllers/midterms.php voordat de views renderen.
 */

if (!function_exists('mt_rating_label')) {
    function mt_rating_label(string $rating, bool $short = false): string
    {
        $meta = MidtermsModel::ratingMeta();
        if (!isset($meta[$rating])) {
            return 'Onbekend';
        }
        return $short ? $meta[$rating]['short'] : $meta[$rating]['label'];
    }
}

if (!function_exists('mt_rating_party')) {
    /** @return string|null 'D', 'R' of null */
    function mt_rating_party(string $rating): ?string
    {
        $meta = MidtermsModel::ratingMeta();
        return $meta[$rating]['party'] ?? null;
    }
}

if (!function_exists('mt_party_label')) {
    function mt_party_label(?string $party): string
    {
        switch ($party) {
            case 'D': return 'Democraten';
            case 'R': return 'Republikeinen';
            case 'I': return 'Onafhankelijk';
            default: return 'Onbekend';
        }
    }
}

if (!function_exists('mt_party_from_label')) {
    /** Leid partij (D/R/I) af uit een tekstlabel. */
    function mt_party_from_label(?string $label): ?string
    {
        $l = strtolower((string) $label);
        if (str_contains($l, 'republik') || str_contains($l, 'republic') || $l === 'r' || str_contains($l, 'gop')) {
            return 'R';
        }
        if (str_contains($l, 'democr') || $l === 'd') {
            return 'D';
        }
        if (str_contains($l, 'onafhank') || str_contains($l, 'independ')) {
            return 'I';
        }
        return null;
    }
}

if (!function_exists('mt_party_class')) {
    /** CSS-modifier voor een partij (gebruikt door .mt-party--*). */
    function mt_party_class(?string $party): string
    {
        switch ($party) {
            case 'D': return 'dem';
            case 'R': return 'gop';
            case 'I': return 'ind';
            default: return 'neutral';
        }
    }
}

if (!function_exists('mt_pct')) {
    /**
     * Formatteer een prijs (0..1) of percentage als NL-percentage.
     * Accepteert zowel 0.535 als 53.5.
     */
    function mt_pct($value, int $decimals = 0): string
    {
        $v = (float) $value;
        if ($v <= 1.0) {
            $v *= 100;
        }
        return number_format($v, $decimals, ',', '.') . '%';
    }
}

if (!function_exists('mt_pct_num')) {
    /** Numerieke percentagewaarde (0..100) uit prijs/percentage. */
    function mt_pct_num($value): float
    {
        $v = (float) $value;
        if ($v <= 1.0) {
            $v *= 100;
        }
        return $v;
    }
}

if (!function_exists('mt_date_nl')) {
    function mt_date_nl(?string $date): string
    {
        if (empty($date)) {
            return '';
        }
        $ts = strtotime($date);
        if ($ts === false) {
            return (string) $date;
        }
        $months = [
            1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
            5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
            9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december',
        ];
        return (int) date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}
