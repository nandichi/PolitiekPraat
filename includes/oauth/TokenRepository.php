<?php
/**
 * Repository voor access tokens en refresh tokens.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

use Database;

class TokenRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function recordAccessToken(array $data): void
    {
        $this->db->query("INSERT INTO oauth_access_tokens
            (jti, client_id, user_id, scope, expires_at)
            VALUES (:jti, :cid, :uid, :scope, :exp)");
        $this->db->bind(':jti', $data['jti']);
        $this->db->bind(':cid', $data['client_id']);
        $this->db->bind(':uid', $data['user_id'] ?? null);
        $this->db->bind(':scope', $data['scope'] ?? '');
        $this->db->bind(':exp', date('Y-m-d H:i:s', (int) $data['expires_at']));
        $this->db->execute();
    }

    public function isAccessTokenActive(string $jti): bool
    {
        $this->db->query('SELECT expires_at, revoked_at FROM oauth_access_tokens WHERE jti = :jti LIMIT 1');
        $this->db->bind(':jti', $jti);
        $row = $this->db->single();
        if (!$row) {
            return false;
        }
        $row = (array) $row;
        if (!empty($row['revoked_at'])) {
            return false;
        }
        return strtotime((string) $row['expires_at']) >= time();
    }

    public function revokeAccessToken(string $jti): void
    {
        $this->db->query('UPDATE oauth_access_tokens SET revoked_at = NOW() WHERE jti = :jti AND revoked_at IS NULL');
        $this->db->bind(':jti', $jti);
        $this->db->execute();
    }

    public function createRefreshToken(array $data): string
    {
        $token = bin2hex(random_bytes(40));
        $hash  = hash('sha256', $token);

        $this->db->query("INSERT INTO oauth_refresh_tokens
            (token_hash, client_id, user_id, scope, parent_jti, expires_at)
            VALUES (:h, :cid, :uid, :scope, :pjti, :exp)");
        $this->db->bind(':h', $hash);
        $this->db->bind(':cid', $data['client_id']);
        $this->db->bind(':uid', $data['user_id'] ?? null);
        $this->db->bind(':scope', $data['scope'] ?? '');
        $this->db->bind(':pjti', $data['parent_jti'] ?? null);
        $this->db->bind(':exp', date('Y-m-d H:i:s', (int) ($data['expires_at'] ?? (time() + 30 * 86400))));
        $this->db->execute();

        return $token;
    }

    public function consumeRefreshToken(string $token): ?array
    {
        $hash = hash('sha256', $token);
        $this->db->query('SELECT * FROM oauth_refresh_tokens WHERE token_hash = :h LIMIT 1');
        $this->db->bind(':h', $hash);
        $row = $this->db->single();
        if (!$row) {
            return null;
        }
        $row = (array) $row;
        if (!empty($row['revoked_at'])) {
            $this->revokeForUserClient(
                $row['user_id'] !== null ? (int) $row['user_id'] : null,
                (string) $row['client_id']
            );
            return null;
        }
        if (strtotime((string) $row['expires_at']) < time()) {
            return null;
        }

        $this->db->query('UPDATE oauth_refresh_tokens SET revoked_at = NOW() WHERE id = :id');
        $this->db->bind(':id', (int) $row['id']);
        $this->db->execute();

        return $row;
    }

    public function revokeRefreshToken(string $token): void
    {
        $hash = hash('sha256', $token);
        $this->db->query('UPDATE oauth_refresh_tokens SET revoked_at = NOW() WHERE token_hash = :h AND revoked_at IS NULL');
        $this->db->bind(':h', $hash);
        $this->db->execute();
    }

    public function revokeForUserClient(?int $userId, string $clientId): void
    {
        $this->db->query('UPDATE oauth_access_tokens SET revoked_at = NOW()
            WHERE client_id = :c AND revoked_at IS NULL' . ($userId !== null ? ' AND user_id = :u' : ''));
        $this->db->bind(':c', $clientId);
        if ($userId !== null) {
            $this->db->bind(':u', $userId);
        }
        $this->db->execute();

        $this->db->query('UPDATE oauth_refresh_tokens SET revoked_at = NOW()
            WHERE client_id = :c AND revoked_at IS NULL' . ($userId !== null ? ' AND user_id = :u' : ''));
        $this->db->bind(':c', $clientId);
        if ($userId !== null) {
            $this->db->bind(':u', $userId);
        }
        $this->db->execute();
    }
}
