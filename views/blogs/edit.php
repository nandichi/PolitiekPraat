<?php
/**
 * Editorial blog edit form (Wave 2).
 *
 * Verwacht: $blog, $categories, $csrf_token, $error_message (optioneel)
 */
require_once BASE_PATH . '/views/templates/header.php';

require_once BASE_PATH . '/includes/CategoryController.php';
if (!isset($categories)) {
    $categoryController = new CategoryController();
    $categories = $categoryController->getAll();
}

// Bestaande poll ophalen
$db = new Database();
$db->query("SELECT * FROM blog_polls WHERE blog_id = :blog_id LIMIT 1");
$db->bind(':blog_id', $blog->id);
$existingPoll = $db->single();
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Bewerken',
    'title'   => 'Blog aanpassen',
    'lead'    => 'Werk je publicatie bij. Wijzigingen worden direct opgeslagen.',
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

    <form action="<?= pp_e(pp_url('/blogs/edit/' . $blog->id)) ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">

        <div class="keyline-card p-6 md:p-8">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-6">
                Basis
            </div>

            <div class="field mb-6">
                <label class="field__label" for="title">Titel <span style="color: var(--color-terracotta);">*</span></label>
                <input type="text" id="title" name="title" required
                       class="input"
                       value="<?= pp_e($blog->title) ?>">
            </div>

            <div class="field mb-6">
                <label class="field__label" for="category_id">Categorie</label>
                <select name="category_id" id="category_id" class="select">
                    <option value="">Geen categorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int) $category->id ?>"
                            <?= (int) ($blog->category_id ?? 0) === (int) $category->id ? 'selected' : '' ?>>
                            <?= pp_e($category->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label class="field__label" for="summary">Korte samenvatting</label>
                <textarea name="summary" id="summary" rows="3" maxlength="300"
                          class="textarea"
                          placeholder="Optioneel."><?= pp_e($blog->summary ?? '') ?></textarea>
            </div>
        </div>

        <div class="keyline-card p-6 md:p-8">
            <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-6">
                Media
            </div>

            <?php if (!empty($blog->image_path)): ?>
                <div class="mb-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Huidige afbeelding</p>
                    <div class="editorial-frame editorial-frame--16-9 max-w-md">
                        <img src="<?= pp_e(getBlogImageUrl($blog->image_path)) ?>" alt="<?= pp_e($blog->title) ?>">
                    </div>
                </div>
            <?php endif; ?>

            <div class="field mb-6">
                <label class="field__label" for="image">
                    <?= !empty($blog->image_path) ? 'Vervang afbeelding' : 'Hoofdafbeelding' ?>
                </label>
                <input type="file" id="image" name="image" accept="image/*" class="input">
                <p class="field__hint">Optioneel. Een upload vervangt de bestaande afbeelding.</p>
            </div>

            <?php if (!empty($blog->video_path)): ?>
                <div class="mb-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Huidige video</p>
                    <video controls class="w-full max-w-md rounded-md border border-[color:var(--color-keyline)]" preload="metadata">
                        <source src="<?= pp_e(getBlogVideoUrl($blog->video_path)) ?>" type="video/mp4">
                    </video>
                </div>
            <?php endif; ?>

            <div class="field mb-6">
                <label class="field__label" for="video">Video upload</label>
                <input type="file" id="video" name="video" accept="video/*" class="input">
                <p class="field__hint">Optioneel. Vervangt bestaande video.</p>
            </div>

            <div class="field mb-6">
                <label class="field__label" for="video_url">Of: video-URL</label>
                <input type="url" id="video_url" name="video_url" class="input"
                       placeholder="https://www.youtube.com/embed/..."
                       value="<?= pp_e($blog->video_url ?? '') ?>">
            </div>

            <?php if (!empty($blog->audio_path)): ?>
                <div class="mb-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-2">Huidige audio</p>
                    <audio controls class="w-full max-w-md">
                        <source src="<?= pp_e(rtrim(URLROOT, '/') . '/' . $blog->audio_path) ?>" type="audio/mpeg">
                    </audio>
                    <label class="inline-flex items-center gap-2 mt-3 cursor-pointer">
                        <input type="checkbox" name="remove_audio">
                        <span class="text-sm text-[color:var(--color-ink-muted)]">Verwijder huidige audio</span>
                    </label>
                </div>
            <?php endif; ?>

            <div class="field">
                <label class="field__label" for="audio"><?= !empty($blog->audio_path) ? 'Vervang audio' : 'Audio upload' ?></label>
                <input type="file" id="audio" name="audio" accept=".mp3,.wav,.ogg,audio/mpeg,audio/wav,audio/ogg" class="input">
            </div>
        </div>

        <div class="keyline-card p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)]">
                    Peiling
                </div>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="enablePoll" name="enable_poll"
                           <?= $existingPoll ? 'checked' : '' ?>
                           onchange="document.getElementById('pollFields').hidden = !this.checked">
                    <span class="text-sm text-[color:var(--color-ink)]">Peiling aan</span>
                </label>
            </div>

            <div id="pollFields" <?= $existingPoll ? '' : 'hidden' ?>>
                <div class="field mb-4">
                    <label class="field__label" for="poll_question">Vraag</label>
                    <input type="text" id="poll_question" name="poll_question" maxlength="200"
                           class="input"
                           value="<?= pp_e($existingPoll->question ?? '') ?>">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="field">
                        <label class="field__label" for="poll_option_a">Optie A</label>
                        <input type="text" id="poll_option_a" name="poll_option_a" maxlength="100"
                               class="input"
                               value="<?= pp_e($existingPoll->option_a ?? '') ?>">
                    </div>
                    <div class="field">
                        <label class="field__label" for="poll_option_b">Optie B</label>
                        <input type="text" id="poll_option_b" name="poll_option_b" maxlength="100"
                               class="input"
                               value="<?= pp_e($existingPoll->option_b ?? '') ?>">
                    </div>
                </div>

                <?php if ($existingPoll): ?>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="delete_poll">
                        <span class="text-sm" style="color: var(--color-terracotta);">Verwijder bestaande peiling</span>
                    </label>
                <?php endif; ?>
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
                          style="font-size: 0.95rem; line-height: 1.7;"><?= pp_e($blog->content) ?></textarea>
                <p class="field__hint">Markdown wordt automatisch omgezet naar HTML.</p>
            </div>
        </div>

        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <a href="<?= pp_e(pp_url('/blogs/manage')) ?>" class="btn btn--ghost">
                    <?= pp_icon('arrow-left', 16) ?>
                    Annuleer
                </a>
                <a href="<?= pp_e(pp_url('/blogs/' . $blog->slug)) ?>" class="btn btn--ghost">
                    <?= pp_icon('eye', 16) ?>
                    Bekijk
                </a>
            </div>
            <button type="submit" class="btn btn--primary btn--lg">
                <?= pp_icon('save', 16) ?>
                Wijzigingen opslaan
            </button>
        </div>
    </form>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
