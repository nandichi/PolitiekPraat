<?php

if (!function_exists('get_rate_limit_config')) {
    function get_rate_limit_config(): array {
        $defaultWindow = (int) (getenv('POLITIEKPRAAT_API_RATE_LIMIT_WINDOW') ?: 60);
        $defaultMax = (int) (getenv('POLITIEKPRAAT_API_RATE_LIMIT_MAX') ?: 120);
        $strictMax = (int) (getenv('POLITIEKPRAAT_API_RATE_LIMIT_STRICT_MAX') ?: 30);

        return [
            'window_seconds' => max(10, $defaultWindow),
            'default_max_requests' => max(10, $defaultMax),
            'strict_max_requests' => max(5, $strictMax),
            'strict_endpoints' => [
                'auth',
                'contact',
                'comments',
                'polls',
            ],
        ];
    }
}

if (!function_exists('get_rate_limit_client_ip')) {
    function get_rate_limit_client_ip(): string {
        $forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
        if ($forwarded !== '') {
            $parts = explode(',', $forwarded);
            $ip = trim($parts[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }

        $realIp = $_SERVER['HTTP_X_REAL_IP'] ?? '';
        if ($realIp !== '' && filter_var($realIp, FILTER_VALIDATE_IP)) {
            return $realIp;
        }

        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return filter_var($remoteAddr, FILTER_VALIDATE_IP) ? $remoteAddr : 'unknown';
    }
}

if (!function_exists('resolve_rate_limit_bucket')) {
    function resolve_rate_limit_bucket(array $config): array {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
        $path = trim((string) preg_replace('#^/api/?#', '', $path), '/');
        $endpoint = explode('/', $path)[0] ?? 'index';

        $maxRequests = in_array($endpoint, $config['strict_endpoints'], true)
            ? $config['strict_max_requests']
            : $config['default_max_requests'];

        return [
            'endpoint' => $endpoint,
            'max_requests' => $maxRequests,
        ];
    }
}

if (!function_exists('enforce_api_rate_limit')) {
    function enforce_api_rate_limit(): void {
        $config = get_rate_limit_config();
        $bucket = resolve_rate_limit_bucket($config);

        $windowSeconds = (int) $config['window_seconds'];
        $windowStart = (int) (floor(time() / $windowSeconds) * $windowSeconds);
        $resetIn = max(1, ($windowStart + $windowSeconds) - time());

        $ip = get_rate_limit_client_ip();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $fingerprint = hash('sha256', $ip . '|' . $bucket['endpoint'] . '|' . $method . '|' . $windowStart);

        $storageDir = dirname(__DIR__) . '/cache/rate_limit';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0775, true);
        }

        $storagePath = $storageDir . '/api_rate_limits.json';
        $limitState = [];

        $fp = @fopen($storagePath, 'c+');
        if ($fp) {
            if (flock($fp, LOCK_EX)) {
                $raw = stream_get_contents($fp);
                if (is_string($raw) && trim($raw) !== '') {
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) {
                        $limitState = $decoded;
                    }
                }

                foreach ($limitState as $key => $state) {
                    if (!isset($state['expires_at']) || (int) $state['expires_at'] < time()) {
                        unset($limitState[$key]);
                    }
                }

                $count = isset($limitState[$fingerprint]['count']) ? (int) $limitState[$fingerprint]['count'] : 0;
                $count++;

                $limitState[$fingerprint] = [
                    'count' => $count,
                    'expires_at' => $windowStart + $windowSeconds,
                ];

                ftruncate($fp, 0);
                rewind($fp);
                fwrite($fp, json_encode($limitState, JSON_UNESCAPED_UNICODE));
                fflush($fp);
                flock($fp, LOCK_UN);
                fclose($fp);

                $remaining = max(0, (int) $bucket['max_requests'] - $count);
                header('X-RateLimit-Limit: ' . (int) $bucket['max_requests']);
                header('X-RateLimit-Remaining: ' . $remaining);
                header('X-RateLimit-Reset: ' . ($windowStart + $windowSeconds));

                if ($count > (int) $bucket['max_requests']) {
                    header('Retry-After: ' . $resetIn);
                    http_response_code(429);
                    header('Content-Type: application/json; charset=UTF-8');
                    echo json_encode([
                        'success' => false,
                        'error' => 'Rate limit overschreden. Probeer het later opnieuw.',
                        'retry_after_seconds' => $resetIn,
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
                }

                return;
            }

            fclose($fp);
        }
    }
}
