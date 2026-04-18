<?php
/**
 * Repository voor oauth_clients: client registratie, opzoeken en secret-verificatie.
 */

declare(strict_types=1);

namespace PolitiekPraat\OAuth;

use Database;

class ClientRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByClientId(string $clientId): ?array
    {
        $this->db->query('SELECT * FROM oauth_clients WHERE client_id = :cid LIMIT 1');
        $this->db->bind(':cid', $clientId);
        $row = $this->db->single();
        if (!$row) {
            return null;
        }
        return $this->hydrate((array) $row);
    }

    /** @return array<int, array<string, mixed>> */
    public function all(): array
    {
        $this->db->query('SELECT * FROM oauth_clients ORDER BY created_at DESC');
        $rows = $this->db->resultSet();
        if (!is_array($rows)) {
            return [];
        }
        return array_map(fn($r) => $this->hydrate((array) $r), $rows);
    }

    public function verifySecret(array $client, string $providedSecret): bool
    {
        if (empty($client['client_secret_hash'])) {
            return false;
        }
        return password_verify($providedSecret, (string) $client['client_secret_hash']);
    }

    public function create(array $input): array
    {
        $clientId = $input['client_id'] ?? 'cli_' . bin2hex(random_bytes(12));
        $plainSecret = null;
        $secretHash = null;

        $isPublic = !empty($input['is_public']);
        if (!$isPublic) {
            $plainSecret = $input['client_secret'] ?? bin2hex(random_bytes(24));
            $secretHash = password_hash($plainSecret, PASSWORD_DEFAULT);
        }

        $redirectUris  = $this->asJsonArray($input['redirect_uris'] ?? []);
        $grantTypes    = $this->asJsonArray($input['grant_types'] ?? ['authorization_code']);
        $responseTypes = $this->asJsonArray($input['response_types'] ?? ['code']);
        $scopes        = $this->asJsonArray($input['scopes'] ?? Scopes::supported());

        $authMethod = $input['token_endpoint_auth_method']
            ?? ($isPublic ? 'none' : 'client_secret_basic');

        $registrationToken = null;
        $registrationHash = null;
        if (!empty($input['issue_registration_token'])) {
            $registrationToken = bin2hex(random_bytes(32));
            $registrationHash = hash('sha256', $registrationToken);
        }

        $this->db->query("INSERT INTO oauth_clients
            (client_id, client_secret_hash, client_name, client_uri, logo_uri, tos_uri, policy_uri,
             redirect_uris, grant_types, response_types, scopes, token_endpoint_auth_method,
             is_public, owner_user_id, registration_access_token_hash, registration_client_uri)
            VALUES (:cid, :sec, :name, :curi, :luri, :turi, :puri,
                    :red, :gt, :rt, :sc, :auth, :pub, :owner, :regtok, :regcli)");

        $this->db->bind(':cid', $clientId);
        $this->db->bind(':sec', $secretHash);
        $this->db->bind(':name', (string) ($input['client_name'] ?? 'Unnamed client'));
        $this->db->bind(':curi', $input['client_uri'] ?? null);
        $this->db->bind(':luri', $input['logo_uri'] ?? null);
        $this->db->bind(':turi', $input['tos_uri'] ?? null);
        $this->db->bind(':puri', $input['policy_uri'] ?? null);
        $this->db->bind(':red', $redirectUris);
        $this->db->bind(':gt', $grantTypes);
        $this->db->bind(':rt', $responseTypes);
        $this->db->bind(':sc', $scopes);
        $this->db->bind(':auth', $authMethod);
        $this->db->bind(':pub', $isPublic ? 1 : 0);
        $this->db->bind(':owner', $input['owner_user_id'] ?? null);
        $this->db->bind(':regtok', $registrationHash);
        $this->db->bind(':regcli', $input['registration_client_uri'] ?? null);
        $this->db->execute();

        $stored = $this->findByClientId($clientId);
        if ($stored === null) {
            throw new \RuntimeException('Client aanmaak mislukte');
        }
        if ($plainSecret !== null) {
            $stored['__plain_client_secret'] = $plainSecret;
        }
        if ($registrationToken !== null) {
            $stored['__registration_access_token'] = $registrationToken;
        }
        return $stored;
    }

    public function delete(string $clientId): bool
    {
        $this->db->query('DELETE FROM oauth_clients WHERE client_id = :cid');
        $this->db->bind(':cid', $clientId);
        return (bool) $this->db->execute();
    }

    public function clientHasGrant(array $client, string $grant): bool
    {
        return in_array($grant, (array) ($client['grant_types'] ?? []), true);
    }

    public function clientHasRedirect(array $client, string $redirectUri): bool
    {
        $uris = (array) ($client['redirect_uris'] ?? []);
        return in_array($redirectUri, $uris, true);
    }

    private function hydrate(array $row): array
    {
        foreach (['redirect_uris', 'grant_types', 'response_types', 'scopes'] as $field) {
            if (isset($row[$field]) && is_string($row[$field])) {
                $decoded = json_decode($row[$field], true);
                $row[$field] = is_array($decoded) ? $decoded : [];
            } elseif (!isset($row[$field]) || !is_array($row[$field])) {
                $row[$field] = [];
            }
        }
        return $row;
    }

    private function asJsonArray($value): string
    {
        if (!is_array($value)) {
            $value = $value === null ? [] : [(string) $value];
        }
        $value = array_values(array_filter(array_map(
            static fn($v) => is_string($v) ? trim($v) : $v,
            $value
        ), static fn($v) => $v !== '' && $v !== null));
        return json_encode(array_values($value), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
