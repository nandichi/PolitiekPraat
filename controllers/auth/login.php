<?php
require_once BASE_PATH . '/includes/auth_csrf.php';
require_once BASE_PATH . '/includes/auth_remember.php';

$error = '';
$emailValue = '';
$csrf_token = auth_ensure_csrf_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!auth_require_csrf_token_from_post()) {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        require_once BASE_PATH . '/views/templates/header.php';
        echo '<section class="pp-container pp-container--md py-16"><div class="keyline-card p-6 border-[color:var(--color-terracotta)]"><h1 class="font-display text-display-md mb-2 text-[color:var(--color-ink)]">403 - Ongeldige sessieaanvraag</h1><p class="text-[color:var(--color-ink-muted)]">De sessiecontrole is mislukt. Probeer opnieuw in te loggen.</p></div></section>';
        require_once BASE_PATH . '/views/templates/footer.php';
        exit;
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $emailValue = (string) ($email ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Vul alle velden in.';
    } else {
        $db = new Database();
        $db->query("SELECT * FROM users WHERE email = :email");
        $db->bind(':email', $email);
        $user = $db->single();

        if ($user && password_verify($password, $user->password)) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['is_admin'] = $user->is_admin;
            $_SESSION['profile_photo'] = $user->profile_photo;

            if (isset($_POST['remember'])) {
                remember_create_token((int) $user->id);
            } else {
                remember_invalidate_current_token();
            }

            header('Location: ' . URLROOT);
            exit;
        } else {
            $error = 'Ongeldige inloggegevens.';
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<section class="pp-container pp-container--narrow py-16 md:py-24">
    <div class="text-center mb-10">
        <div class="eyebrow mb-4">Account</div>
        <h1 class="font-display text-display-2xl text-[color:var(--color-ink)] leading-[1.05] mb-3">Inloggen bij PolitiekPraat</h1>
        <p class="text-[color:var(--color-ink-muted)] max-w-md mx-auto">
            Welkom terug. Log in om mee te doen aan het gesprek.
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

        <form method="POST" action="<?= pp_e(pp_url('/login')) ?>" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">

            <div class="field">
                <label for="email" class="field__label">E-mailadres</label>
                <input type="email" id="email" name="email" required autocomplete="email"
                       class="input" placeholder="jouw@email.nl"
                       value="<?= pp_e($emailValue) ?>">
            </div>

            <div class="field">
                <div class="flex items-baseline justify-between mb-1.5">
                    <label for="password" class="field__label mb-0">Wachtwoord</label>
                    <a href="<?= pp_e(pp_url('/reset-password')) ?>" class="text-xs text-[color:var(--color-hague)] hover:underline underline-offset-2">Wachtwoord vergeten?</a>
                </div>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       class="input" placeholder="Voer je wachtwoord in">
            </div>

            <label class="flex items-center gap-2 text-sm text-[color:var(--color-ink-muted)] cursor-pointer">
                <input type="checkbox" name="remember" value="1" class="rounded border-[color:var(--color-line-strong)]">
                <span>Onthoud mij op dit apparaat</span>
            </label>

            <button type="submit" class="btn btn--primary btn--lg w-full">
                <?= pp_icon('log-in', 16) ?>
                Inloggen
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-[color:var(--color-keyline)] text-center">
            <p class="text-sm text-[color:var(--color-ink-muted)]">
                Nog geen account?
                <a href="<?= pp_e(pp_url('/register')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">Maak er een aan</a>.
            </p>
        </div>
    </div>

    <p class="text-xs text-[color:var(--color-ink-faint)] text-center mt-6 leading-relaxed">
        Door in te loggen ga je akkoord met onze
        <a href="<?= pp_e(pp_url('/gebruiksvoorwaarden')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">voorwaarden</a>
        en
        <a href="<?= pp_e(pp_url('/privacy-policy')) ?>" class="text-[color:var(--color-hague)] underline-offset-2 hover:underline">privacybeleid</a>.
    </p>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
