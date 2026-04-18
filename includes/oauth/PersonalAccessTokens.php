<?php
/**
 * Beheer van Personal Access Tokens (PAT) voor het PolitiekPraat API/MCP.
 *
 * PATs zijn long-lived bearer tokens waarmee een ingelogde gebruiker
 * handmatig toegang kan geven aan externe tools (Cursor, Claude Desktop,
 * scripts) zonder door de OAuth 2.0 authorization-code flow te gaan.
 *
 * Token-format: "pp_live_" + 48 hex chars (192 bit entropy).
 * Opslag: alleen SHA-256 hash + korte prefix; de originele token wordt
 * eenmalig aan de user getoond na aanmaak.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

use Database;
use Throwable;

final class PersonalAccessTokens
{
    private const TOKEN_PREFIX = 'pp_live_';
    private const TOKEN_BYTES  = 24;

    public function __construct(private Database $db) {}

    /**
     * Maak een nieuwe PAT voor een user. Retourneert de plain-token
     * EENMALIG zodat de user hem kan kopieren; intern slaan we alleen
     * de hash op.
     *
     * @param string[] $scopes Goedgekeurde scopes (worden genormaliseerd)
     * @return array{id:int,plain_token:string,prefix:string,name:string,scopes:string[],expires_at:?string}
     */
    public function create(int $userId, string $name, array $scopes, ?string $expiresAt = null): array
    {
        $name = trim($name);
        if ($name === '') {
            throw new \InvalidArgumentException('Naam is verplicht.');
        }
        $scopes = Scopes::normalize(implode(' ', $scopes));
        if (empty($scopes)) {
            throw new \InvalidArgumentException('Minimaal één scope is verplicht.');
        }

        $plain  = self::TOKEN_PREFIX . bin2hex(random_bytes(self::TOKEN_BYTES));
        $prefix = substr($plain, 0, 16);
        $hash   = hash('sha256', $plain);

        $this->db->query('INSERT INTO oauth_personal_access_tokens
            (user_id, name, token_prefix, token_hash, scopes, expires_at)
            VALUES (:u, :n, :p, :h, :s, :e)');
        $this->db->bind(':u', $userId);
        $this->db->bind(':n', $name);
        $this->db->bind(':p', $prefix);
        $this->db->bind(':h', $hash);
        $this->db->bind(':s', Scopes::format($scopes));
        $this->db->bind(':e', $expiresAt);
        $this->db->execute();
        $id = (int) $this->db->lastInsertId();

        return [
            'id'          => $id,
            'plain_token' => $plain,
            'prefix'      => $prefix,
            'name'        => $name,
            'scopes'      => $scopes,
            'expires_at'  => $expiresAt,
        ];
    }

    /**
     * Verifieer een bearer-token. Retourneert een authcontext of null.
     *
     * @return array{type:string,user_id:int,scopes:string[],token_id:int,token_name:string}|null
     */
    public function verify(string $token): ?array
    {
        if (!str_starts_with($token, self::TOKEN_PREFIX)) {
            return null;
        }
        $hash = hash('sha256', $token);
        try {
            $this->db->query('SELECT id, user_id, name, scopes, expires_at, revoked_at
                              FROM oauth_personal_access_tokens
                              WHERE token_hash = :h LIMIT 1');
            $this->db->bind(':h', $hash);
            $row = $this->db->single();
            if (!$row) {
                return null;
            }
            if (!empty($row->revoked_at)) {
                return null;
            }
            if (!empty($row->expires_at) && strtotime((string) $row->expires_at) < time()) {
                return null;
            }

            $this->touch((int) $row->id);

            return [
                'type'       => 'pat',
                'user_id'    => (int) $row->user_id,
                'scopes'     => Scopes::normalize((string) $row->scopes),
                'token_id'   => (int) $row->id,
                'token_name' => (string) $row->name,
            ];
        } catch (Throwable $e) {
            error_log('[PAT::verify] ' . $e->getMessage());
            return null;
        }
    }

    public function revoke(int $tokenId, int $userId): bool
    {
        $this->db->query('UPDATE oauth_personal_access_tokens
                          SET revoked_at = CURRENT_TIMESTAMP
                          WHERE id = :id AND user_id = :u AND revoked_at IS NULL');
        $this->db->bind(':id', $tokenId);
        $this->db->bind(':u', $userId);
        return $this->db->execute();
    }

    /**
     * @return array<int,object>
     */
    public function listForUser(int $userId): array
    {
        $this->db->query('SELECT id, name, token_prefix, scopes, last_used_at, last_used_ip,
                                 expires_at, revoked_at, created_at
                          FROM oauth_personal_access_tokens
                          WHERE user_id = :u
                          ORDER BY created_at DESC');
        $this->db->bind(':u', $userId);
        return $this->db->resultSet();
    }

    private function touch(int $tokenId): void
    {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->db->query('UPDATE oauth_personal_access_tokens
                              SET last_used_at = CURRENT_TIMESTAMP, last_used_ip = :ip
                              WHERE id = :id');
            $this->db->bind(':ip', $ip);
            $this->db->bind(':id', $tokenId);
            $this->db->execute();
        } catch (Throwable $e) {
            // touch is best-effort
        }
    }
}
