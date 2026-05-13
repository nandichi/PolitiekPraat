<?php
/**
 * Globale render-helpers voor de editorial component-library.
 *
 * Vereist één keer included worden (via includes/component_bootstrap.php).
 */

if (!function_exists('pp_e')) {
    /**
     * Veilige escape voor HTML-output.
     */
    function pp_e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('pp_attr')) {
    /**
     * Render een HTML-attribuut-string vanuit een associative array.
     * Booleans worden als HTML5-boolean attributes weergegeven.
     */
    function pp_attr(array $attrs): string
    {
        $parts = [];
        foreach ($attrs as $key => $value) {
            if ($value === null || $value === false) {
                continue;
            }
            if ($value === true) {
                $parts[] = pp_e($key);
                continue;
            }
            $parts[] = pp_e($key) . '="' . pp_e($value) . '"';
        }
        return implode(' ', $parts);
    }
}

if (!function_exists('pp_class')) {
    /**
     * Combineer classNames; ondersteunt strings of arrays met conditie-keys
     * waarbij `'foo' => true` de klasse 'foo' toevoegt.
     */
    function pp_class(...$classes): string
    {
        $out = [];
        foreach ($classes as $entry) {
            if (is_array($entry)) {
                foreach ($entry as $key => $val) {
                    if (is_int($key)) {
                        if ($val) {
                            $out[] = (string) $val;
                        }
                    } else {
                        if ($val) {
                            $out[] = $key;
                        }
                    }
                }
            } elseif (is_string($entry) && $entry !== '') {
                $out[] = $entry;
            }
        }
        return trim(implode(' ', array_filter(array_unique($out))));
    }
}

if (!function_exists('pp_url')) {
    /**
     * Bouw een absolute URL met URLROOT prefix; respecteert externe URLs.
     */
    function pp_url(string $path): string
    {
        if ($path === '') {
            return defined('URLROOT') ? URLROOT : '/';
        }
        if (preg_match('#^(https?:)?//#i', $path) === 1
            || strpos($path, 'mailto:') === 0
            || strpos($path, 'tel:') === 0
            || strpos($path, '#') === 0) {
            return $path;
        }
        $base = defined('URLROOT') ? rtrim(URLROOT, '/') : '';
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('pp_active_path')) {
    /**
     * Returnt true als de huidige request-URI gelijk is aan $path of eronder valt.
     */
    function pp_active_path(string $path): bool
    {
        $current = $_SERVER['REQUEST_URI'] ?? '/';
        $current = strtok($current, '?');
        $current = '/' . trim((string) $current, '/');
        $target = '/' . trim($path, '/');
        if ($target === '/') {
            return $current === '/';
        }
        return $current === $target || str_starts_with($current, $target . '/');
    }
}

if (!function_exists('pp_render_component')) {
    /**
     * Render een component-partial met scoped variables.
     * Voorbeeld: pp_render_component('ui/button', ['label' => 'Klik']).
     */
    function pp_render_component(string $__pp_component, array $__pp_props = []): string
    {
        $__pp_root = realpath(__DIR__);
        if ($__pp_root === false) {
            return '';
        }
        $__pp_file = $__pp_root . '/' . ltrim($__pp_component, '/') . '.php';
        if (!file_exists($__pp_file)) {
            error_log("[pp_render_component] missing component: {$__pp_file}");
            return '';
        }
        ob_start();
        // Maak alle props beschikbaar als variabelen in lokale scope.
        // OVERWRITE zodat geen props worden geblokkeerd door interne variabelen.
        $props = $__pp_props;
        extract($__pp_props, EXTR_OVERWRITE);
        include $__pp_file;
        return (string) ob_get_clean();
    }

    function pp_render_component_e(string $__pp_component, array $__pp_props = []): void
    {
        echo pp_render_component($__pp_component, $__pp_props);
    }
}

if (!function_exists('pp_category_tone')) {
    /**
     * Mapping van categorie-string naar een tag-tone (ochre/olive/rose/moss/hague/terracotta).
     * Wordt gebruikt voor consistente category-tags in article-cards.
     */
    function pp_category_tone(?string $category): string
    {
        $category = strtolower(trim((string) $category));
        $map = [
            'analyse' => 'hague',
            'analyses' => 'hague',
            'opinie' => 'rose',
            'column' => 'rose',
            'essay' => 'moss',
            'reportage' => 'ochre',
            'interview' => 'olive',
            'achtergrond' => 'hague',
            'feiten' => 'olive',
            'nieuws' => 'terracotta',
            'breaking' => 'terracotta',
            'aankondiging' => 'ochre',
            'aanbeveling' => 'moss',
            'video' => 'rose',
            'audio' => 'rose',
            'data' => 'hague',
            'tools' => 'olive',
        ];
        return $map[$category] ?? 'neutral';
    }
}

if (!function_exists('pp_reading_time')) {
    /**
     * Schat leestijd in minuten op basis van content-lengte (~220 wpm Nederlands).
     */
    function pp_reading_time(?string $content): int
    {
        if (empty($content)) {
            return 1;
        }
        $words = str_word_count(strip_tags((string) $content));
        return max(1, (int) round($words / 220));
    }
}
