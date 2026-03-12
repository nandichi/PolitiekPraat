<?php

class JwtService {
    private $secret_key;

    public function __construct($secret_key = null) {
        if ($secret_key === null) {
            $secret_key = 'PolitiekPraat_JWT_Secret_2024_Secure_Key_' . (defined('DB_NAME') ? DB_NAME : 'default');
        }

        $this->secret_key = $secret_key;
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
