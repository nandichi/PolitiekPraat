<?php require_once BASE_PATH . '/views/templates/header.php'; ?>

<?php
$totaal = $presidentenStatistieken->totaal_presidenten ?? 0;
$vvdAantal = $presidentenStatistieken->vvd_presidenten ?? 0;
$jongste = $presidentenStatistieken->jongste_leeftijd ?? null;
$gemTermijn = isset($presidentenStatistieken->gemiddelde_termijn_dagen)
    ? round(((int) $presidentenStatistieken->gemiddelde_termijn_dagen) / 365, 1)
    : null;

$heroMeta = [
    ['icon' => 'users',    'text' => $totaal . ' ministers-presidenten'],
    ['icon' => 'landmark', 'text' => 'Sinds 1848'],
];
if ($gemTermijn !== null) {
    $heroMeta[] = ['icon' => 'clock', 'text' => 'Gemiddeld ' . $gemTermijn . ' jaar in functie'];
}
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Nederland',
    'title'   => 'Ministers-presidenten',
    'lead'    => 'Een chronologisch overzicht van alle ministers-presidenten van Nederland sinds Thorbecke. Klik op een persoon voor de uitgebreide biografie.',
    'meta'    => $heroMeta,
]) ?>

<section class="pp-container pp-container--xl py-10 md:py-14 space-y-12">

    <?php if (isset($presidentenStatistieken)): ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">Totaal</div>
                <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= (int) $totaal ?></div>
                <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Ministers-presidenten sinds 1848</p>
            </div>
            <div class="keyline-card p-5">
                <div class="eyebrow mb-2">VVD-premiers</div>
                <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= (int) $vvdAantal ?></div>
                <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Liberale traditie</p>
            </div>
            <?php if ($jongste !== null): ?>
                <div class="keyline-card p-5">
                    <div class="eyebrow mb-2">Jongste</div>
                    <div class="font-display text-display-xl text-[color:var(--color-ink)] font-mono text-tabular leading-none"><?= (int) $jongste ?></div>
                    <p class="text-xs text-[color:var(--color-ink-muted)] mt-2">Jaar bij aantreden</p>
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

    <?php if (isset($presidentenPerEra) && !empty($presidentenPerEra)): ?>
        <?php foreach ($presidentenPerEra as $era => $presidenten): ?>
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <span class="eyebrow"><?= pp_e((string) $era) ?></span>
                    <span class="flex-1 h-px bg-[color:var(--color-keyline)]"></span>
                    <span class="text-sm text-[color:var(--color-ink-muted)] font-mono text-tabular">
                        <?= count($presidenten) ?> <?= count($presidenten) === 1 ? 'premier' : 'premiers' ?>
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($presidenten as $president):
                        $foto = $president->foto_url ?? '';
                        $naam = $president->naam ?? 'Onbekend';
                        $partij = $president->partij ?? '';
                        $nummer = $president->minister_president_nummer ?? null;
                        $start = $president->periode_start ?? null;
                        $eind  = $president->periode_eind ?? null;
                        $isHuidig = !empty($president->is_huidig);
                        $dagenInFunctie = $president->dagen_in_functie ?? null;
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
                                    <?php if ($isHuidig): ?>
                                        <span class="badge badge--olive">Huidig</span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="font-display text-display-md text-[color:var(--color-ink)] mb-1 leading-tight group-hover:text-[color:var(--color-hague)] transition-colors">
                                    <?= pp_e($naam) ?>
                                </h3>
                                <p class="text-sm text-[color:var(--color-ink-muted)] mb-3"><?= pp_e($partij) ?></p>
                                <?php if ($start): ?>
                                    <p class="text-xs text-[color:var(--color-ink-muted)] font-mono text-tabular">
                                        <?= date('Y', strtotime($start)) ?>
                                        &mdash;
                                        <?= $isHuidig || !$eind ? 'heden' : date('Y', strtotime($eind)) ?>
                                        <?php if ($dagenInFunctie): ?>
                                            <span class="text-[color:var(--color-ink-faint)]">&middot; <?= round((int) $dagenInFunctie / 365, 1) ?> jaar</span>
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
            <p class="text-sm text-[color:var(--color-ink-muted)]">Er zijn momenteel geen ministers-presidenten gegevens beschikbaar.</p>
        </div>
    <?php endif; ?>

    <div class="text-center">
        <a href="<?= URLROOT ?>/nederlandse-verkiezingen" class="btn btn--ghost">
            <?= pp_icon('arrow-left', 14) ?>
            Terug naar Nederlandse verkiezingen
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

    const prestaties = president.prestaties_parsed || [];
    const funFacts = president.fun_facts_parsed || [];
    const kabinetten = president.kabinetten_parsed || [];
    const coalitiepartners = president.coalitiepartners_parsed || [];
    const citaten = president.citaten_parsed || [];

    const startYear = president.periode_start ? new Date(president.periode_start).getFullYear() : '';
    const endYear = president.periode_eind ? new Date(president.periode_eind).getFullYear() : 'heden';

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
                            ? `<div class="photo-frame"><img src="${president.foto_url}" alt="${president.naam}" style="aspect-ratio: 4/5; object-fit: cover;"></div>`
                            : `<div class="photo-frame" style="aspect-ratio: 4/5; display:flex; align-items:center; justify-content:center; background:var(--color-paper-2); color:var(--color-ink-faint);">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                              </div>`
                        }
                    </div>
                    <div style="flex:1; min-width: 220px;">
                        <div class="eyebrow" style="margin-bottom: .5rem;">${president.minister_president_nummer ? '#' + president.minister_president_nummer + ' minister-president' : 'Minister-president'}</div>
                        <h2 class="font-display" style="font-size: var(--text-display-xl); color: var(--color-ink); margin: 0 0 .25rem; line-height: 1.05;">${escapeHtml(president.naam || '')}</h2>
                        ${president.bijnaam ? `<p style="font-style: italic; color: var(--color-ink-muted); margin: 0 0 .75rem;">"${escapeHtml(president.bijnaam)}"</p>` : ''}
                        <div style="display:flex; flex-wrap: wrap; gap: .5rem; margin-top: .5rem;">
                            <span class="chip">${escapeHtml(president.partij || '')}</span>
                            <span class="chip">${startYear} &mdash; ${endYear}</span>
                            ${president.dagen_in_functie ? `<span class="chip">${Math.round(president.dagen_in_functie / 365 * 10) / 10} jaar</span>` : ''}
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
                    ${president.leeftijd_bij_aantreden ? renderDetail('Leeftijd bij aantreden', president.leeftijd_bij_aantreden + ' jaar') : ''}
                    ${president.onderwijs ? renderDetail('Onderwijs', president.onderwijs) : ''}
                    ${president.echtgenoot_echtgenote ? renderDetail('Echtgenoot/echtgenote', president.echtgenoot_echtgenote) : ''}
                </div>

                ${kabinetten.length ? renderList('Kabinetten', kabinetten) : ''}
                ${coalitiepartners.length ? renderList('Coalitiepartners', coalitiepartners) : ''}
                ${prestaties.length ? renderList('Belangrijkste prestaties', prestaties) : ''}
                ${funFacts.length ? renderList('Interessante weetjes', funFacts) : ''}

                ${citaten.length ? `
                    <div>
                        <div class="eyebrow" style="margin-bottom: .75rem;">Bekende uitspraken</div>
                        ${citaten.map(c => `
                            <blockquote class="pull-quote" style="margin: 0 0 1rem; padding: .75rem 1rem; border-left: 2px solid var(--color-hague); color: var(--color-ink);">
                                "${escapeHtml(c)}"
                                <footer style="margin-top: .5rem; font-size: .875rem; color: var(--color-ink-muted);">&mdash; ${escapeHtml(president.naam || '')}</footer>
                            </blockquote>
                        `).join('')}
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
            <div class="eyebrow" style="margin-bottom: .25rem;">${label}</div>
            <div style="font-family: var(--font-display); color: var(--color-ink);">${escapeHtml(value)}</div>
        </div>
    `;
}

function renderList(label, items) {
    return `
        <div style="margin-bottom: 1.5rem;">
            <div class="eyebrow" style="margin-bottom: .75rem;">${label}</div>
            <div style="display:flex; flex-wrap: wrap; gap: .5rem;">
                ${items.map(i => `<span class="chip">${escapeHtml(i)}</span>`).join('')}
            </div>
        </div>
    `;
}

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, s => ({
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
