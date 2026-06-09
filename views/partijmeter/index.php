<?php require_once 'views/templates/header.php'; ?>
<?php
/**
 * PartijMeter 2026 - welkom, quiz en resultaten in één Alpine-app.
 * Data komt uit $payload (PartijMeterController::getData). Een eventueel gedeeld
 * resultaat komt binnen via $sharedResult (zie controllers/partijmeter-resultaat.php).
 */
$assetize = static function (?string $path): ?string {
    if (!$path) {
        return null;
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return rtrim(URLROOT, '/') . '/' . ltrim($path, '/');
};

$jsPayload = $payload;
$jsPayload['parties'] = array_map(static function (array $p) use ($assetize): array {
    $p['logo'] = $assetize($p['logo'] ?? null);
    $p['leaderPhoto'] = $assetize($p['leaderPhoto'] ?? null);
    return $p;
}, $payload['parties']);

$themes = [];
foreach ($payload['questions'] as $q) {
    if (!empty($q['theme']) && !in_array($q['theme'], $themes, true)) {
        $themes[] = $q['theme'];
    }
}
?>

<script>
    window.PARTIJMETER_DATA = <?= json_encode($jsPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    <?php if (!empty($sharedResult)): ?>
    window.PARTIJMETER_SHARE = <?= json_encode($sharedResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    <?php endif; ?>
</script>

<main class="pm" x-data="partijMeter()" x-init="init()" x-cloak>

    <!-- ================================================================ WELKOM -->
    <section x-show="screen === 'welcome'" x-transition.opacity class="pm-welcome">
        <div class="pp-container pp-container--xl">

            <div class="pm-hero">
                <span class="pm-eyebrow"><?= pp_icon('vote', 15) ?> Stemhulp &middot; Tweede Kamer 2026</span>
                <h1 class="pm-hero__title">PartijMeter</h1>
                <p class="pm-hero__lead">
                    Ontdek met <strong x-text="meta.totalQuestions"></strong> actuele stellingen welke van de
                    <strong x-text="meta.totalParties"></strong> partijen in de Tweede Kamer het beste bij jouw
                    standpunten past. Gratis, anoniem en in een paar minuten.
                </p>

                <div class="pm-hero__actions">
                    <button type="button" class="btn btn--primary pm-btn-lg" @click="startQuiz()" x-show="!hasSaved">
                        Start de PartijMeter <?= pp_icon('arrow-right', 18) ?>
                    </button>

                    <template x-if="hasSaved">
                        <div class="pm-resume">
                            <button type="button" class="btn btn--primary pm-btn-lg" @click="resumeQuiz()">
                                Verder waar je was <?= pp_icon('arrow-right', 18) ?>
                            </button>
                            <button type="button" class="btn btn--ghost" @click="restart()">
                                Opnieuw beginnen
                            </button>
                            <p class="pm-resume__note">
                                <?= pp_icon('refresh-cw', 13) ?>
                                Je hebt <strong x-text="answeredCount"></strong> van <strong x-text="totalSteps"></strong>
                                stellingen ingevuld.
                            </p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pm-stats">
                <div class="pm-stat">
                    <span class="pm-stat__icon"><?= pp_icon('list-checks', 20) ?></span>
                    <span class="pm-stat__num" x-text="meta.totalQuestions"></span>
                    <span class="pm-stat__label">stellingen</span>
                </div>
                <div class="pm-stat">
                    <span class="pm-stat__icon"><?= pp_icon('users', 20) ?></span>
                    <span class="pm-stat__num" x-text="meta.totalParties"></span>
                    <span class="pm-stat__label">partijen</span>
                </div>
                <div class="pm-stat">
                    <span class="pm-stat__icon"><?= pp_icon('clock', 20) ?></span>
                    <span class="pm-stat__num">4-6</span>
                    <span class="pm-stat__label">minuten</span>
                </div>
                <div class="pm-stat">
                    <span class="pm-stat__icon"><?= pp_icon('shield', 20) ?></span>
                    <span class="pm-stat__num">100%</span>
                    <span class="pm-stat__label">anoniem</span>
                </div>
            </div>

            <div class="pm-how">
                <header class="section-header">
                    <div>
                        <span class="section-header__label">Zo werkt het</span>
                        <h2 class="section-header__title">Drie stappen naar jouw match</h2>
                    </div>
                </header>
                <div class="pm-how__grid">
                    <div class="keyline-card pm-how__step">
                        <span class="pm-how__num">1</span>
                        <h3>Geef je mening</h3>
                        <p>Reageer per stelling met eens, neutraal of oneens. Vind je iets extra belangrijk? Laat het dubbel meetellen.</p>
                    </div>
                    <div class="keyline-card pm-how__step">
                        <span class="pm-how__num">2</span>
                        <h3>Wij rekenen mee</h3>
                        <p>We vergelijken je antwoorden met de standpunten van alle partijen volgens een transparante scoremethode.</p>
                    </div>
                    <div class="keyline-card pm-how__step">
                        <span class="pm-how__num">3</span>
                        <h3>Zie je resultaat</h3>
                        <p>Een ranglijst, vergelijkingsmatrix, je positie op het politiek kompas en zelfs een coalitie die bij je past.</p>
                    </div>
                </div>
            </div>

            <div class="pm-themes">
                <span class="section-header__label">Onderwerpen</span>
                <div class="pm-themes__chips">
                    <template x-for="theme in themeList()" :key="theme">
                        <span class="pm-chip" x-text="theme"></span>
                    </template>
                </div>
            </div>

            <p class="pm-disclaimer">
                <?= pp_icon('info', 14) ?>
                De standpunten zijn een redactionele momentopname op basis van verkiezingsprogramma's en stemgedrag
                (<span x-text="meta.peildatum"></span>). Je antwoorden worden anoniem verwerkt.
            </p>

        </div>
    </section>

    <!-- ================================================================== QUIZ -->
    <section x-show="screen === 'quiz'" x-transition.opacity class="pm-quiz">

        <div class="pm-progress" :style="'--pm-progress:' + progressPct + '%'">
            <div class="pp-container pp-container--xl pm-progress__inner">
                <div class="pm-progress__meta">
                    <span class="pm-progress__theme" x-text="currentQuestion() ? currentQuestion().theme : ''"></span>
                    <span class="pm-progress__count">
                        Stelling <strong x-text="currentStep + 1"></strong> / <span x-text="totalSteps"></span>
                    </span>
                </div>
                <div class="pm-progress__track"><div class="pm-progress__bar"></div></div>
            </div>
        </div>

        <div class="pp-container pp-container--narrow">
            <template x-if="currentQuestion()">
                <div class="pm-card" :key="currentStep">
                    <div class="pm-card__theme">
                        <?= pp_icon('layers', 14) ?>
                        <span x-text="currentQuestion().theme"></span>
                    </div>

                    <h2 class="pm-card__statement" x-text="currentQuestion().title"></h2>

                    <div class="pm-card__context" x-show="currentQuestion().explanation">
                        <button type="button" class="pm-context__toggle" @click="showExplanation = !showExplanation">
                            <?= pp_icon('info', 15) ?>
                            <span x-text="showExplanation ? 'Verberg toelichting' : 'Wat betekent dit?'"></span>
                        </button>
                        <p class="pm-context__body" x-show="showExplanation" x-transition x-text="currentQuestion().explanation"></p>
                    </div>

                    <div class="pm-answers">
                        <button type="button" class="pm-answer pm-answer--eens"
                                :class="answerFor(currentStep) === 'eens' && 'is-active'"
                                @click="answer('eens')">
                            <span class="pm-answer__icon"><?= pp_icon('check-circle', 22) ?></span>
                            <span class="pm-answer__label">Eens</span>
                            <span class="pm-answer__key">1</span>
                        </button>
                        <button type="button" class="pm-answer pm-answer--neutraal"
                                :class="answerFor(currentStep) === 'neutraal' && 'is-active'"
                                @click="answer('neutraal')">
                            <span class="pm-answer__icon"><?= pp_icon('minus', 22) ?></span>
                            <span class="pm-answer__label">Neutraal</span>
                            <span class="pm-answer__key">2</span>
                        </button>
                        <button type="button" class="pm-answer pm-answer--oneens"
                                :class="answerFor(currentStep) === 'oneens' && 'is-active'"
                                @click="answer('oneens')">
                            <span class="pm-answer__icon"><?= pp_icon('x', 22) ?></span>
                            <span class="pm-answer__label">Oneens</span>
                            <span class="pm-answer__key">3</span>
                        </button>
                    </div>

                    <button type="button" class="pm-important" :class="isImportant(currentStep) && 'is-active'" @click="toggleImportant()">
                        <?= pp_icon('star', 16) ?>
                        <span x-text="isImportant(currentStep) ? 'Telt dubbel mee' : 'Dit onderwerp vind ik belangrijk'"></span>
                    </button>

                    <div class="pm-nav">
                        <button type="button" class="btn btn--ghost" @click="prev()" :disabled="currentStep === 0">
                            <?= pp_icon('arrow-left', 16) ?> Vorige
                        </button>
                        <button type="button" class="pm-skip" @click="skip()">Overslaan</button>
                        <button type="button" class="btn btn--secondary" @click="finish()" x-show="answeredCount > 0">
                            Bekijk resultaat <?= pp_icon('bar-chart-3', 16) ?>
                        </button>
                    </div>

                    <p class="pm-hint">
                        <?= pp_icon('info', 12) ?>
                        Sneltoetsen: <kbd>1</kbd>/<kbd>2</kbd>/<kbd>3</kbd> antwoorden, <kbd>&larr;</kbd>/<kbd>&rarr;</kbd> navigeren, <kbd>B</kbd> belangrijk.
                    </p>
                </div>
            </template>
        </div>
    </section>

    <!-- =============================================================== RESULTAAT -->
    <section x-show="screen === 'results'" x-transition.opacity class="pm-results">
        <div class="pp-container pp-container--xl">

            <div class="pm-results__head">
                <span class="pm-eyebrow"><?= pp_icon('sparkles', 15) ?> Jouw resultaat</span>
                <h1 class="pm-results__title">Dit past bij jou</h1>
                <p class="pm-results__sub" x-show="!isReliable">
                    <?= pp_icon('info', 14) ?>
                    Je beantwoordde <strong x-text="answeredCount"></strong> van <span x-text="totalSteps"></span> stellingen.
                    Beantwoord er meer voor een betrouwbaarder beeld.
                </p>
            </div>

            <!-- Top match hero -->
            <template x-if="finalResults.length">
                <div class="pm-top" :style="'--pm-accent:' + finalResults[0].color">
                    <div class="pm-top__media">
                        <img x-show="finalResults[0].leaderPhoto" :src="finalResults[0].leaderPhoto" :alt="finalResults[0].leader" class="pm-top__photo" loading="lazy">
                        <img x-show="finalResults[0].logo" :src="finalResults[0].logo" :alt="finalResults[0].name" class="pm-top__logo" loading="lazy">
                    </div>
                    <div class="pm-top__body">
                        <span class="pm-top__rank"><?= pp_icon('award', 14) ?> Beste match</span>
                        <h2 class="pm-top__name" x-text="finalResults[0].name"></h2>
                        <p class="pm-top__leader" x-show="finalResults[0].leader" x-text="finalResults[0].leader"></p>
                        <div class="pm-top__scoreline">
                            <span class="pm-top__score"><span x-text="finalResults[0].display"></span>%</span>
                            <span class="pm-top__match">overeenkomst</span>
                        </div>
                    </div>
                    <div class="pm-top__actions">
                        <button type="button" class="btn btn--primary" @click="share()">
                            <?= pp_icon('share-2', 16) ?>
                            <span x-text="copied ? 'Link gekopieerd' : 'Deel resultaat'"></span>
                        </button>
                        <button type="button" class="btn btn--ghost" @click="restart()">
                            <?= pp_icon('refresh-cw', 16) ?> Opnieuw
                        </button>
                    </div>
                </div>
            </template>

            <!-- Tabs -->
            <div class="pm-tabs" role="tablist">
                <button class="pm-tab" :class="activeTab === 'ranking' && 'is-active'" @click="activeTab = 'ranking'"><?= pp_icon('bar-chart-3', 15) ?> Ranglijst</button>
                <button class="pm-tab" :class="activeTab === 'matrix' && 'is-active'" @click="activeTab = 'matrix'"><?= pp_icon('table', 15) ?> Vergelijking</button>
                <button class="pm-tab" :class="activeTab === 'themes' && 'is-active'" @click="activeTab = 'themes'"><?= pp_icon('layers', 15) ?> Thema's</button>
                <button class="pm-tab" :class="activeTab === 'compass' && 'is-active'" @click="activeTab = 'compass'"><?= pp_icon('compass', 15) ?> Kompas</button>
                <button class="pm-tab" :class="activeTab === 'coalition' && 'is-active'" @click="activeTab = 'coalition'"><?= pp_icon('landmark', 15) ?> Coalitie</button>
                <button class="pm-tab" :class="activeTab === 'deviations' && 'is-active'" @click="activeTab = 'deviations'"><?= pp_icon('git-compare', 15) ?> Afwijkingen</button>
            </div>

            <!-- RANGLIJST -->
            <div class="pm-panel" x-show="activeTab === 'ranking'" x-transition.opacity>
                <template x-for="(r, i) in finalResults" :key="r.key">
                    <div class="pm-rank" :style="'--pm-accent:' + r.color">
                        <span class="pm-rank__pos" x-text="i + 1"></span>
                        <span class="pm-rank__logo">
                            <img x-show="r.logo" :src="r.logo" :alt="r.name" loading="lazy">
                        </span>
                        <div class="pm-rank__main">
                            <div class="pm-rank__top">
                                <span class="pm-rank__name" x-text="r.name"></span>
                                <span class="pm-rank__pct"><span x-text="r.display"></span>%</span>
                            </div>
                            <div class="pm-rank__track"><div class="pm-rank__bar" :style="'width:' + r.display + '%'"></div></div>
                            <div class="pm-rank__meta">
                                <span class="pm-tag pm-tag--match"><?= pp_icon('check', 12) ?> <span x-text="r.matched"></span> eens</span>
                                <span class="pm-tag pm-tag--partial"><?= pp_icon('minus', 12) ?> <span x-text="r.partial"></span> deels</span>
                                <span class="pm-tag pm-tag--oppose"><?= pp_icon('x', 12) ?> <span x-text="r.opposed"></span> oneens</span>
                                <span class="pm-rank__seats"><span x-text="r.seats"></span> zetels</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- VERGELIJKINGSMATRIX -->
            <div class="pm-panel" x-show="activeTab === 'matrix'" x-transition.opacity>
                <p class="pm-panel__intro">Per stelling zie je jouw antwoord en het standpunt van elke partij. Klik een stelling open voor de toelichting en bron.</p>
                <template x-for="(q, i) in questions" :key="q.id">
                    <div class="pm-matrix" :class="expanded === i && 'is-open'">
                        <button type="button" class="pm-matrix__head" @click="toggleMatrix(i)">
                            <div class="pm-matrix__q">
                                <span class="pm-matrix__theme" x-text="q.theme"></span>
                                <span class="pm-matrix__title" x-text="q.title"></span>
                            </div>
                            <div class="pm-matrix__right">
                                <span class="pm-matrix__you" :class="'pm-you--' + (answerFor(i) || 'none')"
                                      x-text="answerFor(i) ? positionLabel(answerFor(i)) : 'Overgeslagen'"></span>
                                <span class="pm-matrix__chev" :class="expanded === i && 'is-open'"><?= pp_icon('chevron-down', 18) ?></span>
                            </div>
                        </button>
                        <div class="pm-matrix__dots">
                            <template x-for="p in parties" :key="p.key">
                                <span class="pm-dot" :class="'pm-dot--' + posTone(i, p.key)" :title="p.name"></span>
                            </template>
                        </div>
                        <template x-if="expanded === i">
                            <div class="pm-matrix__detail" x-transition>
                                <template x-for="p in parties" :key="p.key">
                                    <div class="pm-matrix__row" x-show="q.positions[p.key]">
                                        <div class="pm-matrix__party">
                                            <span class="pm-matrix__plogo"><img x-show="p.logo" :src="p.logo" :alt="p.name" loading="lazy"></span>
                                            <span x-text="p.name"></span>
                                        </div>
                                        <span class="pm-matrix__pos" :class="q.positions[p.key] && ('pm-pos--' + q.positions[p.key].p)"
                                              x-text="q.positions[p.key] ? positionLabel(q.positions[p.key].p) : ''"></span>
                                        <p class="pm-matrix__exp" x-show="q.positions[p.key] && q.positions[p.key].e" x-text="q.positions[p.key] ? q.positions[p.key].e : ''"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            <!-- THEMA'S -->
            <div class="pm-panel" x-show="activeTab === 'themes'" x-transition.opacity>
                <p class="pm-panel__intro">Op welk onderwerp sluit welke partij het beste bij je aan.</p>
                <div class="pm-themegrid">
                    <template x-for="t in themeBreakdown" :key="t.theme">
                        <div class="keyline-card pm-themecard" x-show="t.top">
                            <span class="pm-themecard__name" x-text="t.theme"></span>
                            <div class="pm-themecard__top" :style="'--pm-accent:' + (t.top ? t.top.color : '#666')">
                                <span class="pm-themecard__logo"><img x-show="t.top && t.top.logo" :src="t.top ? t.top.logo : ''" :alt="t.top ? t.top.name : ''" loading="lazy"></span>
                                <span class="pm-themecard__party" x-text="t.top ? t.top.name : ''"></span>
                                <span class="pm-themecard__pct" x-text="(t.top ? t.top.pct : 0) + '%'"></span>
                            </div>
                            <span class="pm-themecard__count"><span x-text="t.count"></span> stellingen</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- KOMPAS -->
            <div class="pm-panel" x-show="activeTab === 'compass'" x-transition.opacity>
                <p class="pm-panel__intro">Jouw positie tussen de partijen op twee assen: economisch (links&ndash;rechts) en cultureel (progressief&ndash;conservatief).</p>
                <div class="pm-compass">
                    <svg viewBox="0 0 100 100" class="pm-compass__svg" preserveAspectRatio="xMidYMid meet" aria-hidden="true">
                        <rect x="0" y="0" width="50" height="50" class="pm-q pm-q--gl"></rect>
                        <rect x="50" y="0" width="50" height="50" class="pm-q pm-q--gr"></rect>
                        <rect x="0" y="50" width="50" height="50" class="pm-q pm-q--rl"></rect>
                        <rect x="50" y="50" width="50" height="50" class="pm-q pm-q--rr"></rect>
                        <line x1="50" y1="0" x2="50" y2="100" class="pm-axis"></line>
                        <line x1="0" y1="50" x2="100" y2="50" class="pm-axis"></line>
                        <template x-for="p in parties" :key="'c' + p.key">
                            <g>
                                <circle :cx="cx(p.compass.x)" :cy="cy(p.compass.y)" r="2.4" :fill="p.color" class="pm-pt"></circle>
                            </g>
                        </template>
                        <circle :cx="cx(userCompass.x)" :cy="cy(userCompass.y)" r="4.6" class="pm-pt-user-ring"></circle>
                        <circle :cx="cx(userCompass.x)" :cy="cy(userCompass.y)" r="2.6" class="pm-pt-user"></circle>
                    </svg>
                    <span class="pm-compass__lbl pm-compass__lbl--top">Conservatief</span>
                    <span class="pm-compass__lbl pm-compass__lbl--bottom">Progressief</span>
                    <span class="pm-compass__lbl pm-compass__lbl--left">Links</span>
                    <span class="pm-compass__lbl pm-compass__lbl--right">Rechts</span>
                </div>
                <div class="pm-compass__legend">
                    <span class="pm-legend__you"><span class="pm-legend__dot pm-legend__dot--you"></span> Jij</span>
                    <template x-for="p in parties" :key="'l' + p.key">
                        <span class="pm-legend__item"><span class="pm-legend__dot" :style="'background:' + p.color"></span> <span x-text="p.key"></span></span>
                    </template>
                </div>
            </div>

            <!-- COALITIE -->
            <div class="pm-panel" x-show="activeTab === 'coalition'" x-transition.opacity>
                <p class="pm-panel__intro">De partijen die het dichtst bij jou staan en samen een meerderheid (76+ zetels) vormen.</p>
                <template x-if="coalition">
                    <div class="pm-coalition">
                        <div class="pm-coalition__summary">
                            <div class="pm-coalition__stat">
                                <span class="pm-coalition__num" x-text="coalition.seats"></span>
                                <span class="pm-coalition__lbl">zetels</span>
                            </div>
                            <div class="pm-coalition__stat">
                                <span class="pm-coalition__num" x-text="coalition.avg + '%'"></span>
                                <span class="pm-coalition__lbl">gem. match</span>
                            </div>
                            <div class="pm-coalition__stat">
                                <span class="pm-coalition__num" x-text="coalition.members.length"></span>
                                <span class="pm-coalition__lbl">partijen</span>
                            </div>
                            <span class="pm-coalition__badge" :class="coalition.majority ? 'is-ok' : 'is-no'">
                                <span x-text="coalition.majority ? 'Meerderheid' : 'Geen meerderheid'"></span>
                            </span>
                        </div>
                        <div class="pm-coalition__bar">
                            <template x-for="m in coalition.members" :key="'cb' + m.key">
                                <span class="pm-coalition__seg" :style="'flex:' + m.seats + ';background:' + m.color" :title="m.name + ' (' + m.seats + ')'"></span>
                            </template>
                        </div>
                        <div class="pm-coalition__members">
                            <template x-for="m in coalition.members" :key="'cm' + m.key">
                                <div class="pm-coalition__chip" :style="'--pm-accent:' + m.color">
                                    <span class="pm-coalition__clogo"><img x-show="m.logo" :src="m.logo" :alt="m.name" loading="lazy"></span>
                                    <span class="pm-coalition__cname" x-text="m.name"></span>
                                    <span class="pm-coalition__cseat" x-text="m.seats"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- AFWIJKINGEN -->
            <div class="pm-panel" x-show="activeTab === 'deviations'" x-transition.opacity>
                <template x-if="deviations.party">
                    <p class="pm-panel__intro">
                        Stellingen waarop je afwijkt van je beste match
                        <strong x-text="deviations.party.name"></strong>.
                    </p>
                </template>
                <p class="pm-empty" x-show="deviations.items.length === 0">
                    <?= pp_icon('check-circle', 18) ?> Je bent het op alle beantwoorde stellingen eens met je beste match.
                </p>
                <template x-for="(d, i) in deviations.items" :key="'d' + i">
                    <div class="keyline-card pm-dev">
                        <span class="pm-dev__theme" x-text="d.theme"></span>
                        <p class="pm-dev__title" x-text="d.title"></p>
                        <div class="pm-dev__cmp">
                            <span class="pm-dev__you" :class="'pm-pos--' + d.user">Jij: <span x-text="positionLabel(d.user)"></span></span>
                            <span class="pm-dev__party" :class="'pm-pos--' + d.party"><span x-text="deviations.party ? deviations.party.name : ''"></span>: <span x-text="positionLabel(d.party)"></span></span>
                        </div>
                        <p class="pm-dev__exp" x-show="d.explanation" x-text="d.explanation"></p>
                    </div>
                </template>
            </div>

            <p class="pm-disclaimer pm-disclaimer--results">
                <?= pp_icon('info', 14) ?>
                Standpunten zijn een redactionele inschatting (<span x-text="meta.peildatum"></span>) op basis van
                verkiezingsprogramma's, StemWijzer/Kieskompas en stemgedrag. Bekijk de toelichting per partij in de vergelijking.
            </p>

        </div>
    </section>

</main>

<?php require_once 'views/templates/footer.php'; ?>
