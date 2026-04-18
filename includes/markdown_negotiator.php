<?php
/**
 * Markdown content negotiation voor AI-agents.
 *
 * Wanneer een client expliciet `text/markdown` prefereert via de
 * Accept-header, buffert deze middleware de normale HTML-response en
 * converteert die naar Markdown op het moment van output. De content-type
 * wordt dan omgezet naar `text/markdown; charset=UTF-8` en er wordt een
 * `x-markdown-tokens` header toegevoegd met een ruwe tokenschatting.
 *
 * Relevant:
 *  - https://developers.cloudflare.com/fundamentals/reference/markdown-for-agents/
 *
 * De middleware doet NIETS wanneer:
 *  - geen Accept-header aanwezig is of html sterker gewogen is
 *  - het request een API/AJAX/admin/auth/logout pad is
 *  - het een niet-GET/HEAD request is
 */

declare(strict_types=1);

if (!function_exists('markdown_negotiator_accepts_markdown')) {
    function markdown_negotiator_accepts_markdown(): bool {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if ($accept === '') {
            return false;
        }

        $mdQ = 0.0;
        $htmlQ = 0.0;

        foreach (explode(',', $accept) as $part) {
            $bits = array_map('trim', explode(';', $part));
            $mime = strtolower(array_shift($bits) ?? '');

            if ($mime === '') {
                continue;
            }

            $q = 1.0;
            foreach ($bits as $param) {
                if (stripos($param, 'q=') === 0) {
                    $q = (float) substr($param, 2);
                }
            }

            if ($mime === 'text/markdown' || $mime === 'text/x-markdown') {
                if ($q > $mdQ) {
                    $mdQ = $q;
                }
            } elseif ($mime === 'text/html' || $mime === 'application/xhtml+xml' || $mime === '*/*') {
                if ($q > $htmlQ) {
                    $htmlQ = $q;
                }
            }
        }

        return $mdQ > 0 && $mdQ >= $htmlQ;
    }
}

if (!function_exists('markdown_negotiator_is_skipped_path')) {
    function markdown_negotiator_is_skipped_path(): bool {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = '/' . ltrim($path, '/');

        $skipPrefixes = [
            '/api/',
            '/ajax/',
            '/admin/',
            '/.well-known/',
            '/auth/',
            '/oauth/',
            '/mcp',
            '/logout',
            '/login',
            '/register',
            '/sitemap.xml',
            '/robots.txt',
        ];

        foreach ($skipPrefixes as $prefix) {
            if (strpos($path, $prefix) === 0) {
                return true;
            }
        }

        if ($path === '/api') {
            return true;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext !== '' && !in_array($ext, ['php', 'html', 'htm'], true)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('markdown_negotiator_start')) {
    /**
     * Activeer de markdown buffering. Moet vroeg in de bootstrap worden
     * aangeroepen. No-op wanneer de client geen markdown vraagt.
     */
    function markdown_negotiator_start(): void {
        if (defined('MARKDOWN_NEGOTIATOR_ACTIVE')) {
            return;
        }

        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if (!in_array($method, ['GET', 'HEAD'], true)) {
            return;
        }

        if (markdown_negotiator_is_skipped_path()) {
            return;
        }

        if (!markdown_negotiator_accepts_markdown()) {
            return;
        }

        define('MARKDOWN_NEGOTIATOR_ACTIVE', true);

        ob_start();

        register_shutdown_function('markdown_negotiator_flush');
    }
}

if (!function_exists('markdown_negotiator_flush')) {
    function markdown_negotiator_flush(): void {
        if (!defined('MARKDOWN_NEGOTIATOR_ACTIVE')) {
            return;
        }

        $html = '';
        while (ob_get_level() > 0) {
            $chunk = ob_get_clean();
            if ($chunk === false) {
                break;
            }
            $html = $chunk . $html;
        }

        $statusCode = http_response_code();
        if (is_int($statusCode) && $statusCode >= 300 && $statusCode < 400) {
            echo $html;
            return;
        }

        if (trim($html) === '') {
            return;
        }

        $sniff = ltrim($html);
        $isHtml = (stripos($sniff, '<!doctype') === 0)
            || (stripos($sniff, '<html') === 0)
            || (stripos($sniff, '<') === 0 && stripos($sniff, '<') !== false);

        if (!$isHtml) {
            echo $html;
            return;
        }

        $markdown = markdown_negotiator_convert($html);

        if ($markdown === null) {
            echo $html;
            return;
        }

        if (!headers_sent()) {
            header_remove('Content-Type');
            header_remove('Content-Length');
            header('Content-Type: text/markdown; charset=UTF-8');
            header('Vary: Accept');

            $tokenEstimate = (int) ceil(strlen($markdown) / 4);
            header('x-markdown-tokens: ' . $tokenEstimate);

            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            $path = parse_url($uri, PHP_URL_PATH) ?? '/';
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'politiekpraat.nl';
            $canonical = $scheme . '://' . $host . $path;
            header('Link: <' . $canonical . '>; rel="canonical"; type="text/html"', false);
        }

        echo $markdown;
    }
}

if (!function_exists('markdown_negotiator_convert')) {
    function markdown_negotiator_convert(string $html): ?string {
        try {
            if (!class_exists('League\\HTMLToMarkdown\\HtmlConverter')) {
                $autoload = __DIR__ . '/../vendor/autoload.php';
                if (is_readable($autoload)) {
                    require_once $autoload;
                }
            }

            if (!class_exists('League\\HTMLToMarkdown\\HtmlConverter')) {
                error_log('[markdown_negotiator] HtmlConverter niet beschikbaar');
                return null;
            }

            $cleanHtml = markdown_negotiator_strip_noise($html);

            $converter = new \League\HTMLToMarkdown\HtmlConverter([
                'strip_tags' => true,
                'remove_nodes' => 'script style noscript iframe svg canvas',
                'hard_break' => true,
                'header_style' => 'atx',
                'use_autolinks' => true,
                'italic_style' => '_',
                'bold_style' => '**',
                'preserve_comments' => false,
                'suppress_errors' => true,
            ]);

            $markdown = $converter->convert($cleanHtml);

            $markdown = preg_replace("/\n{3,}/", "\n\n", $markdown);

            return trim((string) $markdown) . "\n";
        } catch (Throwable $e) {
            error_log('[markdown_negotiator] conversion error: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('markdown_negotiator_strip_noise')) {
    function markdown_negotiator_strip_noise(string $html): string {
        $patterns = [
            '/<script\b[^>]*>.*?<\/script>/si',
            '/<style\b[^>]*>.*?<\/style>/si',
            '/<noscript\b[^>]*>.*?<\/noscript>/si',
            '/<template\b[^>]*>.*?<\/template>/si',
            '/<svg\b[^>]*>.*?<\/svg>/si',
            '/<iframe\b[^>]*>.*?<\/iframe>/si',
            '/<!--(?!\[if).*?-->/s',
        ];

        $clean = preg_replace($patterns, '', $html);
        if (!is_string($clean)) {
            return $html;
        }

        if (preg_match('/<main\b[^>]*>.*?<\/main>/si', $clean, $m)) {
            return $m[0];
        }

        if (preg_match('/<body\b[^>]*>(.*?)<\/body>/si', $clean, $m)) {
            return $m[1];
        }

        return $clean;
    }
}
