<?php
/**
 * OAuth 2.0 / OIDC authorization server orchestratie.
 *
 * Centrale plek die access-token/ID-token issuance, scope-validatie en
 * issuer-configuratie verzorgt voor de PolitiekPraat AS.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

require_once __DIR__ . '/Scopes.php';
require_once __DIR__ . '/Base64Url.php';
require_once __DIR__ . '/JwksManager.php';
require_once __DIR__ . '/ClientRepository.php';
require_once __DIR__ . '/AuthCodeRepository.php';
require_once __DIR__ . '/TokenRepository.php';

use Database;
use InvalidArgumentException;
use RuntimeException;

class OAuthServer
{
    public const ACCESS_TOKEN_TTL  = 3600;     // 1 uur
    public const REFRESH_TOKEN_TTL = 2592000;  // 30 dagen
    public const ID_TOKEN_TTL      = 3600;

    private Database $db;
    private JwksManager $jwks;
    private ClientRepository $clients;
    private AuthCodeRepository $codes;
    private TokenRepository $tokens;

    public function __construct(Database $db)
    {
        $this->db      = $db;
        $this->jwks    = new JwksManager($db);
        $this->clients = new ClientRepository($db);
        $this->codes   = new AuthCodeRepository($db);
        $this->tokens  = new TokenRepository($db);
    }

    public function db(): Database                 { return $this->db; }
    public function jwks(): JwksManager            { return $this->jwks; }
    public function clients(): ClientRepository    { return $this->clients; }
    public function codes(): AuthCodeRepository    { return $this->codes; }
    public function tokens(): TokenRepository      { return $this->tokens; }

    public function issuer(): string
    {
        $base = defined('URLROOT') ? (string) URLROOT : '';
        if ($base === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'politiekpraat.nl';
            $base = $scheme . '://' . $host;
        }
        return rtrim($base, '/');
    }

    public function endpoint(string $path): string
    {
        return $this->issuer() . '/' . ltrim($path, '/');
    }

    public function metadata(): array
    {
        $iss = $this->issuer();
        return [
            'issuer'                                  => $iss,
            'authorization_endpoint'                  => $iss . '/oauth/authorize',
            'token_endpoint'                          => $iss . '/oauth/token',
            'userinfo_endpoint'                       => $iss . '/oauth/userinfo',
            'jwks_uri'                                => $iss . '/.well-known/jwks.json',
            'registration_endpoint'                   => $iss . '/oauth/register',
            'revocation_endpoint'                     => $iss . '/oauth/revoke',
            'introspection_endpoint'                  => $iss . '/oauth/introspect',
            'scopes_supported'                        => Scopes::supported(),
            'response_types_supported'                => ['code'],
            'response_modes_supported'                => ['query', 'fragment'],
            'grant_types_supported'                   => ['authorization_code', 'refresh_token', 'client_credentials'],
            'subject_types_supported'                 => ['public'],
            'id_token_signing_alg_values_supported'   => ['RS256'],
            'token_endpoint_auth_methods_supported'   => ['client_secret_basic', 'client_secret_post', 'none'],
            'code_challenge_methods_supported'        => ['S256'],
            'claims_supported'                        => ['sub', 'iss', 'aud', 'exp', 'iat', 'email', 'email_verified', 'preferred_username', 'name'],
            'ui_locales_supported'                    => ['nl-NL', 'en-US'],
            'service_documentation'                   => $iss . '/api/',
            'op_policy_uri'                           => $iss . '/privacy-policy',
            'op_tos_uri'                              => $iss . '/gebruiksvoorwaarden',
        ];
    }

    public function protectedResourceMetadata(string $resourcePath = '/api'): array
    {
        $iss = $this->issuer();
        $resourcePath = '/' . ltrim($resourcePath, '/');
        $docs = rtrim($resourcePath, '/') . '/';
        return [
            'resource'                            => $iss . $resourcePath,
            'authorization_servers'               => [$iss],
            'scopes_supported'                    => Scopes::supported(),
            'bearer_methods_supported'            => ['header'],
            'resource_documentation'              => $iss . $docs,
            'resource_policy_uri'                 => $iss . '/privacy-policy',
            'resource_tos_uri'                    => $iss . '/gebruiksvoorwaarden',
            'resource_signing_alg_values_supported' => ['RS256'],
        ];
    }

    public function validatePkce(string $verifier, ?string $challenge, ?string $method): bool
    {
        if ($challenge === null || $challenge === '') {
            return false;
        }
        $method = strtoupper((string) ($method ?: 'plain'));
        if ($method !== 'S256') {
            return false;
        }
        $computed = Base64Url::encode(hash('sha256', $verifier, true));
        return hash_equals($challenge, $computed);
    }

    /**
     * Genereer access token JWT + registreer in DB.
     */
    public function issueAccessToken(array $client, ?array $user, array $scopes, ?string $nonce = null): array
    {
        $keyRow = $this->jwks->ensureActiveKey();
        $now = time();
        $exp = $now + self::ACCESS_TOKEN_TTL;
        $jti = bin2hex(random_bytes(16));

        $payload = [
            'iss'       => $this->issuer(),
            'sub'       => $user !== null ? (string) $user['id'] : 'client:' . $client['client_id'],
            'aud'       => $this->issuer() . '/api',
            'client_id' => $client['client_id'],
            'scope'     => Scopes::format($scopes),
            'iat'       => $now,
            'nbf'       => $now,
            'exp'       => $exp,
            'jti'       => $jti,
        ];
        if ($user !== null) {
            $payload['user_id'] = (int) $user['id'];
        }

        $jwt = $this->jwks->signJwt(['typ' => 'at+jwt'], $payload, $keyRow);

        $this->tokens->recordAccessToken([
            'jti'        => $jti,
            'client_id'  => $client['client_id'],
            'user_id'    => $user['id'] ?? null,
            'scope'      => Scopes::format($scopes),
            'expires_at' => $exp,
        ]);

        return [
            'access_token' => $jwt,
            'token_type'   => 'Bearer',
            'expires_in'   => self::ACCESS_TOKEN_TTL,
            'scope'        => Scopes::format($scopes),
            'jti'          => $jti,
        ];
    }

    public function issueIdToken(array $client, array $user, array $scopes, ?string $nonce = null): string
    {
        $keyRow = $this->jwks->ensureActiveKey();
        $now = time();

        $payload = [
            'iss' => $this->issuer(),
            'sub' => (string) $user['id'],
            'aud' => $client['client_id'],
            'exp' => $now + self::ID_TOKEN_TTL,
            'iat' => $now,
            'auth_time' => $now,
        ];
        if ($nonce !== null && $nonce !== '') {
            $payload['nonce'] = $nonce;
        }
        if (in_array(Scopes::PROFILE, $scopes, true)) {
            $payload['preferred_username'] = $user['username'] ?? null;
            $payload['name'] = $user['username'] ?? null;
        }
        if (in_array(Scopes::EMAIL, $scopes, true)) {
            $payload['email'] = $user['email'] ?? null;
            $payload['email_verified'] = !empty($user['email']);
        }

        return $this->jwks->signJwt(['typ' => 'JWT'], $payload, $keyRow);
    }

    public function issueRefreshToken(array $client, ?array $user, array $scopes, ?string $parentJti = null): string
    {
        return $this->tokens->createRefreshToken([
            'client_id'  => $client['client_id'],
            'user_id'    => $user['id'] ?? null,
            'scope'      => Scopes::format($scopes),
            'parent_jti' => $parentJti,
            'expires_at' => time() + self::REFRESH_TOKEN_TTL,
        ]);
    }

    /**
     * Verifieer een bearer access token. Retourneert de payload wanneer geldig,
     * null wanneer niet.
     */
    public function verifyAccessToken(string $jwt): ?array
    {
        $payload = $this->jwks->verifyJwt($jwt);
        if ($payload === null) {
            return null;
        }

        $now = time();
        if (isset($payload['exp']) && (int) $payload['exp'] < $now) {
            return null;
        }
        if (isset($payload['nbf']) && (int) $payload['nbf'] > $now) {
            return null;
        }
        if (!isset($payload['iss']) || $payload['iss'] !== $this->issuer()) {
            return null;
        }
        if (isset($payload['jti']) && !$this->tokens->isAccessTokenActive((string) $payload['jti'])) {
            return null;
        }

        return $payload;
    }

    public function getUserById(int $userId): ?array
    {
        $this->db->query('SELECT id, username, email, is_admin, profile_photo, created_at FROM users WHERE id = :id LIMIT 1');
        $this->db->bind(':id', $userId);
        $row = $this->db->single();
        return $row ? (array) $row : null;
    }
}

if (!function_exists('oauth_server')) {
    function oauth_server(): OAuthServer
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new OAuthServer(new \Database());
        }
        return $instance;
    }
}
