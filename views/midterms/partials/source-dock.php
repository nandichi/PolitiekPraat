<?php
$sources = $midtermsContext['sources'] ?? [];
if (empty($sources)) return;
?>
<section class="mt-sources" aria-labelledby="mt-sources-title" data-mt-reveal>
    <div class="mt-section-heading">
        <div>
            <span class="mt-panel-label">Controleerbaar</span>
            <h2 id="mt-sources-title">Bronnen achter de tracker</h2>
        </div>
        <p>Institutionele feiten, redactionele ratings en marktprijzen worden bewust uit elkaar gehouden.</p>
    </div>
    <div class="mt-sources__grid">
        <?php foreach ($sources as $source): ?>
            <a href="<?= pp_e($source['url'] ?? '#') ?>" target="_blank" rel="noopener" class="mt-source-card">
                <span class="mt-source-card__short"><?= pp_e($source['short'] ?? '') ?></span>
                <strong><?= pp_e($source['name'] ?? '') ?></strong>
                <span><?= pp_e($source['description'] ?? '') ?></span>
                <?= pp_icon('arrow-up-right', 15) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>
