<?php
/**
 * MCP tools voor media-beheer (afbeeldingen voor blogs).
 *
 * - `upload_media_from_url`: downloadt een image van een externe URL
 *   (na mime/size-check) en slaat hem op in `/uploads/blogs/images/`.
 * - `generate_blog_image`: genereert een image via OpenAI (`gpt-image-1`
 *   met fallback naar `dall-e-3`) en slaat hem op.
 * - `list_media`: lijst van geüploade media in /uploads/blogs/images.
 * - `delete_media`: verwijdert een eigen geüploade media-file.
 *
 * Alle write-tools vereisen OAuth scopes `mcp.write` + `media.write`.
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP\Tools;

require_once __DIR__ . '/../ToolBuilder.php';
require_once __DIR__ . '/../McpException.php';

use PolitiekPraat\MCP\McpException;
use PolitiekPraat\MCP\ToolBuilder;
use PolitiekPraat\OAuth\Scopes;
use Throwable;

final class MediaTools
{
    private const UPLOAD_DIR_REL = 'uploads/blogs/images/';
    private const MAX_BYTES      = 10485760; // 10 MB
    private const ALLOWED_MIMES  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const ALLOWED_EXT    = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    /** @return array<int, array<string, mixed>> */
    public static function catalog(): array
    {
        return [
            ToolBuilder::write(
                'upload_media_from_url',
                'Download een image van een externe https-URL en sla hem op als blog-afbeelding. Retourneert `image_path` die je kunt gebruiken in `create_blog_draft` of `set_blog_featured_image`.',
                [
                    'type' => 'object',
                    'properties' => [
                        'url'     => ['type' => 'string', 'minLength' => 8],
                        'alt_text'=> ['type' => 'string', 'maxLength' => 500],
                    ],
                    'required' => ['url'],
                    'additionalProperties' => false,
                ],
                [Scopes::MEDIA_WRITE],
                [self::class, 'upload_media_from_url']
            ),

            ToolBuilder::write(
                'generate_blog_image',
                'Genereer een image via OpenAI (gpt-image-1 / dall-e-3) op basis van een prompt en sla hem op. Retourneert `image_path`. Vereist dat `OPENAI_API_KEY` op de server is ingesteld.',
                [
                    'type' => 'object',
                    'properties' => [
                        'prompt'  => ['type' => 'string', 'minLength' => 10, 'maxLength' => 4000],
                        'size'    => ['type' => 'string', 'enum' => ['1024x1024', '1792x1024', '1024x1792', '512x512'], 'default' => '1024x1024'],
                        'quality' => ['type' => 'string', 'enum' => ['standard', 'hd'], 'default' => 'standard'],
                        'model'   => ['type' => 'string', 'enum' => ['auto', 'gpt-image-1', 'dall-e-3'], 'default' => 'auto'],
                        'style'   => ['type' => 'string', 'enum' => ['vivid', 'natural'], 'default' => 'natural'],
                    ],
                    'required' => ['prompt'],
                    'additionalProperties' => false,
                ],
                [Scopes::MEDIA_WRITE],
                [self::class, 'generate_blog_image']
            ),

            ToolBuilder::write(
                'list_media',
                'Lijst van eerder geüploade media in /uploads/blogs/images/ (max 200 resultaten, nieuwste eerst).',
                [
                    'type' => 'object',
                    'properties' => [
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 200, 'default' => 50],
                    ],
                    'additionalProperties' => false,
                ],
                [Scopes::MEDIA_WRITE],
                [self::class, 'list_media']
            ),

            ToolBuilder::write(
                'delete_media',
                'Verwijder een media-file. Alleen paden onder `uploads/blogs/images/` zijn toegestaan.',
                [
                    'type' => 'object',
                    'properties' => [
                        'image_path' => ['type' => 'string', 'minLength' => 10],
                    ],
                    'required' => ['image_path'],
                    'additionalProperties' => false,
                ],
                [Scopes::MEDIA_WRITE],
                [self::class, 'delete_media']
            ),
        ];
    }

    // ==========================================================
    //                         HANDLERS
    // ==========================================================

    public static function upload_media_from_url(array $args, ?array $ctx): array
    {
        self::requireUser($ctx);
        $url = trim((string) $args['url']);

        if (!preg_match('/^https:\/\//i', $url)) {
            throw new McpException(-32602, 'Alleen https-URLs worden geaccepteerd.');
        }

        $head = self::fetchHeaders($url);
        $contentType = strtolower(explode(';', (string) ($head['content-type'] ?? ''))[0] ?? '');
        if ($contentType && !in_array($contentType, self::ALLOWED_MIMES, true)) {
            throw new McpException(-32602, 'Ongeldig content-type: ' . $contentType);
        }
        $contentLength = (int) ($head['content-length'] ?? 0);
        if ($contentLength > self::MAX_BYTES) {
            throw new McpException(-32602, 'Bestand te groot (' . $contentLength . ' bytes, max ' . self::MAX_BYTES . ')');
        }

        $bytes = self::downloadBytes($url, self::MAX_BYTES);
        if ($bytes === null || $bytes === '') {
            throw new McpException(-32000, 'Download mislukt.');
        }

        // MIME via finfo na download (veiliger dan Content-Type header).
        $mime = self::detectMime($bytes);
        if (!in_array($mime, self::ALLOWED_MIMES, true)) {
            throw new McpException(-32602, 'Ongeldig mime-type na download: ' . $mime);
        }

        return self::persistImage($bytes, $mime, $args['alt_text'] ?? null);
    }

    public static function generate_blog_image(array $args, ?array $ctx): array
    {
        self::requireUser($ctx);

        $apiKey = self::openAiKey();
        if ($apiKey === null) {
            throw new McpException(-32003, 'OPENAI_API_KEY niet beschikbaar op de server.');
        }

        $prompt  = (string) $args['prompt'];
        $size    = (string) ($args['size'] ?? '1024x1024');
        $quality = (string) ($args['quality'] ?? 'standard');
        $wanted  = (string) ($args['model'] ?? 'auto');
        $style   = (string) ($args['style'] ?? 'natural');

        $models = [];
        if ($wanted === 'auto') {
            $models = ['gpt-image-1', 'dall-e-3'];
        } else {
            $models = [$wanted];
        }

        $lastError = null;
        foreach ($models as $model) {
            try {
                $bytes = self::callOpenAiImages($apiKey, $model, $prompt, $size, $quality, $style);
                if ($bytes !== null) {
                    $result = self::persistImage($bytes, 'image/png', $prompt);
                    $result['model'] = $model;
                    return $result;
                }
            } catch (Throwable $e) {
                $lastError = $e->getMessage();
                continue;
            }
        }

        throw new McpException(-32000, 'Image-generatie mislukt: ' . ($lastError ?? 'unknown error'));
    }

    public static function list_media(array $args, ?array $ctx): array
    {
        self::requireUser($ctx);
        $limit = max(1, min(200, (int) ($args['limit'] ?? 50)));
        $dir = BASE_PATH . '/' . self::UPLOAD_DIR_REL;
        if (!is_dir($dir)) return ['media' => []];

        $files = glob($dir . '*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE) ?: [];
        usort($files, static fn($a, $b) => filemtime($b) <=> filemtime($a));
        $files = array_slice($files, 0, $limit);

        $media = [];
        foreach ($files as $f) {
            $rel = self::UPLOAD_DIR_REL . basename($f);
            $media[] = [
                'image_path' => $rel,
                'url'        => self::mediaUrl($rel),
                'size'       => filesize($f) ?: 0,
                'modified'   => date('c', filemtime($f) ?: time()),
            ];
        }
        return ['media' => $media];
    }

    public static function delete_media(array $args, ?array $ctx): array
    {
        self::requireUser($ctx);
        $path = ltrim((string) $args['image_path'], '/');
        if (!str_starts_with($path, self::UPLOAD_DIR_REL) || strpos($path, '..') !== false) {
            throw new McpException(-32602, 'Alleen paden onder ' . self::UPLOAD_DIR_REL . ' mogen verwijderd worden.');
        }
        $full = BASE_PATH . '/' . $path;
        if (!is_file($full)) throw new McpException(-32001, 'file_not_found');
        if (!@unlink($full)) throw new McpException(-32000, 'unlink_failed');
        return ['ok' => true, 'deleted' => $path];
    }

    // ==========================================================
    //                         HELPERS
    // ==========================================================

    private static function requireUser(?array $ctx): int
    {
        $uid = $ctx['user_id'] ?? null;
        if (!$uid) throw new McpException(-32002, 'authentication_required');
        return (int) $uid;
    }

    /**
     * @return array<string,string> lowercased headers
     */
    private static function fetchHeaders(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY         => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HEADER         => true,
            CURLOPT_USERAGENT      => 'PolitiekPraat-MCP/1.0',
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $raw = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (!$raw || $code >= 400) {
            throw new McpException(-32000, 'HEAD-request mislukt (status ' . $code . ')');
        }
        $headers = [];
        foreach (preg_split("/\r\n|\n|\r/", (string) $raw) ?: [] as $line) {
            if (strpos($line, ':') !== false) {
                [$k, $v] = explode(':', $line, 2);
                $headers[strtolower(trim($k))] = trim($v);
            }
        }
        return $headers;
    }

    private static function downloadBytes(string $url, int $maxBytes): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => 'PolitiekPraat-MCP/1.0',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_BUFFERSIZE     => 8192,
        ]);

        $buffer = '';
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$buffer, $maxBytes) {
            $buffer .= $chunk;
            if (strlen($buffer) > $maxBytes) return 0; // abort
            return strlen($chunk);
        });

        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code >= 400 || strlen($buffer) > $maxBytes) return null;
        return $buffer;
    }

    private static function detectMime(string $bytes): string
    {
        if (function_exists('finfo_open')) {
            $f = finfo_open(FILEINFO_MIME_TYPE);
            if ($f) {
                $mime = finfo_buffer($f, $bytes) ?: '';
                finfo_close($f);
                return strtolower($mime);
            }
        }
        // Fallback op magic bytes
        $prefix = substr($bytes, 0, 12);
        if (substr($prefix, 0, 3) === "\xFF\xD8\xFF") return 'image/jpeg';
        if (substr($prefix, 0, 8) === "\x89PNG\r\n\x1a\n") return 'image/png';
        if (substr($prefix, 0, 6) === 'GIF87a' || substr($prefix, 0, 6) === 'GIF89a') return 'image/gif';
        if (substr($prefix, 0, 4) === 'RIFF' && substr($prefix, 8, 4) === 'WEBP') return 'image/webp';
        return 'application/octet-stream';
    }

    /** @return array{ok:bool,image_path:string,url:string,mime:string,size:int,alt_text:?string} */
    private static function persistImage(string $bytes, string $mime, ?string $altText): array
    {
        $dir = BASE_PATH . '/' . self::UPLOAD_DIR_REL;
        if (!is_dir($dir)) @mkdir($dir, 0755, true);
        if (!is_writable($dir)) {
            throw new McpException(-32000, 'Upload directory niet schrijfbaar: ' . self::UPLOAD_DIR_REL);
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'bin',
        };

        $filename = 'mcp-' . date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
        $full = $dir . $filename;
        if (file_put_contents($full, $bytes) === false) {
            throw new McpException(-32000, 'Kon bestand niet opslaan.');
        }
        @chmod($full, 0644);

        $relative = self::UPLOAD_DIR_REL . $filename;
        return [
            'ok'         => true,
            'image_path' => $relative,
            'url'        => self::mediaUrl($relative),
            'mime'       => $mime,
            'size'       => strlen($bytes),
            'alt_text'   => $altText,
        ];
    }

    private static function mediaUrl(string $relative): string
    {
        $base = defined('URLROOT') ? rtrim(URLROOT, '/') : 'https://politiekpraat.nl';
        return $base . '/' . ltrim($relative, '/');
    }

    private static function openAiKey(): ?string
    {
        $key = getenv('OPENAI_API_KEY');
        if ($key) return $key;
        if (isset($_ENV['OPENAI_API_KEY'])) return $_ENV['OPENAI_API_KEY'];
        $configFile = BASE_PATH . '/config/api_keys.php';
        if (is_file($configFile)) {
            $cfg = include $configFile;
            if (is_array($cfg) && !empty($cfg['openai_api_key'])) return (string) $cfg['openai_api_key'];
        }
        return null;
    }

    /**
     * Roept de OpenAI images API aan en retourneert de raw image-bytes.
     * Retourneert null bij transient-fout; gooit McpException bij fatal.
     */
    private static function callOpenAiImages(string $apiKey, string $model, string $prompt, string $size, string $quality, string $style): ?string
    {
        $payload = [
            'model'  => $model,
            'prompt' => $prompt,
            'size'   => $size,
            'n'      => 1,
        ];
        if ($model === 'dall-e-3') {
            $payload['quality'] = $quality;
            $payload['style']   = $style;
            $payload['response_format'] = 'b64_json';
        }
        // gpt-image-1: retourneert b64_json standaard, geen 'style' / 'response_format' param.

        $ch = curl_init('https://api.openai.com/v1/images/generations');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new McpException(-32000, 'OpenAI curl-fout: ' . $err);
        }
        if ($status >= 400) {
            $body = json_decode($response, true);
            $msg  = $body['error']['message'] ?? $response;
            // 404/400 voor onbekend model -> fallback door null te retourneren.
            if ($status === 404 || (str_contains(strtolower((string) $msg), 'model') && str_contains(strtolower((string) $msg), 'not'))) {
                return null;
            }
            throw new McpException(-32000, 'OpenAI API fout (' . $status . '): ' . (string) $msg);
        }

        $data = json_decode($response, true);
        if (!is_array($data) || empty($data['data'][0])) {
            throw new McpException(-32000, 'OpenAI response onleesbaar.');
        }
        $entry = $data['data'][0];
        if (!empty($entry['b64_json'])) {
            return base64_decode($entry['b64_json'], true) ?: null;
        }
        if (!empty($entry['url'])) {
            return self::downloadBytes((string) $entry['url'], self::MAX_BYTES);
        }
        return null;
    }
}
