<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<?php
$totaal = $presidentenStatistieken->totaal_presidenten ?? 0;
$gemLeeftijd = isset($presidentenStatistieken->gemiddelde_leeftijd) ? round((float) $presidentenStatistieken->gemiddelde_leeftijd) : null;
$nogLevend = $presidentenStatistieken->nog_levend ?? null;
$gemTermijn = isset($presidentenStatistieken->gemiddelde_termijn_jaren) ? round((float) $presidentenStatistieken->gemiddelde_termijn_jaren, 1) : null;

$heroMeta = [
    ['icon' => 'users',    'text' => $totaal . ' presidenten'],
    ['icon' => 'landmark', 'text' => 'Sinds 1789'],
];
if ($gemTermijn !== null) {
    $heroMeta[] = ['icon' => 'clock', 'text' => 'Gemiddeld ' . $gemTermijn . ' jaar in functie'];
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Verenigde Staten',
    'title'   => 'Presidenten',
    'lead'    => 'Een chronologisch overzicht van alle presidenten van de Verenigde Staten sinds George Washington. Klik op een president voor de uitgebreide biografie, prestaties en feiten.',
    'meta'    => $heroMeta,
]) ?>

<section class="pp-container pp-container--xl py-10 md:py-14 space-y-12">

    <?php if (isset($presidentenStatistieken)): ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Totaal</div>
                <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= (int) $totaal ?></div>
                <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Presidenten sinds 1789</p>
            </div>
            <?php if ($gemLeeftijd !== null): ?>
                <div class="keyline-card p-5">
                    <div class="eyebrow mb-2">Gem. leeftijd</div>
                    <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= $gemLeeftijd ?></div>
                    <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Jaar bij aantreden</p>
                </div>
            <?php endif; ?>
            <?php if ($nogLevend !== null): ?>
                <div class="keyline-card p-5">
                    <div class="eyebrow mb-2">Nog levend</div>
                    <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= (int) $nogLevend ?></div>
                    <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Ex-presidenten</p>
                </div>
            <?php endif; ?>
            <?php if ($gemTermijn !== null): ?>
                <div class="keyline-card p-5">
                    <div class="eyebrow mb-2">Gem. termijn</div>
                    <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= $gemTermijn ?></div>
                    <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Jaar in functie</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($presidentenPerPeriode) && !empty($presidentenPerPeriode)): ?>
        <?php foreach ($presidentenPerPeriode as $periode => $presidenten):
            $gesorteerd = is_array($presidenten) ? $presidenten : [];
            if (!empty($gesorteerd)) {
                usort($gesorteerd, function ($a, $b) {
                    return ($b->president_nummer ?? 0) - ($a->president_nummer ?? 0);
                });
            }
        ?>
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <span class="eyebrow"><?= pp_e((string) $periode) ?></span>
                    <span class="flex-1 h-px bg-[color:var(--color-keyline)]"></span>
                    <span class="text-sm text-[color:var(--color-ink-muted)] font-mono text-tabular">
                        <?= count($gesorteerd) ?> <?= count($gesorteerd) === 1 ? 'president' : 'presidenten' ?>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($gesorteerd as $president):
                        $foto = $president->foto_url ?? '';
                        $naam = $president->naam ?? 'Onbekend';
                        $partij = $president->partij ?? '';
                        $nummer = $president->president_nummer ?? null;
                        $start = $president->periode_start ?? null;
                        $eind  = $president->periode_eind ?? null;
                        $leeftijd = $president->leeftijd_bij_aantreden ?? null;

                        $partijToneClass = '';
                        $partijDotColor = 'var(--color-ink-faint)';
                        if ($partij === 'Republican') {
                            $partijDotColor = 'var(--color-terracotta)';
                        } elseif ($partij === 'Democratic') {
                            $partijDotColor = 'var(--color-hague)';
                        }
                    ?>
                        <button type="button"
                                onclick="openPresidentModal(<?= htmlspecialchars(json_encode($president), ENT_QUOTES, 'UTF-8') ?>)"
                                class="keyline-card text-left hover:border-[color:var(--color-hague)] transition-colors overflow-hidden group">
                            <div class="photo-frame" style="border:none; padding: 0; border-radius: 0; box-shadow: none;">
                                <?php if ($foto): ?>
                                    <img src="<?= pp_e($foto) ?>"
                                         alt="<?= pp_e($naam) ?>"
                                         loading="lazy"
                                         style="aspect-ratio: 4/5; object-fit: cover; border-radius: 0;">
                                <?php else: ?>
                                    <div class="flex items-center justify-center bg-[color:var(--color-paper-2)] text-[color:var(--color-ink-faint)]"
                                         style="aspect-ratio: 4/5;">
                                        <?= pp_icon('user', 48) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <?php if ($nummer !== null): ?>
                                        <span class="font-mono text-tabular text-xs text-[color:var(--color-ink-faint)]">#<?= (int) $nummer ?></span>
                                    <?php endif; ?>
                                    <?php if ($partij): ?>
                                        <span class="inline-flex items-center gap-1.5 text-xs text-[color:var(--color-ink-muted)]">
                                            <span class="w-2 h-2 rounded-full" style="background: <?= $partijDotColor ?>;"></span>
                                            <?= pp_e($partij) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-1 leading-tight group-hover:text-[color:var(--color-hague)] transition-colors">
                                    <?= pp_e($naam) ?>
                                </h3>
                                <?php if (!empty($president->bijnaam)): ?>
                                    <p class="text-xs italic text-[color:var(--color-ink-muted)] mb-2">"<?= pp_e($president->bijnaam) ?>"</p>
                                <?php endif; ?>
                                <?php if ($start): ?>
                                    <p class="text-xs text-[color:var(--color-ink-muted)] font-mono text-tabular">
                                        <?= date('Y', strtotime($start)) ?>
                                        &mdash;
                                        <?= $eind ? date('Y', strtotime($eind)) : 'heden' ?>
                                        <?php if ($leeftijd): ?>
                                            <span class="text-[color:var(--color-ink-faint)]">&middot; <?= (int) $leeftijd ?> jaar</span>
                                        <?php endif; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="keyline-card p-8 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[color:var(--color-paper-2)] mb-4 text-[color:var(--color-ink-muted)]">
                <?= pp_icon('users', 22) ?>
            </div>
            <p class="text-sm text-[color:var(--color-ink-muted)]">Er zijn momenteel geen presidenten gegevens beschikbaar.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($familieDynastieen) && !empty($familieDynastieen)): ?>
        <div id="familie-dynastieen">
            <div class="flex items-center gap-4 mb-6">
                <span class="eyebrow">Familie dynastieën</span>
                <span class="flex-1 h-px bg-[color:var(--color-keyline)]"></span>
            </div>

            <div class="keyline-card p-6 md:p-8 mb-6">
                <h2 class="font-display text-display-lg text-[color:var(--color-ink)] mb-3 leading-tight">Macht loopt in de familie</h2>
                <p class="text-sm text-[color:var(--color-ink-muted)] leading-relaxed max-w-prose">
                    Verschillende families brachten meerdere presidenten voort &mdash; vader-zoon, neef-oom, en aanverwante connecties die soms generaties omspannen.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($familieDynastieen as $familieNaam => $familiePresidenten): ?>
                    <div class="keyline-card p-6">
                        <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-4 leading-tight"><?= pp_e($familieNaam) ?></h3>
                        <div class="space-y-3">
                            <?php foreach ($familiePresidenten as $p): ?>
                                <div class="flex items-start gap-3 pb-3 border-b border-[color:var(--color-keyline)] last:border-0 last:pb-0">
                                    <span class="font-mono text-tabular text-xs text-[color:var(--color-ink-faint)] mt-1 flex-shrink-0">#<?= (int) ($p->president_nummer ?? 0) ?></span>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-display text-[color:var(--color-ink)] leading-tight"><?= pp_e($p->naam ?? '') ?></div>
                                        <?php if (!empty($p->familie_connecties)): ?>
                                            <div class="text-xs text-[color:var(--color-ink-muted)] mt-1"><?= pp_e($p->familie_connecties) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="<?= URLROOT ?>/amerikaanse-verkiezingen" class="btn btn--ghost">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar Amerikaanse verkiezingen
        </a>
    </div>
</section>

<div id="presidentModal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background: rgba(0,0,0,0.55);">
    <div class="bg-[color:var(--color-paper)] rounded-md shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden border border-[color:var(--color-keyline)]">
        <div id="modalContent" class="overflow-y-auto max-h-[90vh]"></div>
    </div>
</div>

<script>
function openPresidentModal(president) {
    const modal = document.getElementById('presidentModal');
    const content = document.getElementById('modalContent');

    const prestaties = Array.isArray(president.prestaties) ? president.prestaties : [];
    const funFacts = Array.isArray(president.fun_facts) ? president.fun_facts : [];

    const startYear = president.periode_start ? new Date(president.periode_start).getFullYear() : '';
    const endYear = president.periode_eind ? new Date(president.periode_eind).getFullYear() : 'heden';

    const partijDot = president.partij === 'Republican'
        ? 'var(--color-terracotta)'
        : (president.partij === 'Democratic' ? 'var(--color-hague)' : 'var(--color-ink-faint)');

    content.innerHTML = `
        <div class="relative">
            <button type="button"
                    onclick="closePresidentModal()"
                    aria-label="Sluiten"
                    style="position:absolute;top:1rem;right:1rem;z-index:10;width:2rem;height:2rem;border-radius:.25rem;background:var(--color-paper-2);border:1px solid var(--color-keyline);color:var(--color-ink);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>

            <div style="padding: 2rem 2rem 1.5rem; border-bottom: 1px solid var(--color-keyline);">
                <div style="display:flex; gap: 1.5rem; flex-wrap: wrap; align-items: flex-start;">
                    <div style="width: 7rem; flex-shrink:0;">
                        ${president.foto_url
                            ? `<div class="photo-frame"><img src="${president.foto_url}" alt="${escapeHtml(president.naam || '')}" style="aspect-ratio: 4/5; object-fit: cover;"></div>`
                            : `<div class="photo-frame" style="aspect-ratio: 4/5; display:flex; align-items:center; justify-content:center; background:var(--color-paper-2); color:var(--color-ink-faint);">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                              </div>`
                        }
                    </div>
                    <div style="flex:1; min-width: 220px;">
                        <div class="eyebrow" style="margin-bottom: .5rem;">${president.president_nummer ? '#' + president.president_nummer + ' president van de VS' : 'President van de VS'}</div>
                        <h2 class="font-display" style="font-size: var(--text-display-xl); color: var(--color-ink); margin: 0 0 .25rem; line-height: 1.05;">${escapeHtml(president.naam || '')}</h2>
                        ${president.bijnaam ? `<p style="font-style: italic; color: var(--color-ink-muted); margin: 0 0 .75rem;">"${escapeHtml(president.bijnaam)}"</p>` : ''}
                        <div style="display:flex; flex-wrap: wrap; gap: .5rem; margin-top: .5rem;">
                            ${president.partij ? `<span class="chip" style="display:inline-flex;align-items:center;gap:.4rem;"><span style="width:.5rem;height:.5rem;border-radius:9999px;background:${partijDot};"></span>${escapeHtml(president.partij)}</span>` : ''}
                            <span class="chip">${startYear} &mdash; ${endYear}</span>
                            ${president.leeftijd_bij_aantreden ? `<span class="chip">${president.leeftijd_bij_aantreden} jaar bij aantreden</span>` : ''}
                            ${president.jaren_in_functie ? `<span class="chip">${president.jaren_in_functie} jaar in functie</span>` : ''}
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding: 2rem;">
                ${president.biografie ? `
                    <div style="margin-bottom: 2rem;">
                        <div class="eyebrow" style="margin-bottom: .75rem;">Biografie</div>
                        <p class="prose-editorial" style="margin: 0; color: var(--color-ink); line-height: 1.7;">${escapeHtml(president.biografie)}</p>
                    </div>
                ` : ''}

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    ${president.geboorteplaats ? renderDetail('Geboorteplaats', president.geboorteplaats) : ''}
                    ${president.geboren ? renderDetail('Geboren', president.geboren_formatted || formatDate(president.geboren)) : ''}
                    ${president.overleden ? renderDetail('Overleden', president.overleden_formatted || formatDate(president.overleden)) : ''}
                    ${president.echtgenote ? renderDetail('Echtgenote', president.echtgenote) : ''}
                    ${president.vice_president ? renderDetail('Vice-president', president.vice_president) : ''}
                </div>

                ${prestaties.length ? renderList('Belangrijkste prestaties', prestaties) : ''}
                ${funFacts.length ? renderList('Interessante feiten', funFacts) : ''}

                ${president.familie_connecties ? `
                    <div style="margin-top: 1rem; padding: 1rem; border-left: 2px solid var(--color-hague); background: var(--color-paper-2);">
                        <div class="eyebrow" style="margin-bottom: .5rem;">Familie connecties</div>
                        <p style="margin: 0; color: var(--color-ink);">${escapeHtml(president.familie_connecties)}</p>
                    </div>
                ` : ''}
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function renderDetail(label, value) {
    return `
        <div style="border-left: 2px solid var(--color-hague); padding-left: .75rem;">
            <div class="eyebrow" style="margin-bottom: .25rem;">${escapeHtml(label)}</div>
            <div style="font-family: var(--font-display); color: var(--color-ink);">${escapeHtml(value)}</div>
        </div>
    `;
}

function renderList(label, items) {
    return `
        <div style="margin-bottom: 1.5rem;">
            <div class="eyebrow" style="margin-bottom: .75rem;">${escapeHtml(label)}</div>
            <ul style="margin: 0; padding: 0; list-style: none; display: grid; gap: .5rem;">
                ${items.map(i => `
                    <li style="display:flex; gap:.5rem; align-items:flex-start; padding:.5rem .75rem; border:1px solid var(--color-keyline); border-radius: .25rem;">
                        <span style="color: var(--color-hague); margin-top: .15rem; flex-shrink: 0;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </span>
                        <span style="color: var(--color-ink); font-size: .875rem; line-height: 1.5;">${escapeHtml(i)}</span>
                    </li>
                `).join('')}
            </ul>
        </div>
    `;
}

function formatDate(dateString) {
    try {
        return new Date(dateString).toLocaleDateString('nl-NL');
    } catch (e) {
        return dateString;
    }
}

function escapeHtml(str) {
    return String(str == null ? '' : str).replace(/[&<>"']/g, s => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[s]));
}

function closePresidentModal() {
    const modal = document.getElementById('presidentModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('presidentModal').addEventListener('click', function (e) {
    if (e.target === this) closePresidentModal();
});
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePresidentModal();
});
</script>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
