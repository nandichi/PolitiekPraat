<?php
/**
 * Inline newsletter sign-up card.
 *
 * Props:
 *   title    string
 *   lead     string|null
 *   action   string         URL waar form naar post (default: /newsletter/subscribe)
 *   button   string         CTA-label (default: 'Aanmelden')
 *   variant  string         default|footer (footer = compactere variant)
 */
$title = $title ?? ($props['title'] ?? 'De PolitiekPraat nieuwsbrief');
$lead = $lead ?? ($props['lead'] ?? 'Elke zondag een korte editie met de belangrijkste politieke ontwikkelingen, de nieuwste blogs en handige stemhulpen.');
$action = $action ?? ($props['action'] ?? '/newsletter/subscribe');
$button = $button ?? ($props['button'] ?? 'Aanmelden');
?>
<aside class="newsletter-inline">
    <div>
        <p class="newsletter-inline__title"><?= pp_e($title) ?></p>
        <p class="newsletter-inline__lead"><?= pp_e($lead) ?></p>
    </div>
    <form class="newsletter-inline__form" method="post" action="<?= pp_e(pp_url($action)) ?>">
        <label class="sr-only" for="newsletter-inline-email">E-mailadres</label>
        <input id="newsletter-inline-email"
               class="input"
               type="email"
               name="email"
               placeholder="je@email.nl"
               required>
        <input type="hidden" name="source" value="inline-cta">
        <button type="submit" class="btn btn--primary btn--block">
            <?= pp_e($button) ?>
            <?= pp_icon('arrow-right', 16) ?>
        </button>
        <p class="text-xs text-[color:var(--color-ink-faint)]">Je kunt je altijd direct uitschrijven. Geen spam.</p>
    </form>
</aside>
