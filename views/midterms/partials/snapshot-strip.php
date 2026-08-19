<?php
$midtermsContext = $midtermsContext ?? [];
$house = $midtermsContext['house'] ?? [];
$senate = $midtermsContext['senate'] ?? [];
$governors = $midtermsContext['governors'] ?? [];
?>
<section class="mt-snapshot" aria-label="Stand van zaken" data-mt-reveal>
    <div class="pp-container pp-container--xl">
        <div class="mt-snapshot__grid">
            <article class="mt-snapshot__item">
                <span class="mt-snapshot__label">Meerderheid Huis</span>
                <strong class="mt-snapshot__value" data-mt-count="<?= (int) ($house['majority'] ?? 218) ?>"><?= (int) ($house['majority'] ?? 218) ?></strong>
                <span class="mt-snapshot__detail">van 435 zetels</span>
            </article>
            <article class="mt-snapshot__item">
                <span class="mt-snapshot__label">Huidige Senaat</span>
                <strong class="mt-snapshot__value"><i class="mt-blue"><?= (int) ($senate['current']['D'] ?? 45) ?></i><b>–</b><i class="mt-red"><?= (int) ($senate['current']['R'] ?? 53) ?></i></strong>
                <span class="mt-snapshot__detail"><?= (int) ($senate['current']['I'] ?? 2) ?> onafhankelijken caucussen met D</span>
            </article>
            <article class="mt-snapshot__item">
                <span class="mt-snapshot__label">Senaatsraces</span>
                <strong class="mt-snapshot__value" data-mt-count="<?= (int) ($senate['races'] ?? 35) ?>"><?= (int) ($senate['races'] ?? 35) ?></strong>
                <span class="mt-snapshot__detail">33 regulier + 2 speciaal</span>
            </article>
            <article class="mt-snapshot__item">
                <span class="mt-snapshot__label">Toss-ups Huis</span>
                <strong class="mt-snapshot__value" data-mt-count="<?= (int) ($house['rating_summary']['tossup'] ?? 19) ?>"><?= (int) ($house['rating_summary']['tossup'] ?? 19) ?></strong>
                <span class="mt-snapshot__detail">ratingoverzicht 13 augustus</span>
            </article>
            <article class="mt-snapshot__item">
                <span class="mt-snapshot__label">Gouverneursraces</span>
                <strong class="mt-snapshot__value" data-mt-count="<?= (int) ($governors['races'] ?? 36) ?>"><?= (int) ($governors['races'] ?? 36) ?></strong>
                <span class="mt-snapshot__detail">verspreid over 36 staten</span>
            </article>
        </div>
    </div>
</section>
