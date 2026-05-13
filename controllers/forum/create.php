<?php
// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/login');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Validatie
    if (empty($title) || empty($content)) {
        $error = 'Vul alle velden in';
    } else {
        $db = new Database();
        
        // Voeg topic toe
        $db->query("INSERT INTO forum_topics (title, content, author_id) VALUES (:title, :content, :author_id)");
        $db->bind(':title', $title);
        $db->bind(':content', $content);
        $db->bind(':author_id', $_SESSION['user_id']);
        
        if ($db->execute()) {
            $topic_id = $db->lastInsertId();
            header('Location: ' . URLROOT . '/forum/topic/' . $topic_id);
            exit;
        } else {
            $error = 'Er is iets misgegaan bij het aanmaken van je discussie';
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
$titleValue = isset($title) ? (string) $title : '';
$contentValue = isset($content) ? (string) $content : '';
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Forum',
    'title'   => 'Start een nieuwe discussie',
    'lead'    => 'Stel je vraag scherp, geef context en nodig anderen uit om mee te denken.',
]) ?>

<section class="pp-container pp-container--narrow py-10 md:py-14">
    <div class="mb-6">
        <a href="<?= pp_e(URLROOT) ?>/forum" class="inline-flex items-center gap-2 text-sm text-[color:var(--color-ink-muted)] hover:text-[color:var(--color-hague)] transition-colors">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar forum
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="border-l-4 border-[color:var(--color-terracotta)] bg-[color:var(--color-terracotta-tint)] text-[color:var(--color-terracotta)] p-4 mb-6 rounded-r">
            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 mt-0.5"><?= pp_icon('alert-circle', 18) ?></span>
                <span class="text-sm leading-relaxed"><?= pp_e($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= pp_e(URLROOT) ?>/forum/create" class="keyline-card p-6 md:p-10 space-y-5">
        <div class="field">
            <label for="title" class="field__label">Onderwerp</label>
            <input type="text" name="title" id="title" required class="input" placeholder="Een heldere, concrete titel" value="<?= pp_e($titleValue) ?>">
            <div class="field__hint">Maak de titel kort, scherp en uitnodigend.</div>
        </div>

        <div class="field">
            <label for="content" class="field__label">Bericht</label>
            <textarea name="content" id="content" rows="10" required class="textarea" placeholder="Geef context, deel je gedachten en nodig uit tot reactie..."><?= pp_e($contentValue) ?></textarea>
            <div class="field__hint">Geef context, onderbouw je standpunt en stel een duidelijke vraag.</div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-[color:var(--color-keyline)]">
            <a href="<?= pp_e(URLROOT) ?>/forum" class="btn btn--ghost">Annuleren</a>
            <button type="submit" class="btn btn--primary btn--lg">
                <?= pp_icon('send', 14) ?>
                Discussie starten
            </button>
        </div>
    </form>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 