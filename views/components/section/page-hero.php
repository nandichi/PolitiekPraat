<?php
/**
 * Standaard page-hero (eyebrow + title + lead).
 *
 * Props:
 *   eyebrow  string|null  Korte label boven titel (uppercase)
 *   title    string
 *   lead     string|null
 *   children string|null  Extra HTML onderaan (bv. CTA buttons)
 *   meta     array|null   Lijst van ['icon' => 'lucide-name', 'text' => '...'] items onder de lead
 */
$eyebrow = $eyebrow ?? ($props['eyebrow'] ?? null);
$title = $title ?? ($props['title'] ?? '');
$lead = $lead ?? ($props['lead'] ?? null);
$children = $children ?? ($props['children'] ?? null);
$meta = $meta ?? ($props['meta'] ?? null);
?>
<section class="page-hero">
    <div class="pp-container pp-container--xl">
        <?php if ($eyebrow): ?>
            <span class="page-hero__eyebrow"><?= pp_e($eyebrow) ?></span>
        <?php endif; ?>
        <h1 class="page-hero__title"><?= pp_e($title) ?></h1>
        <?php if ($lead): ?>
            <p class="page-hero__lead"><?= pp_e($lead) ?></p>
        <?php endif; ?>
        <?php if (is_array($meta) && !empty($meta)): ?>
            <ul class="page-hero__meta mt-5 flex flex-wrap gap-x-5 gap-y-2 text-sm text-[color:var(--color-ink-muted)]">
                <?php foreach ($meta as $item): ?>
                    <li class="inline-flex items-center gap-2">
                        <?php if (!empty($item['icon'])): ?>
                            <span class="text-[color:var(--color-hague)]"><?= pp_icon($item['icon'], 14) ?></span>
                        <?php endif; ?>
                        <span><?= pp_e($item['text'] ?? '') ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if ($children): ?>
            <div class="mt-5"><?= $children ?></div>
        <?php endif; ?>
    </div>
</section>
