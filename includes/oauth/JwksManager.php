<?php
/**
 * JWKS key manager voor de OAuth/OIDC authorization server.
 *
 * Genereert RSA-signing keys, bewaart ze in de oauth_jwks tabel en
 * publiceert ze als public JWK set. Ondersteunt key-rotatie via
 * `activate()` en `retire()`.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

require_once __DIR__ . '/Base64Url.php';

use Database;
use RuntimeException;

class JwksManager
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Zorgt dat er minstens één actieve signing key bestaat.
     * Idempotent: veilig om bij elke request aan te roepen.
     */
    public function ensureActiveKey(): array
    {
        $active = $this->getActiveKey();
        if ($active !== null) {
            return $active;
        }

        return $this->generateKey();
    }

    public function getActiveKey(): ?array
    {
        $this->db->query("SELECT * FROM oauth_jwks WHERE status = 'active' ORDER BY activated_at DESC LIMIT 1");
        $row = $this->db->single();
        return $row ? (array) $row : null;
    }

    /** @return array<int, array<string, mixed>> */
    public function getPublishedKeys(): array
    {
        $this->db->query("SELECT * FROM oauth_jwks WHERE status IN ('active','rotating') ORDER BY activated_at DESC");
        $rows = $this->db->resultSet();
        if (!is_array($rows)) {
            return [];
        }
        return array_map(static fn($r) => (array) $r, $rows);
    }

    public function generateKey(): array
    {
        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'digest_alg'       => 'sha256',
        ];

        $res = openssl_pkey_new($config);
        if ($res === false) {
            throw new RuntimeException('Kon geen RSA key genereren: ' . openssl_error_string());
        }

        $privatePem = '';
        if (!openssl_pkey_export($res, $privatePem)) {
            throw new RuntimeException('Export RSA private key mislukt');
        }

        $details = openssl_pkey_get_details($res);
        if ($details === false || !isset($details['rsa']['n'], $details['rsa']['e'])) {
            throw new RuntimeException('Kan RSA details niet lezen');
        }

        $kid = bin2hex(random_bytes(8));

        $jwk = [
            'kty' => 'RSA',
            'use' => 'sig',
            'alg' => 'RS256',
            'kid' => $kid,
            'n'   => Base64Url::encode($details['rsa']['n']),
            'e'   => Base64Url::encode($details['rsa']['e']),
        ];

        $now = date('Y-m-d H:i:s');
        $this->db->query("INSERT INTO oauth_jwks (kid, algorithm, private_key_pem, public_jwk_json, status, activated_at)
                          VALUES (:kid, 'RS256', :priv, :pub, 'active', :now)");
        $this->db->bind(':kid', $kid);
        $this->db->bind(':priv', $this->encryptPrivateKey($privatePem));
        $this->db->bind(':pub', json_encode($jwk, JSON_UNESCAPED_SLASHES));
        $this->db->bind(':now', $now);
        $this->db->execute();

        return $this->getActiveKey() ?? [];
    }

    public function getPrivateKeyPem(array $keyRow): string
    {
        return $this->decryptPrivateKey($keyRow['private_key_pem']);
    }

    public function getPublicJwk(array $keyRow): array
    {
        $decoded = json_decode((string) $keyRow['public_jwk_json'], true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getJwkSet(): array
    {
        $keys = [];
        foreach ($this->getPublishedKeys() as $row) {
            $jwk = $this->getPublicJwk($row);
            if (!empty($jwk)) {
                $keys[] = $jwk;
            }
        }
        return ['keys' => $keys];
    }

    private function encryptPrivateKey(string $pem): string
    {
        $masterKey = $this->masterKey();
        if ($masterKey === null) {
            return 'plain:' . base64_encode($pem);
        }

        $iv = random_bytes(12);
        $tag = '';
        $ciphertext = openssl_encrypt($pem, 'aes-256-gcm', $masterKey, OPENSSL_RAW_DATA, $iv, $tag, '', 16);
        if ($ciphertext === false) {
            throw new RuntimeException('Kon private key niet versleutelen');
        }

        return 'aesgcm:' . base64_encode($iv . $tag . $ciphertext);
    }

    private function decryptPrivateKey(string $stored): string
    {
        if (strpos($stored, 'plain:') === 0) {
            return base64_decode(substr($stored, 6), true) ?: '';
        }

        if (strpos($stored, 'aesgcm:') === 0) {
            $masterKey = $this->masterKey();
            if ($masterKey === null) {
                throw new RuntimeException('POLITIEKPRAAT_JWKS_MASTER_KEY ontbreekt; kan private key niet ontsleutelen');
            }
            $raw = base64_decode(substr($stored, 7), true);
            if ($raw === false || strlen($raw) < 28) {
                throw new RuntimeException('Corrupte private key ciphertext');
            }
            $iv  = substr($raw, 0, 12);
            $tag = substr($raw, 12, 16);
            $ct  = substr($raw, 28);
            $pem = openssl_decrypt($ct, 'aes-256-gcm', $masterKey, OPENSSL_RAW_DATA, $iv, $tag);
            if ($pem === false) {
                throw new RuntimeException('Decryptie private key mislukt');
            }
            return $pem;
        }

        return $stored;
    }

    private function masterKey(): ?string
    {
        $key = getenv('POLITIEKPRAAT_JWKS_MASTER_KEY');
        if (!is_string($key) || $key === '') {
            return null;
        }

        $decoded = base64_decode($key, true);
        if ($decoded !== false && strlen($decoded) === 32) {
            return $decoded;
        }

        return hash('sha256', $key, true);
    }

    public function signJwt(array $header, array $payload, array $keyRow): string
    {
        $header = array_merge($header, [
            'alg' => 'RS256',
            'typ' => $header['typ'] ?? 'JWT',
            'kid' => $keyRow['kid'],
        ]);

        $signingInput = Base64Url::encode(json_encode($header, JSON_UNESCAPED_SLASHES))
            . '.'
            . Base64Url::encode(json_encode($payload, JSON_UNESCAPED_SLASHES));

        $signature = '';
        $privatePem = $this->getPrivateKeyPem($keyRow);
        $ok = openssl_sign($signingInput, $signature, $privatePem, OPENSSL_ALGO_SHA256);
        if (!$ok) {
            throw new RuntimeException('JWT signing mislukt: ' . openssl_error_string());
        }

        return $signingInput . '.' . Base64Url::encode($signature);
    }

    public function verifyJwt(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }
        [$h64, $p64, $s64] = $parts;

        $header = json_decode(Base64Url::decode($h64), true);
        $payload = json_decode(Base64Url::decode($p64), true);
        $signature = Base64Url::decode($s64);

        if (!is_array($header) || !is_array($payload) || $signature === '') {
            return null;
        }
        if (($header['alg'] ?? '') !== 'RS256') {
            return null;
        }

        $kid = $header['kid'] ?? null;
        $keyRow = null;
        foreach ($this->getPublishedKeys() as $row) {
            if ($row['kid'] === $kid) {
                $keyRow = $row;
                break;
            }
        }
        if ($keyRow === null) {
            return null;
        }

        $privatePem = $this->getPrivateKeyPem($keyRow);
        $privateKey = openssl_pkey_get_private($privatePem);
        if ($privateKey === false) {
            return null;
        }
        $details = openssl_pkey_get_details($privateKey);
        if ($details === false || empty($details['key'])) {
            return null;
        }
        $publicKey = openssl_pkey_get_public($details['key']);
        if ($publicKey === false) {
            return null;
        }

        $signingInput = $h64 . '.' . $p64;
        $ok = openssl_verify($signingInput, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($ok !== 1) {
            return null;
        }

        return $payload;
    }
}
