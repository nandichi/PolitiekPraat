<?php

if (!defined('REMEMBER_ME_INCLUDED')) {
    define('REMEMBER_ME_INCLUDED', true);

    if (!defined('REMEMBER_ME_COOKIE_NAME')) {
        define('REMEMBER_ME_COOKIE_NAME', 'pp_remember');
    }

    if (!defined('REMEMBER_ME_TTL_SECONDS')) {
        define('REMEMBER_ME_TTL_SECONDS', 60 * 60 * 24 * 30);
    }

    if (!function_exists('remember_is_https')) {
        function remember_is_https(): bool {
            if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
                return true;
            }

            if (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) {
                return true;
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                return strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
            }

            return false;
        }
    }

    if (!function_exists('remember_cookie_options')) {
        function remember_cookie_options(int $expires_at): array {
            return [
                'expires' => $expires_at,
                'path' => '/',
                'domain' => '',
                'secure' => remember_is_https(),
                'httponly' => true,
                'samesite' => 'Strict',
            ];
        }
    }

    if (!function_exists('remember_set_cookie')) {
        function remember_set_cookie(string $token_raw, int $expires_at): void {
            setcookie(REMEMBER_ME_COOKIE_NAME, $token_raw, remember_cookie_options($expires_at));
            $_COOKIE[REMEMBER_ME_COOKIE_NAME] = $token_raw;
        }
    }

    if (!function_exists('remember_clear_cookie')) {
        function remember_clear_cookie(): void {
            $expires_at = time() - 3600;
            setcookie(REMEMBER_ME_COOKIE_NAME, '', remember_cookie_options($expires_at));
            unset($_COOKIE[REMEMBER_ME_COOKIE_NAME]);
        }
    }

    if (!function_exists('remember_create_token')) {
        function remember_create_token(int $user_id): void {
            $token_raw = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token_raw);
            $expires_at = time() + REMEMBER_ME_TTL_SECONDS;
            $expires_at_sql = date('Y-m-d H:i:s', $expires_at);

            $db = new Database();
            $db->query('DELETE FROM user_remember_tokens WHERE user_id = :user_id');
            $db->bind(':user_id', $user_id);
            $db->execute();

            $db->query('INSERT INTO user_remember_tokens (user_id, token_hash, expires_at, created_at) VALUES (:user_id, :token_hash, :expires_at, NOW())');
            $db->bind(':user_id', $user_id);
            $db->bind(':token_hash', $token_hash);
            $db->bind(':expires_at', $expires_at_sql);
            $db->execute();

            remember_set_cookie($token_raw, $expires_at);
        }
    }

    if (!function_exists('remember_invalidate_current_token')) {
        function remember_invalidate_current_token(): void {
            $cookie_token_raw = $_COOKIE[REMEMBER_ME_COOKIE_NAME] ?? '';

            if (!is_string($cookie_token_raw) || $cookie_token_raw === '') {
                remember_clear_cookie();
                return;
            }

            if (!preg_match('/^[a-f0-9]{64}$/', $cookie_token_raw)) {
                remember_clear_cookie();
                return;
            }

            try {
                $cookie_token_hash = hash('sha256', $cookie_token_raw);
                $db = new Database();
                $db->query('DELETE FROM user_remember_tokens WHERE token_hash = :token_hash');
                $db->bind(':token_hash', $cookie_token_hash);
                $db->execute();
            } catch (Throwable $e) {
                error_log('[remember-me] Token invalidation failed: ' . $e->getMessage());
            }

            remember_clear_cookie();
        }
    }

    if (!function_exists('remember_restore_session_from_cookie')) {
        function remember_restore_session_from_cookie(): void {
            if (isset($_SESSION['user_id'])) {
                return;
            }

            $cookie_token_raw = $_COOKIE[REMEMBER_ME_COOKIE_NAME] ?? '';

            if (!is_string($cookie_token_raw) || $cookie_token_raw === '') {
                return;
            }

            if (!preg_match('/^[a-f0-9]{64}$/', $cookie_token_raw)) {
                remember_clear_cookie();
                return;
            }

            $cookie_token_hash = hash('sha256', $cookie_token_raw);

            try {
                $db = new Database();
                $db->query('SELECT t.user_id, u.username, u.is_admin, u.profile_photo
                    FROM user_remember_tokens t
                    INNER JOIN users u ON u.id = t.user_id
                    WHERE t.token_hash = :token_hash
                    AND t.expires_at > NOW()
                    LIMIT 1');
                $db->bind(':token_hash', $cookie_token_hash);
                $token_row = $db->single();

                if (!$token_row) {
                    remember_invalidate_current_token();
                    return;
                }

                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $token_row->user_id;
                $_SESSION['username'] = $token_row->username;
                $_SESSION['is_admin'] = (bool) $token_row->is_admin;
                $_SESSION['profile_photo'] = $token_row->profile_photo;

                remember_create_token((int) $token_row->user_id);
            } catch (Throwable $e) {
                error_log('[remember-me] Restore failed: ' . $e->getMessage());
            }
        }
    }
}
