<?php

/**
 * Gelaagde beveiliging voor het publieke contactformulier.
 *
 * Deze module verstuurt zelf geen mail. Daardoor zijn validatie, throttling en
 * Turnstile-controle los van de controller regressie-testbaar.
 */

if (!function_exists('pp_contact_env_int')) {
    function pp_contact_env_int(string $key, int $default, int $minimum, int $maximum): int {
        $raw = getenv($key);
        $value = is_string($raw) && preg_match('/^\d+$/', $raw) ? (int) $raw : $default;
        return max($minimum, min($maximum, $value));
    }
}

if (!function_exists('pp_contact_base64url_encode')) {
    function pp_contact_base64url_encode(string $value): string {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}

if (!function_exists('pp_contact_base64url_decode')) {
    function pp_contact_base64url_decode(string $value): ?string {
        if ($value === '' || preg_match('/^[A-Za-z0-9_-]+$/', $value) !== 1) {
            return null;
        }

        $padding = (4 - (strlen($value) % 4)) % 4;
        $decoded = base64_decode(strtr($value . str_repeat('=', $padding), '-_', '+/'), true);
        return is_string($decoded) ? $decoded : null;
    }
}

if (!function_exists('pp_contact_ensure_session')) {
    function pp_contact_ensure_session(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

if (!function_exists('pp_contact_issue_challenge')) {
    function pp_contact_issue_challenge(?int $issuedAt = null): string {
        pp_contact_ensure_session();

        if (empty($_SESSION['contact_form_secret']) || !is_string($_SESSION['contact_form_secret'])) {
            $_SESSION['contact_form_secret'] = bin2hex(random_bytes(32));
        }

        $issuedAt = $issuedAt ?? time();
        $nonce = bin2hex(random_bytes(16));
        $payload = $issuedAt . '.' . $nonce;
        $signature = hash_hmac('sha256', $payload, $_SESSION['contact_form_secret']);

        $challenges = $_SESSION['contact_form_challenges'] ?? [];
        if (!is_array($challenges)) {
            $challenges = [];
        }
        $challenges[$nonce] = $issuedAt;
        if (count($challenges) > 5) {
            $challenges = array_slice($challenges, -5, null, true);
        }
        $_SESSION['contact_form_challenges'] = $challenges;

        return pp_contact_base64url_encode($payload . '.' . $signature);
    }
}

if (!function_exists('pp_contact_consume_challenge')) {
    function pp_contact_consume_challenge(string $token, ?int $now = null): array {
        pp_contact_ensure_session();
        $now = $now ?? time();
        $decoded = pp_contact_base64url_decode($token);

        if ($decoded === null || preg_match('/^(\d{10})\.([a-f0-9]{32})\.([a-f0-9]{64})$/', $decoded, $matches) !== 1) {
            return ['ok' => false, 'reason' => 'challenge_invalid'];
        }

        $issuedAt = (int) $matches[1];
        $nonce = $matches[2];
        $signature = $matches[3];
        $challenges = $_SESSION['contact_form_challenges'] ?? [];
        $storedIssuedAt = is_array($challenges) ? ($challenges[$nonce] ?? null) : null;

        // Verbruik de nonce ook bij een mislukte poging om herhaald raden te stoppen.
        if (is_array($challenges)) {
            unset($challenges[$nonce]);
            $_SESSION['contact_form_challenges'] = $challenges;
        }

        if (!is_int($storedIssuedAt) || $storedIssuedAt !== $issuedAt) {
            return ['ok' => false, 'reason' => 'challenge_unknown'];
        }

        $secret = $_SESSION['contact_form_secret'] ?? '';
        $expected = is_string($secret) ? hash_hmac('sha256', $issuedAt . '.' . $nonce, $secret) : '';
        if ($expected === '' || !hash_equals($expected, $signature)) {
            return ['ok' => false, 'reason' => 'challenge_invalid'];
        }

        $age = $now - $issuedAt;
        $minimumAge = pp_contact_env_int('POLITIEKPRAAT_CONTACT_MIN_SECONDS', 4, 1, 30);
        $maximumAge = pp_contact_env_int('POLITIEKPRAAT_CONTACT_MAX_SECONDS', 7200, 300, 86400);
        if ($age < $minimumAge) {
            return ['ok' => false, 'reason' => 'challenge_too_fast'];
        }
        if ($age > $maximumAge) {
            return ['ok' => false, 'reason' => 'challenge_expired'];
        }

        return ['ok' => true, 'reason' => 'ok'];
    }
}

if (!function_exists('pp_contact_client_ip')) {
    function pp_contact_client_ip(): string {
        // De live site draait direct op Apache. Forwarded headers zijn daarom
        // niet vertrouwd: een aanvaller mag zijn rate-limit key niet zelf kiezen.
        $remote = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return is_string($remote) && filter_var($remote, FILTER_VALIDATE_IP) ? $remote : 'unknown';
    }
}

if (!function_exists('pp_contact_hash_identifier')) {
    function pp_contact_hash_identifier(string $value): string {
        $configuredKey = getenv('POLITIEKPRAAT_CONTACT_HASH_KEY');
        $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
        $stableKey = is_string($configuredKey) && strlen(trim($configuredKey)) >= 32
            ? trim($configuredKey)
            : hash('sha256', $basePath . '|politiekpraat-contact-rate-v1');
        return hash_hmac('sha256', mb_strtolower(trim($value)), $stableKey);
    }
}

if (!function_exists('pp_contact_rate_limit_path')) {
    function pp_contact_rate_limit_path(): string {
        $override = getenv('POLITIEKPRAAT_CONTACT_RATE_LIMIT_PATH');
        if (is_string($override) && trim($override) !== '') {
            return $override;
        }

        $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
        return $basePath . '/cache/rate_limit/contact_security.json';
    }
}

if (!function_exists('pp_contact_update_rate_state')) {
    function pp_contact_update_rate_state(callable $callback): array {
        $path = pp_contact_rate_limit_path();
        $directory = dirname($path);
        if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
            return ['ok' => false, 'reason' => 'rate_storage_unavailable'];
        }

        $fp = @fopen($path, 'c+');
        if ($fp === false) {
            return ['ok' => false, 'reason' => 'rate_storage_unavailable'];
        }
        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            return ['ok' => false, 'reason' => 'rate_storage_unavailable'];
        }

        $raw = stream_get_contents($fp);
        $state = json_decode(is_string($raw) ? $raw : '', true);
        if (!is_array($state)) {
            $state = [];
        }

        $oldestUseful = time() - 86400;
        foreach ($state as $key => $value) {
            if (is_array($value)) {
                $state[$key] = pp_contact_prune_timestamps($value, $oldestUseful);
                if ($state[$key] === []) {
                    unset($state[$key]);
                }
            } elseif (str_starts_with((string) $key, 'fingerprint:') && (!is_int($value) || $value < $oldestUseful)) {
                unset($state[$key]);
            }
        }

        $result = $callback($state);
        $nextState = isset($result['state']) && is_array($result['state']) ? $result['state'] : $state;
        unset($result['state']);

        ftruncate($fp, 0);
        rewind($fp);
        $encoded = json_encode($nextState, JSON_UNESCAPED_SLASHES);
        $written = is_string($encoded) ? fwrite($fp, $encoded) : false;
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($written === false) {
            return ['ok' => false, 'reason' => 'rate_storage_unavailable'];
        }

        return $result;
    }
}

if (!function_exists('pp_contact_prune_timestamps')) {
    function pp_contact_prune_timestamps(array $timestamps, int $minimum): array {
        return array_values(array_filter($timestamps, static function ($timestamp) use ($minimum): bool {
            return is_int($timestamp) && $timestamp >= $minimum;
        }));
    }
}

if (!function_exists('pp_contact_register_attempt')) {
    function pp_contact_register_attempt(?int $now = null): array {
        $now = $now ?? time();
        $window = pp_contact_env_int('POLITIEKPRAAT_CONTACT_ATTEMPT_WINDOW', 600, 60, 3600);
        $maximum = pp_contact_env_int('POLITIEKPRAAT_CONTACT_ATTEMPT_MAX', 8, 2, 100);
        $key = 'attempt:' . pp_contact_hash_identifier(pp_contact_client_ip());

        return pp_contact_update_rate_state(static function (array $state) use ($now, $window, $maximum, $key): array {
            $attempts = pp_contact_prune_timestamps(is_array($state[$key] ?? null) ? $state[$key] : [], $now - $window);
            if (count($attempts) >= $maximum) {
                return ['ok' => false, 'reason' => 'attempt_rate_limited', 'state' => $state];
            }
            $attempts[] = $now;
            $state[$key] = $attempts;
            return ['ok' => true, 'reason' => 'ok', 'state' => $state];
        });
    }
}

if (!function_exists('pp_contact_delivery_fingerprint')) {
    function pp_contact_delivery_fingerprint(array $data): string {
        $canonical = implode('|', [
            mb_strtolower(trim((string) ($data['email'] ?? ''))),
            preg_replace('/\s+/u', ' ', mb_strtolower(trim((string) ($data['subject'] ?? '')))),
            preg_replace('/\s+/u', ' ', mb_strtolower(trim((string) ($data['message'] ?? '')))),
        ]);
        return hash('sha256', $canonical);
    }
}

if (!function_exists('pp_contact_delivery_allowed')) {
    function pp_contact_delivery_allowed(string $email, string $fingerprint, ?int $now = null): array {
        $now = $now ?? time();
        $window = pp_contact_env_int('POLITIEKPRAAT_CONTACT_DELIVERY_WINDOW', 3600, 300, 86400);
        $ipMaximum = pp_contact_env_int('POLITIEKPRAAT_CONTACT_IP_MAX', 3, 1, 20);
        $emailMaximum = pp_contact_env_int('POLITIEKPRAAT_CONTACT_EMAIL_MAX', 2, 1, 10);
        $ipKey = 'delivery-ip:' . pp_contact_hash_identifier(pp_contact_client_ip());
        $emailKey = 'delivery-email:' . pp_contact_hash_identifier($email);
        $fingerprintKey = 'fingerprint:' . $fingerprint;

        return pp_contact_update_rate_state(static function (array $state) use ($now, $window, $ipMaximum, $emailMaximum, $ipKey, $emailKey, $fingerprintKey): array {
            $ipDeliveries = pp_contact_prune_timestamps(is_array($state[$ipKey] ?? null) ? $state[$ipKey] : [], $now - $window);
            $emailDeliveries = pp_contact_prune_timestamps(is_array($state[$emailKey] ?? null) ? $state[$emailKey] : [], $now - $window);
            $duplicateAt = isset($state[$fingerprintKey]) && is_int($state[$fingerprintKey]) ? $state[$fingerprintKey] : 0;

            if ($duplicateAt >= $now - 86400) {
                return ['ok' => false, 'reason' => 'duplicate_submission', 'state' => $state];
            }
            if (count($ipDeliveries) >= $ipMaximum || count($emailDeliveries) >= $emailMaximum) {
                return ['ok' => false, 'reason' => 'delivery_rate_limited', 'state' => $state];
            }

            return ['ok' => true, 'reason' => 'ok', 'state' => $state];
        });
    }
}

if (!function_exists('pp_contact_record_delivery')) {
    function pp_contact_record_delivery(string $email, string $fingerprint, ?int $now = null): bool {
        $now = $now ?? time();
        $ipKey = 'delivery-ip:' . pp_contact_hash_identifier(pp_contact_client_ip());
        $emailKey = 'delivery-email:' . pp_contact_hash_identifier($email);
        $fingerprintKey = 'fingerprint:' . $fingerprint;

        $result = pp_contact_update_rate_state(static function (array $state) use ($now, $ipKey, $emailKey, $fingerprintKey): array {
            $state[$ipKey][] = $now;
            $state[$emailKey][] = $now;
            $state[$fingerprintKey] = $now;
            return ['ok' => true, 'reason' => 'ok', 'state' => $state];
        });
        return ($result['ok'] ?? false) === true;
    }
}

if (!function_exists('pp_contact_reserve_delivery')) {
    function pp_contact_reserve_delivery(string $email, string $fingerprint, ?int $now = null): array {
        $now = $now ?? time();
        $window = pp_contact_env_int('POLITIEKPRAAT_CONTACT_DELIVERY_WINDOW', 3600, 300, 86400);
        $ipMaximum = pp_contact_env_int('POLITIEKPRAAT_CONTACT_IP_MAX', 3, 1, 20);
        $emailMaximum = pp_contact_env_int('POLITIEKPRAAT_CONTACT_EMAIL_MAX', 2, 1, 10);
        $ipKey = 'delivery-ip:' . pp_contact_hash_identifier(pp_contact_client_ip());
        $emailKey = 'delivery-email:' . pp_contact_hash_identifier($email);
        $fingerprintKey = 'fingerprint:' . $fingerprint;

        return pp_contact_update_rate_state(static function (array $state) use ($now, $window, $ipMaximum, $emailMaximum, $ipKey, $emailKey, $fingerprintKey): array {
            $ipDeliveries = pp_contact_prune_timestamps(is_array($state[$ipKey] ?? null) ? $state[$ipKey] : [], $now - $window);
            $emailDeliveries = pp_contact_prune_timestamps(is_array($state[$emailKey] ?? null) ? $state[$emailKey] : [], $now - $window);
            $duplicateAt = isset($state[$fingerprintKey]) && is_int($state[$fingerprintKey]) ? $state[$fingerprintKey] : 0;

            if ($duplicateAt >= $now - 86400) {
                return ['ok' => false, 'reason' => 'duplicate_submission', 'state' => $state];
            }
            if (count($ipDeliveries) >= $ipMaximum || count($emailDeliveries) >= $emailMaximum) {
                return ['ok' => false, 'reason' => 'delivery_rate_limited', 'state' => $state];
            }

            $ipDeliveries[] = $now;
            $emailDeliveries[] = $now;
            $state[$ipKey] = $ipDeliveries;
            $state[$emailKey] = $emailDeliveries;
            $state[$fingerprintKey] = $now;
            return ['ok' => true, 'reason' => 'ok', 'state' => $state];
        });
    }
}

if (!function_exists('pp_contact_turnstile_config')) {
    function pp_contact_turnstile_config(): array {
        $siteKey = trim((string) (getenv('POLITIEKPRAAT_TURNSTILE_SITE_KEY') ?: ''));
        $secretKey = trim((string) (getenv('POLITIEKPRAAT_TURNSTILE_SECRET_KEY') ?: ''));
        $hostnamesRaw = (string) (getenv('POLITIEKPRAAT_TURNSTILE_HOSTNAMES') ?: 'politiekpraat.nl,www.politiekpraat.nl');
        $hostnames = array_values(array_filter(array_map('trim', explode(',', $hostnamesRaw))));

        return [
            'enabled' => $siteKey !== '' && $secretKey !== '',
            'misconfigured' => ($siteKey === '') xor ($secretKey === ''),
            'site_key' => $siteKey,
            'secret_key' => $secretKey,
            'hostnames' => $hostnames,
            'action' => 'contact',
        ];
    }
}

if (!function_exists('pp_contact_verify_turnstile')) {
    function pp_contact_verify_turnstile(string $token, ?string $remoteIp = null): array {
        $config = pp_contact_turnstile_config();
        if (!$config['enabled']) {
            return $config['misconfigured']
                ? ['ok' => false, 'reason' => 'turnstile_misconfigured']
                : ['ok' => true, 'reason' => 'turnstile_disabled'];
        }
        if ($token === '' || strlen($token) > 2048) {
            return ['ok' => false, 'reason' => 'turnstile_missing'];
        }

        $payload = http_build_query([
            'secret' => $config['secret_key'],
            'response' => $token,
            'remoteip' => $remoteIp ?: pp_contact_client_ip(),
        ]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 5,
                'ignore_errors' => true,
            ],
        ]);
        $response = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
        $result = is_string($response) ? json_decode($response, true) : null;
        if (!is_array($result) || ($result['success'] ?? false) !== true) {
            return ['ok' => false, 'reason' => 'turnstile_failed'];
        }
        if (($result['action'] ?? '') !== $config['action']) {
            return ['ok' => false, 'reason' => 'turnstile_action_mismatch'];
        }
        if (!in_array((string) ($result['hostname'] ?? ''), $config['hostnames'], true)) {
            return ['ok' => false, 'reason' => 'turnstile_hostname_mismatch'];
        }

        return ['ok' => true, 'reason' => 'ok'];
    }
}

if (!function_exists('pp_contact_log_abuse')) {
    function pp_contact_log_abuse(string $reason): void {
        error_log('[CONTACT SECURITY] ' . json_encode([
            'reason' => $reason,
            'ip_hash' => pp_contact_hash_identifier(pp_contact_client_ip()),
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '/contact',
        ], JSON_UNESCAPED_SLASHES));
    }
}

if (!function_exists('pp_contact_validate_submission')) {
    function pp_contact_validate_submission(array $post): array {
        $attempt = pp_contact_register_attempt();
        if (($attempt['ok'] ?? false) !== true) {
            pp_contact_log_abuse((string) ($attempt['reason'] ?? 'attempt_rate_limited'));
            return ['ok' => false, 'reason' => $attempt['reason'] ?? 'attempt_rate_limited', 'data' => []];
        }

        if (!isset($post['website']) || !is_string($post['website']) || trim($post['website']) !== '') {
            pp_contact_log_abuse('honeypot_triggered');
            return ['ok' => false, 'reason' => 'honeypot_triggered', 'data' => []];
        }

        $challenge = isset($post['contact_challenge']) && is_string($post['contact_challenge']) ? $post['contact_challenge'] : '';
        $challengeResult = pp_contact_consume_challenge($challenge);
        if (!$challengeResult['ok']) {
            pp_contact_log_abuse($challengeResult['reason']);
            return ['ok' => false, 'reason' => $challengeResult['reason'], 'data' => []];
        }

        $limits = ['name' => 100, 'email' => 254, 'subject' => 160, 'message' => 5000];
        $minimums = ['name' => 2, 'email' => 3, 'subject' => 3, 'message' => 10];
        $data = [];
        foreach ($limits as $field => $maximum) {
            if (!isset($post[$field]) || !is_string($post[$field])) {
                return ['ok' => false, 'reason' => 'invalid_' . $field, 'data' => []];
            }
            $value = trim(str_replace("\0", '', $post[$field]));
            $length = mb_strlen($value, 'UTF-8');
            if ($length < $minimums[$field] || $length > $maximum) {
                return ['ok' => false, 'reason' => 'invalid_' . $field, 'data' => []];
            }
            if ($field !== 'message' && preg_match('/[\r\n]/', $value) === 1) {
                return ['ok' => false, 'reason' => 'invalid_' . $field, 'data' => []];
            }
            $data[$field] = $value;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'reason' => 'invalid_email', 'data' => []];
        }
        preg_match_all('~(?:https?://|www\.)~iu', $data['message'], $urlMatches);
        if (count($urlMatches[0]) > 2) {
            pp_contact_log_abuse('too_many_urls');
            return ['ok' => false, 'reason' => 'too_many_urls', 'data' => []];
        }

        $turnstileToken = isset($post['cf-turnstile-response']) && is_string($post['cf-turnstile-response'])
            ? trim($post['cf-turnstile-response'])
            : '';
        $turnstileResult = pp_contact_verify_turnstile($turnstileToken);
        if (!$turnstileResult['ok']) {
            pp_contact_log_abuse($turnstileResult['reason']);
            return ['ok' => false, 'reason' => $turnstileResult['reason'], 'data' => []];
        }

        $fingerprint = pp_contact_delivery_fingerprint($data);
        // Atomair reserveren vóór SMTP voorkomt dat gelijktijdige identieke
        // requests allemaal door de read-check heen komen.
        $delivery = pp_contact_reserve_delivery($data['email'], $fingerprint);
        if (($delivery['ok'] ?? false) !== true) {
            pp_contact_log_abuse((string) ($delivery['reason'] ?? 'delivery_rate_limited'));
            return ['ok' => false, 'reason' => $delivery['reason'] ?? 'delivery_rate_limited', 'data' => []];
        }

        $data['fingerprint'] = $fingerprint;
        return ['ok' => true, 'reason' => 'ok', 'data' => $data];
    }
}
