<?php
/**
 * Repository voor oauth_authorization_codes.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

use Database;

class AuthCodeRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function create(array $data): string
    {
        $code = bin2hex(random_bytes(32));
        $hash = hash('sha256', $code);

        $this->db->query("INSERT INTO oauth_authorization_codes
            (code_hash, client_id, user_id, redirect_uri, scope, code_challenge,
             code_challenge_method, nonce, state, expires_at)
            VALUES (:hash, :cid, :uid, :red, :scope, :chal, :method, :nonce, :state, :exp)");

        $this->db->bind(':hash', $hash);
        $this->db->bind(':cid', $data['client_id']);
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':red', $data['redirect_uri']);
        $this->db->bind(':scope', $data['scope'] ?? '');
        $this->db->bind(':chal', $data['code_challenge'] ?? null);
        $this->db->bind(':method', $data['code_challenge_method'] ?? null);
        $this->db->bind(':nonce', $data['nonce'] ?? null);
        $this->db->bind(':state', $data['state'] ?? null);
        $this->db->bind(':exp', date('Y-m-d H:i:s', time() + 600));
        $this->db->execute();

        return $code;
    }

    public function consume(string $code): ?array
    {
        $hash = hash('sha256', $code);
        $this->db->query('SELECT * FROM oauth_authorization_codes WHERE code_hash = :h LIMIT 1');
        $this->db->bind(':h', $hash);
        $row = $this->db->single();
        if (!$row) {
            return null;
        }
        $row = (array) $row;

        if (!empty($row['used_at'])) {
            $this->revokeAllForUserClient((int) $row['user_id'], (string) $row['client_id']);
            return null;
        }
        if (strtotime((string) $row['expires_at']) < time()) {
            return null;
        }

        $this->db->query('UPDATE oauth_authorization_codes SET used_at = NOW() WHERE id = :id');
        $this->db->bind(':id', (int) $row['id']);
        $this->db->execute();

        return $row;
    }

    private function revokeAllForUserClient(int $userId, string $clientId): void
    {
        $this->db->query('UPDATE oauth_access_tokens SET revoked_at = NOW()
                          WHERE user_id = :u AND client_id = :c AND revoked_at IS NULL');
        $this->db->bind(':u', $userId);
        $this->db->bind(':c', $clientId);
        $this->db->execute();

        $this->db->query('UPDATE oauth_refresh_tokens SET revoked_at = NOW()
                          WHERE user_id = :u AND client_id = :c AND revoked_at IS NULL');
        $this->db->bind(':u', $userId);
        $this->db->bind(':c', $clientId);
        $this->db->execute();
    }
}
