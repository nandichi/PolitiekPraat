<?php
/**
 * Wachtwoord vergeten / opnieuw instellen.
 *
 * Twee fases in één controller:
 *   1. request : gebruiker vult e-mailadres in -> we mailen een reset-link.
 *   2. reset   : gebruiker opent de link (?token=...) -> kiest nieuw wachtwoord.
 *
 * Veiligheid:
 *   - We slaan alleen de SHA-256 hash van de token op (nooit de ruwe token).
 *   - Tokens verlopen na 1 uur en zijn eenmalig bruikbaar (used_at).
 *   - De request-fase geeft altijd dezelfde generieke melding, zodat niet te
 *     achterhalen is welke e-mailadressen een account hebben (user enumeration).
 *   - Beide formulieren zijn CSRF-beschermd.
 */

require_once BASE_PATH . '/includes/auth_csrf.php';
require_once BASE_PATH . '/includes/resend_helper.php';

// Al ingelogd? Dan is een reset niet nodig.
if (isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/profile');
    exit;
}

$csrf_token = auth_ensure_csrf_token();
$error = '';
$success = '';
$emailValue = '';

// Ruwe token uit GET of POST halen.
$rawToken = '';
if (isset($_GET['token']) && is_string($_GET['token'])) {
    $rawToken = trim($_GET['token']);
}
if ($rawToken === '' && isset($_POST['token']) && is_string($_POST['token'])) {
    $rawToken = trim($_POST['token']);
}

$mode = ($rawToken !== '') ? 'reset' : 'request';
$tokenValid = false;

/**
 * Zoek een geldige (ongebruikte, niet-verlopen) reset-rij bij een ruwe token.
 */
$findValidReset = static function (string $raw) {
    if ($raw === '' || !ctype_xdigit($raw) || strlen($raw) !== 64) {
        return null;
    }
    $hash = hash('sha256', $raw);
    $db = new Database();
    $db->query("SELECT id, user_id, expires_at, used_at FROM password_resets WHERE token_hash = :hash LIMIT 1");
    $db->bind(':hash', $hash);
    $row = $db->single();
    if (!$row) {
        return null;
    }
    if ($row->used_at !== null) {
        return null;
    }
    if (strtotime((string) $row->expires_at) < time()) {
        return null;
    }
    return $row;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!auth_require_csrf_token_from_post()) {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        require_once BASE_PATH . '/views/templates/header.php';
        echo '<section class="pp-container pp-container--md py-16"><div class="keyline-card p-6 border-[color:var(--color-terracotta)]"><h1 class="font-display text-display-md mb-2 text-[color:var(--color-ink)]">403 - Ongeldige sessieaanvraag</h1><p class="text-[color:var(--color-ink-muted)]">De sessiecontrole is mislukt. Probeer het opnieuw.</p></div></section>';
        require_once BASE_PATH . '/views/templates/footer.php';
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'request') {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $emailValue = (string) ($email ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Vul een geldig e-mailadres in.';
            $mode = 'request';
        } else {
            $db = new Database();
            $db->query("SELECT id, username, email FROM users WHERE email = :email LIMIT 1");
            $db->bind(':email', $email);
            $user = $db->single();

            if ($user) {
                // Eerdere ongebruikte tokens opruimen: alleen de nieuwste link werkt.
                $db->query("DELETE FROM password_resets WHERE user_id = :uid AND used_at IS NULL");
                $db->bind(':uid', $user->id);
                $db->execute();

                $raw = bin2hex(random_bytes(32));
                $hash = hash('sha256', $raw);
                $expires = date('Y-m-d H:i:s', time() + 3600);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;

                $db->query("INSERT INTO password_resets (user_id, token_hash, expires_at, ip_address) VALUES (:uid, :hash, :exp, :ip)");
                $db->bind(':uid', $user->id);
                $db->bind(':hash', $hash);
                $db->bind(':exp', $expires);
                $db->bind(':ip', $ip);
                $db->execute();

                $resetUrl = rtrim(URLROOT, '/') . '/reset-password?token=' . $raw;
                $html = pp_password_reset_email_html($resetUrl, (string) $user->username);
                $text = "Hoi " . $user->username . ",\n\n"
                    . "Je hebt aangevraagd om je wachtwoord opnieuw in te stellen. Open onderstaande link (1 uur geldig):\n\n"
                    . $resetUrl . "\n\n"
                    . "Heb je dit niet aangevraagd? Dan kun je deze e-mail negeren.\n\nPolitiekPraat";

                $sendResult = pp_send_email((string) $user->email, 'Stel je wachtwoord opnieuw in - PolitiekPraat', $html, $text);
                if (!($sendResult['ok'] ?? false)) {
                    error_log('[reset-password] mail niet verstuurd voor user ' . $user->id . ': ' . ($sendResult['error'] ?? 'onbekend'));
                }
            }

            // Altijd dezelfde generieke melding (geen user enumeration).
            $success = 'Als er een account bij dit e-mailadres hoort, ontvang je binnen enkele minuten een e-mail met instructies om je wachtwoord opnieuw in te stellen. Controleer ook je spam-map.';
            $mode = 'request';
        }
    } elseif ($action === 'reset') {
        $row = $findValidReset($rawToken);
        if (!$row) {
            $error = 'Deze reset-link is ongeldig of verlopen. Vraag hieronder een nieuwe aan.';
            $mode = 'request';
            $rawToken = '';
        } else {
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (strlen($password) < 6) {
                $error = 'Wachtwoord moet minimaal 6 karakters bevatten.';
                $mode = 'reset';
                $tokenValid = true;
            } elseif ($password !== $confirm) {
                $error = 'De wachtwoorden komen niet overeen.';
                $mode = 'reset';
                $tokenValid = true;
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $db = new Database();

                $db->query("UPDATE users SET password = :pw WHERE id = :uid");
                $db->bind(':pw', $hashed);
                $db->bind(':uid', $row->user_id);
                $db->execute();

                // Token markeren als gebruikt en overige tokens van deze user wissen.
                $db->query("UPDATE password_resets SET used_at = NOW() WHERE id = :id");
                $db->bind(':id', $row->id);
                $db->execute();
                $db->query("DELETE FROM password_resets WHERE user_id = :uid AND used_at IS NULL");
                $db->bind(':uid', $row->user_id);
                $db->execute();

                $success = 'Je wachtwoord is succesvol gewijzigd. Je kunt nu inloggen met je nieuwe wachtwoord.';
                $mode = 'done';
            }
        }
    }
} else {
    // GET: bij een token controleren of die geldig is.
    if ($mode === 'reset') {
        $row = $findValidReset($rawToken);
        if ($row) {
            $tokenValid = true;
        } else {
            $error = 'Deze reset-link is ongeldig of verlopen. Vraag hieronder een nieuwe aan.';
            $mode = 'request';
            $rawToken = '';
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<section class="pp-container pp-container--narrow py-16 md:py-24">
    <div class="text-center mb-10">
        <div class="eyebrow mb-4">Account</div>
        <h1 class="font-display text-display-2xl text-[color:var(--color-ink)] leading-[1.05] mb-3">
            <?= $mode === 'reset' ? 'Nieuw wachtwoord kiezen' : 'Wachtwoord vergeten' ?>
        </h1>
        <p class="text-[color:var(--color-ink-muted)] max-w-md mx-auto">
            <?php if ($mode === 'reset'): ?>
                Kies hieronder een nieuw wachtwoord voor je account.
            <?php else: ?>
                Vul je e-mailadres in en we sturen je een link om je wachtwoord opnieuw in te stellen.
            <?php endif; ?>
        </p>
    </div>

    <div class="keyline-card p-6 md:p-10">
        <?php if (!empty($error)): ?>
            <div class="border-l-4 border-[color:var(--color-terracotta)] bg-[color:var(--color-terracotta-tint)] text-[color:var(--color-terracotta)] p-4 mb-6 rounded-r">
                <div class="flex items-start gap-3">
                    <span class="flex-shrink-0 mt-0.5"><?= pp_icon('alert-circle', 18) ?></span>
                    <span class="text-sm leading-relaxed"><?= pp_e($error) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($mode === 'done'): ?>
            <div class="border-l-4 border-[color:var(--color-olive)] bg-[color:var(--color-olive-tint)] text-[color:var(--color-olive)] p-4 mb-2 rounded-r">
                <div class="flex items-start gap-3">
                    <span class="flex-shrink-0 mt-0.5"><?= pp_icon('check-circle', 18) ?></span>
                    <div class="text-sm leading-relaxed">
                        <?= pp_e($success) ?>
                        <div class="mt-3">
                            <a href="<?= pp_e(pp_url('/login')) ?>" class="btn btn--primary btn--sm">
                                <?= pp_icon('log-in', 15) ?>
                                Naar inloggen
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($mode === 'reset' && $tokenValid): ?>
            <form method="POST" action="<?= pp_e(pp_url('/reset-password')) ?>" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">
                <input type="hidden" name="action" value="reset">
                <input type="hidden" name="token" value="<?= pp_e($rawToken) ?>">

                <div class="field">
                    <label for="password" class="field__label">Nieuw wachtwoord</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                           minlength="6" class="input" placeholder="Minimaal 6 karakters">
                    <div class="field__hint">Kies een sterk wachtwoord van minimaal 6 karakters.</div>
                </div>

                <div class="field">
                    <label for="confirm_password" class="field__label">Bevestig nieuw wachtwoord</label>
                    <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password"
                           class="input" placeholder="Herhaal je nieuwe wachtwoord">
                </div>

                <button type="submit" class="btn btn--primary btn--lg w-full">
                    <?= pp_icon('shield-check', 16) ?>
                    Wachtwoord opslaan
                </button>
            </form>

        <?php elseif (!empty($success)): ?>
            <div class="border-l-4 border-[color:var(--color-olive)] bg-[color:var(--color-olive-tint)] text-[color:var(--color-olive)] p-4 rounded-r">
                <div class="flex items-start gap-3">
                    <span class="flex-shrink-0 mt-0.5"><?= pp_icon('mail-check', 18) ?></span>
                    <span class="text-sm leading-relaxed"><?= pp_e($success) ?></span>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-[color:var(--color-keyline)] text-center">
                <p class="text-sm text-[color:var(--color-ink-muted)]">
                    <a href="<?= pp_e(pp_url('/login')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">Terug naar inloggen</a>
                </p>
            </div>

        <?php else: ?>
            <form method="POST" action="<?= pp_e(pp_url('/reset-password')) ?>" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">
                <input type="hidden" name="action" value="request">

                <div class="field">
                    <label for="email" class="field__label">E-mailadres</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           class="input" placeholder="jouw@email.nl" value="<?= pp_e($emailValue) ?>">
                </div>

                <button type="submit" class="btn btn--primary btn--lg w-full">
                    <?= pp_icon('mail', 16) ?>
                    Stuur reset-link
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-[color:var(--color-keyline)] text-center">
                <p class="text-sm text-[color:var(--color-ink-muted)]">
                    Weet je je wachtwoord weer?
                    <a href="<?= pp_e(pp_url('/login')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">Log dan in</a>.
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
