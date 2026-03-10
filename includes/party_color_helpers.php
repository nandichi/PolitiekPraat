<?php

if (!function_exists('getPartyColor')) {
    function getPartyColor($partyKey) {
        $partyColors = [
            'PVV' => '#0078D7',
            'VVD' => '#FF9900',
            'NSC' => '#4D7F78',
            'BBB' => '#95c119',
            'GL-PvdA' => '#008800',
            'D66' => '#00B13C',
            'SP' => '#EE0000',
            'PvdD' => '#007E3A',
            'CDA' => '#1E8449',
            'JA21' => '#0066CC',
            'SGP' => '#FF6600',
            'FvD' => '#811E1E',
            'DENK' => '#00b7b2',
            'Volt' => '#502379',
            'CU' => '#00AEEF'
        ];

        return isset($partyColors[$partyKey]) ? $partyColors[$partyKey] : '#A0A0A0';
    }
}

if (!function_exists('adjustColorOpacity')) {
    function adjustColorOpacity($hex, $opacity) {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return "rgba($r, $g, $b, $opacity)";
    }
}

if (!function_exists('adjustColorBrightness')) {
    function adjustColorBrightness($hex, $steps) {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");

        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}
