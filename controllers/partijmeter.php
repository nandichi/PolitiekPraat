<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';

$partijmeterController = new StemwijzerController();

try {
    $partijmeterData = $partijmeterController->getStemwijzerData();
    $totalQuestions = count($partijmeterData['questions']);
} catch (Exception $e) {
    error_log('Partijmeter data load error: ' . $e->getMessage());
    $partijmeterData = ['questions' => [], 'parties' => [], 'partyLogos' => []];
    $totalQuestions = 0;
}

$pageTitle = 'PartijMeter 2026 - Welke partij past bij jou?';
$pageDescription = 'Doe de PartijMeter en ontdek met enkele heldere stellingen welke politieke partijen het beste bij jouw standpunten passen.';
$data = ['title' => $pageTitle, 'description' => $pageDescription];

require_once 'views/templates/header.php';

$dataJson = json_encode($partijmeterData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
if ($dataJson === false) {
    $dataJson = '{}';
}
?>

<div x-data="partijmeter()" x-init="init()" x-cloak>
    <template x-if="screen === 'welcome'">
        <div>
            <?= pp_render_component('section/page-hero', [
                'eyebrow' => 'PartijMeter 2026',
                'title'   => 'Welke partij past bij jou?',
                'lead'    => 'Beantwoord ' . (int) $totalQuestions . ' stellingen over actuele politieke onderwerpen. Geen accounts, geen tracking - alleen jij en de stellingen. Aan het eind zie je welke partijen het dichtst bij jouw standpunten staan.',
            ]) ?>

            <section class="pp-container pp-container--xl mt-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="keyline-card p-6">
                        <div class="text-[color:var(--color-hague)] mb-3"><?= pp_icon('vote', 24) ?></div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2"><?= (int) $totalQuestions ?> stellingen</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Concrete uitspraken over politiek beleid - eens, neutraal of oneens.</p>
                    </div>
                    <div class="keyline-card p-6">
                        <div class="text-[color:var(--color-hague)] mb-3"><?= pp_icon('users', 24) ?></div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2"><?= count($partijmeterData['parties'] ?? []) ?> partijen</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Alle gevestigde partijen in de Tweede Kamer worden meegenomen.</p>
                    </div>
                    <div class="keyline-card p-6">
                        <div class="text-[color:var(--color-hague)] mb-3"><?= pp_icon('clock', 24) ?></div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2">Ongeveer 5 minuten</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Neem de tijd om elke stelling rustig te lezen voordat je antwoordt.</p>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <button @click="startQuiz()" class="btn btn--primary btn--lg" :disabled="!dataLoaded">
                        <?= pp_icon('arrow-right', 18) ?>
                        Start de PartijMeter
                    </button>
                    <p class="text-xs text-[color:var(--color-ink-faint)]">Anoniem - geen data wordt aan jouw persoon gekoppeld.</p>
                </div>
            </section>

            <section class="pp-container pp-container--md mt-24 mb-24">
                <?= pp_render_component('section/section-header', [
                    'label' => 'Veelgestelde vragen',
                    'title' => 'Hoe werkt de PartijMeter?',
                ]) ?>
                <div class="space-y-6">
                    <div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2">Hoe betrouwbaar zijn de resultaten?</h3>
                        <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                            De PartijMeter geeft een momentopname op basis van publieke partijstandpunten. Het is een handig hulpmiddel, geen voorschrift: een politieke keuze blijft persoonlijk.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2">Worden mijn antwoorden bewaard?</h3>
                        <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                            We bewaren geanonimiseerd geaggregeerde data om de tool te verbeteren. We koppelen niets aan jouw persoon - er is geen login nodig.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2">Kan ik mijn antwoorden aanpassen?</h3>
                        <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                            Tijdens de quiz kun je teruggaan naar de vorige stelling. Aan het eind kun je opnieuw starten.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </template>

    <template x-if="screen === 'questions'">
        <section class="pp-container pp-container--md py-10 md:py-16 min-h-[80vh]">
            <div class="mb-8">
                <div class="flex items-center justify-between text-xs uppercase tracking-[0.18em] text-[color:var(--color-ink-muted)] mb-3">
                    <span>Stelling <span x-text="currentStep + 1" class="font-mono"></span> / <span x-text="totalSteps" class="font-mono"></span></span>
                    <span class="font-mono"><span x-text="Math.round(((currentStep + 1) / totalSteps) * 100)"></span>%</span>
                </div>
                <div class="h-1 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden">
                    <div class="h-full bg-[color:var(--color-hague)] transition-[width] duration-300"
                         :style="`width: ${((currentStep + 1) / totalSteps) * 100}%`"></div>
                </div>
            </div>

            <div class="keyline-card p-8 md:p-12 mb-8">
                <div class="text-xs uppercase tracking-[0.18em] text-[color:var(--color-terracotta)] mb-3"
                     x-text="(currentQuestion()?.category || 'Stelling').toUpperCase()"></div>
                <h2 class="font-display text-display-lg md:text-display-xl leading-[1.1] text-[color:var(--color-ink)] mb-6"
                    x-text="currentQuestion()?.title || ''"></h2>
                <p class="font-display text-lg md:text-xl leading-snug text-[color:var(--color-ink-muted)]"
                   x-text="currentQuestion()?.text || ''"></p>
                <template x-if="currentQuestion()?.explanation">
                    <div class="mt-6 pt-6 border-t border-[color:var(--color-keyline)]">
                        <button @click="showExplanation = !showExplanation"
                                class="text-sm text-[color:var(--color-hague)] underline-offset-2 hover:underline inline-flex items-center gap-1.5">
                            <?= pp_icon('info', 14) ?>
                            <span x-text="showExplanation ? 'Verberg uitleg' : 'Meer context'"></span>
                        </button>
                        <p x-show="showExplanation"
                           x-transition
                           class="text-sm text-[color:var(--color-ink-muted)] mt-3 leading-relaxed"
                           x-text="currentQuestion()?.explanation || ''"></p>
                    </div>
                </template>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
                <button @click="answerQuestion('eens')"
                        class="keyline-card p-5 text-left transition hover:border-[color:var(--color-hague)] hover:bg-[color:var(--color-paper-2)]">
                    <div class="text-[color:var(--color-hague)] mb-2"><?= pp_icon('check-circle', 20) ?></div>
                    <div class="font-display text-base text-[color:var(--color-ink)] mb-1">Eens</div>
                    <div class="text-xs text-[color:var(--color-ink-muted)]">Ik onderschrijf de stelling.</div>
                </button>
                <button @click="answerQuestion('neutraal')"
                        class="keyline-card p-5 text-left transition hover:border-[color:var(--color-ink-muted)] hover:bg-[color:var(--color-paper-2)]">
                    <div class="text-[color:var(--color-ink-muted)] mb-2"><?= pp_icon('minus', 20) ?></div>
                    <div class="font-display text-base text-[color:var(--color-ink)] mb-1">Neutraal</div>
                    <div class="text-xs text-[color:var(--color-ink-muted)]">Geen duidelijke mening.</div>
                </button>
                <button @click="answerQuestion('oneens')"
                        class="keyline-card p-5 text-left transition hover:border-[color:var(--color-terracotta)] hover:bg-[color:var(--color-paper-2)]">
                    <div style="color: var(--color-terracotta);" class="mb-2"><?= pp_icon('x', 20) ?></div>
                    <div class="font-display text-base text-[color:var(--color-ink)] mb-1">Oneens</div>
                    <div class="text-xs text-[color:var(--color-ink-muted)]">Ik onderschrijf de stelling niet.</div>
                </button>
            </div>

            <div class="flex items-center justify-between">
                <button @click="previousQuestion()" :disabled="currentStep === 0"
                        class="btn btn--ghost btn--sm" :class="currentStep === 0 ? 'opacity-40 cursor-not-allowed' : ''">
                    <?= pp_icon('arrow-left', 14) ?>
                    Vorige
                </button>
                <button @click="skipQuestion()" class="btn btn--ghost btn--sm">
                    Sla over
                    <?= pp_icon('arrow-right', 14) ?>
                </button>
            </div>
        </section>
    </template>

    <template x-if="screen === 'results'">
        <section class="pp-container pp-container--md py-10 md:py-16 mb-24">
            <?= pp_render_component('section/page-hero', [
                'eyebrow' => 'Resultaat',
                'title'   => 'Dit zijn de partijen die het dichtst bij jouw standpunten staan',
                'lead'    => 'Op basis van jouw antwoorden hebben we de procentuele overlap met elke partij berekend. Bekijk de top vijf hieronder.',
            ]) ?>

            <div class="space-y-4 mt-10">
                <template x-for="(result, index) in finalResults.slice(0, 5)" :key="result.name">
                    <div class="keyline-card p-5 md:p-6">
                        <div class="flex items-center gap-4">
                            <div class="font-mono text-tabular text-2xl text-[color:var(--color-ink-faint)] w-10 text-center" x-text="String(index + 1).padStart(2, '0')"></div>
                            <template x-if="result.logo">
                                <img :src="result.logo" :alt="result.name" class="w-12 h-12 rounded-md object-contain bg-white border border-[color:var(--color-keyline)] p-1.5">
                            </template>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline justify-between mb-2">
                                    <a :href="`/partijen/${result.name}`" class="font-display text-lg text-[color:var(--color-ink)] hover:text-[color:var(--color-hague)]"
                                       x-text="result.name"></a>
                                    <span class="font-mono text-tabular text-lg text-[color:var(--color-ink)]">
                                        <span x-text="result.agreement"></span>%
                                    </span>
                                </div>
                                <div class="h-1.5 bg-[color:var(--color-paper-2)] rounded-full overflow-hidden">
                                    <div class="h-full bg-[color:var(--color-hague)] transition-[width] duration-700"
                                         :style="`width: ${result.agreement}%`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-12 flex flex-wrap items-center justify-between gap-4">
                <button @click="restartQuiz()" class="btn btn--ghost">
                    <?= pp_icon('arrow-left', 14) ?>
                    Opnieuw starten
                </button>
                <div class="flex flex-wrap gap-3">
                    <a :href="`/partijen/${finalResults[0]?.name || ''}`" class="btn btn--primary">
                        <?= pp_icon('arrow-right', 14) ?>
                        Bekijk top-1: <span x-text="finalResults[0]?.name || ''"></span>
                    </a>
                    <a href="<?= pp_e(pp_url('/politiek-kompas')) ?>" class="btn btn--secondary">
                        <?= pp_icon('scale', 14) ?>
                        Doe ook het Politiek Kompas
                    </a>
                </div>
            </div>
        </section>
    </template>
</div>

<script>
window.PARTIJMETER_DATA = <?= $dataJson ?>;

function partijmeter() {
    return {
        screen: 'welcome',
        questions: [],
        parties: [],
        partyLogos: {},
        answers: {},
        currentStep: 0,
        showExplanation: false,
        dataLoaded: false,
        finalResults: [],

        init() {
            const data = window.PARTIJMETER_DATA;
            this.questions = data.questions || [];
            this.parties = data.parties || [];
            this.partyLogos = data.partyLogos || {};
            this.dataLoaded = this.questions.length > 0;
        },

        get totalSteps() {
            return this.questions.length;
        },

        currentQuestion() {
            return this.questions[this.currentStep] || null;
        },

        startQuiz() {
            if (!this.dataLoaded) return;
            this.screen = 'questions';
            this.currentStep = 0;
            this.answers = {};
            this.showExplanation = false;
        },

        answerQuestion(answer) {
            this.answers[this.currentStep] = answer;
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                this.showExplanation = false;
            } else {
                this.calculateResults();
                this.screen = 'results';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        skipQuestion() {
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                this.showExplanation = false;
            } else {
                this.calculateResults();
                this.screen = 'results';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        previousQuestion() {
            if (this.currentStep > 0) {
                this.currentStep--;
                this.showExplanation = false;
            }
        },

        calculateResults() {
            if (!this.questions.length) return;
            const partyKeys = Object.keys(this.questions[0].positions || {});
            const scores = {};
            partyKeys.forEach(party => { scores[party] = { score: 0, total: 0 }; });

            Object.keys(this.answers).forEach(qIndex => {
                const q = this.questions[qIndex];
                const ua = this.answers[qIndex];
                if (!q || !q.positions) return;
                partyKeys.forEach(party => {
                    const pa = q.positions[party];
                    if (ua === pa) {
                        scores[party].score += 2;
                    } else if (
                        (ua === 'neutraal' && (pa === 'eens' || pa === 'oneens')) ||
                        ((ua === 'eens' || ua === 'oneens') && pa === 'neutraal')
                    ) {
                        scores[party].score += 1;
                    }
                    scores[party].total += 2;
                });
            });

            this.finalResults = partyKeys
                .map(party => ({
                    name: party,
                    agreement: scores[party].total > 0 ? Math.round((scores[party].score / scores[party].total) * 100) : 0,
                    logo: this.partyLogos[party] || null,
                }))
                .sort((a, b) => b.agreement - a.agreement);
        },

        restartQuiz() {
            this.screen = 'welcome';
            this.currentStep = 0;
            this.answers = {};
            this.finalResults = [];
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
    };
}
</script>

<?php require_once 'views/templates/footer.php'; ?>
