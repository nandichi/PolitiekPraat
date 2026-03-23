<?php

class JwtService {
    private $secret_key;

    public function __construct($secret_key = null) {
        if ($secret_key === null) {
            $secret_key = self::resolveSecretKey();
        }

        $this->secret_key = $secret_key;
    }

    public static function resolveSecretKey() {
        self::loadLocalEnvFallback();

        $secret = getenv('POLITIEKPRAAT_JWT_SECRET');

        if (is_string($secret)) {
            $secret = trim($secret);
        }

        if (is_string($secret) && $secret !== '') {
            return $secret;
        }

        $app_env = defined('APP_ENV') ? APP_ENV : strtolower((string) (getenv('APP_ENV') ?: 'production'));
        $is_production = !in_array($app_env, ['local', 'development', 'dev', 'test', 'testing', 'staging'], true);

        if ($is_production) {
            throw new RuntimeException('POLITIEKPRAAT_JWT_SECRET ontbreekt. Zet een sterke secret in de environment voordat de API start.');
        }

        trigger_error('POLITIEKPRAAT_JWT_SECRET ontbreekt; development fallback actief. Zet alsnog een lokale secret voor consistente tests.', E_USER_WARNING);

        return 'dev-only-jwt-secret-change-me';
    }

    private static function loadLocalEnvFallback() {
        if (!empty(getenv('POLITIEKPRAAT_JWT_SECRET'))) {
            return;
        }

        $base_path = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
        $local_env_file = rtrim($base_path, '/\\') . '/includes/env.local.php';

        if (is_readable($local_env_file)) {
            require_once $local_env_file;
        }
    }

    public function verify($token) {
        if (!is_string($token) || trim($token) === '') {
            return false;
        }

        $token_parts = explode('.', $token);
        if (count($token_parts) !== 3) {
            return false;
        }

        list($header, $payload, $signature) = $token_parts;

        $expected_signature = hash_hmac('sha256', $header . '.' . $payload, $this->secret_key, true);
        $expected_signature = $this->base64UrlEncode($expected_signature);

        if (!hash_equals($signature, $expected_signature)) {
            return false;
        }

        $decoded_payload = $this->base64UrlDecode($payload);
        if ($decoded_payload === false) {
            return false;
        }

        $payload_data = json_decode($decoded_payload, true);
        if (!is_array($payload_data)) {
            return false;
        }

        if (!isset($payload_data['exp']) || !is_numeric($payload_data['exp'])) {
            return false;
        }

        if ((int)$payload_data['exp'] < time()) {
            return false;
        }

        return $payload_data;
    }

    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data) {
        if (!is_string($data) || $data === '') {
            return false;
        }

        $base64 = strtr($data, '-_', '+/');
        $padding = strlen($base64) % 4;
        if ($padding > 0) {
            $base64 .= str_repeat('=', 4 - $padding);
        }

        return base64_decode($base64, true);
    }
}
