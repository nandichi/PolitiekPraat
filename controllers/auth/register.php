<?php
require_once BASE_PATH . '/includes/auth_csrf.php';

$error = '';
$success = '';
$usernameValue = '';
$emailValue = '';
$csrf_token = auth_ensure_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!auth_require_csrf_token_from_post()) {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        require_once BASE_PATH . '/views/templates/header.php';
        echo '<section class="pp-container pp-container--md py-16"><div class="keyline-card p-6 border-[color:var(--color-terracotta)]"><h1 class="font-display text-display-md mb-2 text-[color:var(--color-ink)]">403 - Ongeldige sessieaanvraag</h1><p class="text-[color:var(--color-ink-muted)]">De sessiecontrole is mislukt. Probeer opnieuw te registreren.</p></div></section>';
        require_once BASE_PATH . '/views/templates/footer.php';
        exit;
    }

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $usernameValue = (string) ($username ?? '');
    $emailValue = (string) ($email ?? '');

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Vul alle velden in.';
    } elseif (strlen($username) < 3) {
        $error = 'Gebruikersnaam moet minimaal 3 karakters bevatten.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ongeldig e-mailadres.';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 karakters bevatten.';
    } elseif ($password !== $confirm_password) {
        $error = 'Wachtwoorden komen niet overeen.';
    } else {
        $db = new Database();

        $db->query("SELECT id FROM users WHERE email = :email");
        $db->bind(':email', $email);

        if ($db->single()) {
            $error = 'Dit e-mailadres is al in gebruik.';
        } else {
            $db->query("SELECT id FROM users WHERE username = :username");
            $db->bind(':username', $username);

            if ($db->single()) {
                $error = 'Deze gebruikersnaam is al in gebruik.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $db->query("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $db->bind(':username', $username);
                $db->bind(':email', $email);
                $db->bind(':password', $hashed_password);

                if ($db->execute()) {
                    $success = 'Account succesvol aangemaakt. Je kunt nu inloggen.';
                } else {
                    $error = 'Er is iets misgegaan bij het aanmaken van je account.';
                }
            }
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<section class="pp-container pp-container--narrow py-16 md:py-24">
    <div class="text-center mb-10">
        <div class="eyebrow mb-4">Account</div>
        <h1 class="font-display text-display-2xl text-[color:var(--color-ink)] leading-[1.05] mb-3">Aanmelden bij PolitiekPraat</h1>
        <p class="text-[color:var(--color-ink-muted)] max-w-md mx-auto">
            Maak een gratis account en doe mee aan het gesprek.
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

        <?php if (!empty($success)): ?>
            <div class="border-l-4 border-[color:var(--color-olive)] bg-[color:var(--color-olive-tint)] text-[color:var(--color-olive)] p-4 mb-6 rounded-r">
                <div class="flex items-start gap-3">
                    <span class="flex-shrink-0 mt-0.5"><?= pp_icon('check-circle', 18) ?></span>
                    <div class="text-sm leading-relaxed">
                        <?= pp_e($success) ?>
                        <div class="mt-2">
                            <a href="<?= pp_e(pp_url('/login')) ?>" class="font-display underline underline-offset-2">Ga naar de inlogpagina</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <form method="POST" action="<?= pp_e(pp_url('/register')) ?>" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">

                <div class="field">
                    <label for="username" class="field__label">Gebruikersnaam</label>
                    <input type="text" id="username" name="username" required autocomplete="username"
                           minlength="3" maxlength="50"
                           class="input" placeholder="bijv. AnneNL"
                           value="<?= pp_e($usernameValue) ?>">
                    <div class="field__hint">Minimaal 3 karakters.</div>
                </div>

                <div class="field">
                    <label for="email" class="field__label">E-mailadres</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           class="input" placeholder="jouw@email.nl"
                           value="<?= pp_e($emailValue) ?>">
                </div>

                <div class="field">
                    <label for="password" class="field__label">Wachtwoord</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                           minlength="6"
                           class="input" placeholder="Minimaal 6 karakters">
                </div>

                <div class="field">
                    <label for="confirm_password" class="field__label">Bevestig wachtwoord</label>
                    <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password"
                           class="input" placeholder="Herhaal je wachtwoord">
                </div>

                <button type="submit" class="btn btn--primary btn--lg w-full">
                    <?= pp_icon('user-plus', 16) ?>
                    Account aanmaken
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-[color:var(--color-keyline)] text-center">
                <p class="text-sm text-[color:var(--color-ink-muted)]">
                    Heb je al een account?
                    <a href="<?= pp_e(pp_url('/login')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">Log dan in</a>.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <p class="text-xs text-[color:var(--color-ink-faint)] text-center mt-6 leading-relaxed">
        Door een account aan te maken ga je akkoord met onze
        <a href="<?= pp_e(pp_url('/voorwaarden')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">voorwaarden</a>
        en
        <a href="<?= pp_e(pp_url('/privacy-policy')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">privacybeleid</a>.
    </p>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
