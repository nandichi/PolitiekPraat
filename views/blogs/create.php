<?php
/**
 * Editorial blog create form (Wave 2).
 *
 * Verwacht: $csrf_token, $error_message (optioneel)
 */
require_once BASE_PATH . '/views/templates/header.php';

require_once BASE_PATH . '/includes/CategoryController.php';
$categoryController = new CategoryController();
$categories = $categoryController->getAll();
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Schrijven',
    'title'   => 'Nieuwe blog',
    'lead'    => 'Deel je analyse, opinie of column. Schrijf in heldere, gewone taal, voeg bronnen toe waar het kan, en wees specifiek.',
]) ?>

<section class="pp-container pp-container--md mt-8 mb-24">
    <?php if (!empty($error_message)): ?>
        <div class="keyline-card p-4 mb-6" style="border-color: var(--color-terracotta);">
            <div class="flex items-start gap-3">
                <span style="color: var(--color-terracotta);"><?= pp_icon('alert-circle', 20) ?></span>
                <div>
                    <p class="font-display text-base text-[color:var(--color-ink)]">Er ging iets mis</p>
                    <p class="text-sm text-[color:var(--color-ink-muted)]"><?= pp_e($error_message) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?= pp_e(pp_url('/blogs/create')) ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">

        <div class="keyline-card p-6 md:p-8">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-6">
                Basis
            </div>

            <div class="field mb-6">
                <label class="field__label" for="title">Titel <span style="color: var(--color-terracotta);">*</span></label>
                <input type="text" id="title" name="title" required
                       class="input"
                       placeholder="Bijvoorbeeld: Waarom geen pompkorting juist sociaal beleid is"
                       value="<?= pp_e($_POST['title'] ?? '') ?>">
                <p class="field__hint">Een heldere, specifieke titel werkt het beste.</p>
            </div>

            <div class="field mb-6">
                <label class="field__label" for="category_id">Categorie</label>
                <select name="category_id" id="category_id" class="select">
                    <option value="">Geen categorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category->id ?>"
                            <?= isset($_POST['category_id']) && (int) $_POST['category_id'] === (int) $category->id ? 'selected' : '' ?>>
                            <?= pp_e($category->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label class="field__label" for="summary">Korte samenvatting</label>
                <textarea name="summary" id="summary" rows="3" maxlength="300"
                          class="textarea"
                          placeholder="Twee zinnen die de lezer doen klikken. Wordt automatisch gegenereerd uit de tekst als je niets invult."><?= pp_e($_POST['summary'] ?? '') ?></textarea>
                <p class="field__hint">Optioneel. Max. 300 karakters.</p>
            </div>
        </div>

        <div class="keyline-card p-6 md:p-8">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-6">
                Media
            </div>

            <div class="field mb-6">
                <label class="field__label" for="image">Hoofdafbeelding</label>
                <input type="file" id="image" name="image" accept="image/*" class="input">
                <p class="field__hint">JPG of PNG, bij voorkeur minimaal 1600px breed.</p>
            </div>

            <div class="field mb-6">
                <label class="field__label" for="video">Video upload</label>
                <input type="file" id="video" name="video" accept="video/*" class="input">
                <p class="field__hint">Optioneel. MP4 wordt aanbevolen.</p>
            </div>

            <div class="field mb-6">
                <label class="field__label" for="video_url">Of: video-URL</label>
                <input type="url" id="video_url" name="video_url" class="input"
                       placeholder="https://www.youtube.com/embed/..."
                       value="<?= pp_e($_POST['video_url'] ?? '') ?>">
                <p class="field__hint">Gebruik een embed-URL (YouTube/Vimeo).</p>
            </div>

            <div class="field mb-6">
                <label class="field__label" for="audio">Audio upload</label>
                <input type="file" id="audio" name="audio" accept=".mp3,.wav,.ogg,audio/mpeg,audio/wav,audio/ogg" class="input">
                <p class="field__hint">Optioneel. Een korte audioversie of podcastfragment.</p>
            </div>

            <div class="field">
                <label class="field__label" for="audio_url">Of: audio-URL</label>
                <input type="url" id="audio_url" name="audio_url" class="input"
                       placeholder="https://..."
                       value="<?= pp_e($_POST['audio_url'] ?? '') ?>">
            </div>
        </div>

        <div class="keyline-card p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)]">
                    Peiling (optioneel)
                </div>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="enablePoll" name="enable_poll"
                           <?= !empty($_POST['enable_poll']) ? 'checked' : '' ?>
                           onchange="document.getElementById('pollFields').hidden = !this.checked">
                    <span class="text-sm text-[color:var(--color-ink)]">Voeg peiling toe</span>
                </label>
            </div>

            <div id="pollFields" <?= empty($_POST['enable_poll']) ? 'hidden' : '' ?>>
                <div class="field mb-4">
                    <label class="field__label" for="poll_question">Vraag</label>
                    <input type="text" id="poll_question" name="poll_question" maxlength="200"
                           class="input"
                           placeholder="Bijvoorbeeld: Moet de pompkorting blijven?"
                           value="<?= pp_e($_POST['poll_question'] ?? '') ?>">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="field">
                        <label class="field__label" for="poll_option_a">Optie A</label>
                        <input type="text" id="poll_option_a" name="poll_option_a" maxlength="100"
                               class="input"
                               placeholder="Ja"
                               value="<?= pp_e($_POST['poll_option_a'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label class="field__label" for="poll_option_b">Optie B</label>
                        <input type="text" id="poll_option_b" name="poll_option_b" maxlength="100"
                               class="input"
                               placeholder="Nee"
                               value="<?= pp_e($_POST['poll_option_b'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="keyline-card p-6 md:p-8">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-6">
                Inhoud
            </div>

            <div class="field">
                <label class="field__label" for="content">Tekst <span style="color: var(--color-terracotta);">*</span></label>
                <textarea name="content" id="content" rows="20" required
                          class="textarea font-mono"
                          style="font-size: 0.95rem; line-height: 1.7;"
                          placeholder="# Tussenkop
Schrijf je tekst in Markdown.

**Vet** of *cursief* werkt natuurlijk ook. Voeg [links](https://...) toe waar nodig.

> Citaten passen tussen `>` aanhalingstekens."><?= pp_e($_POST['content'] ?? '') ?></textarea>
                <p class="field__hint">
                    Markdown wordt automatisch omgezet naar HTML. Tussenkoppen met <code>##</code> structureren het stuk.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between gap-4 flex-wrap">
            <a href="<?= pp_e(pp_url('/blogs/manage')) ?>" class="btn btn--ghost">
                <?= pp_icon('arrow-left', 16) ?>
                Terug naar overzicht
            </a>
            <button type="submit" class="btn btn--primary btn--lg">
                <?= pp_icon('send', 16) ?>
                Publiceer blog
            </button>
        </div>
    </form>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
