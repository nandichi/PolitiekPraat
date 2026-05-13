<?php
require_once dirname(__DIR__) . '/includes/error_bootstrap.php';
require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/StemwijzerController.php';

$stemwijzerController = new StemwijzerController();

try {
    $stemwijzerData = $stemwijzerController->getStemwijzerData();
    $totalQuestions = count($stemwijzerData['questions']);
} catch (Exception $e) {
    error_log('Stemwijzer data load error: ' . $e->getMessage());
    $stemwijzerData = ['questions' => [], 'parties' => [], 'partyLogos' => []];
    $totalQuestions = 0;
}

$pageTitle = 'Stemwijzer 2026 - Welke partij past bij jouw standpunten?';
$pageDescription = 'De Stemwijzer 2026: beantwoord stellingen over actuele politieke thema\'s en ontdek met welke Nederlandse partijen je het meest overeenkomt.';
$data = ['title' => $pageTitle, 'description' => $pageDescription];

$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => 'Stemwijzer 2026 Nederland',
    'description' => $pageDescription,
    'url' => URLROOT . '/stemwijzer',
    'applicationCategory' => 'EducationalApplication',
    'operatingSystem' => 'Web',
    'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'EUR'],
    'provider' => ['@type' => 'Organization', 'name' => 'PolitiekPraat', 'url' => URLROOT],
];

require_once 'views/templates/header.php';

$dataJson = json_encode($stemwijzerData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
if ($dataJson === false) {
    $dataJson = '{}';
}
?>

<script type="application/ld+json">
<?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>

<div x-data="stemwijzer()" x-init="init()" x-cloak>
    <template x-if="screen === 'welcome'">
        <div>
            <?= pp_render_component('section/page-hero', [
                'eyebrow' => 'Stemwijzer 2026',
                'title'   => 'Met welke partij heb jij de meeste overlap?',
                'lead'    => 'Beantwoord ' . (int) $totalQuestions . ' stellingen over actuele politieke onderwerpen - van klimaat tot zorg en immigratie. Aan het eind zie je per partij hoe groot de overeenkomst met jouw antwoorden is.',
            ]) ?>

            <section class="pp-container pp-container--xl mt-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="keyline-card p-6">
                        <div class="text-[color:var(--color-hague)] mb-3"><?= pp_icon('vote', 24) ?></div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2"><?= (int) $totalQuestions ?> stellingen</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Concrete politieke uitspraken - eens, neutraal of oneens.</p>
                    </div>
                    <div class="keyline-card p-6">
                        <div class="text-[color:var(--color-hague)] mb-3"><?= pp_icon('users', 24) ?></div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2"><?= count($stemwijzerData['parties'] ?? []) ?> partijen</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Alle grote Nederlandse partijen worden meegenomen.</p>
                    </div>
                    <div class="keyline-card p-6">
                        <div class="text-[color:var(--color-hague)] mb-3"><?= pp_icon('clock', 24) ?></div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2">Ongeveer 7 minuten</h3>
                        <p class="text-sm text-[color:var(--color-ink-muted)]">Neem de tijd om elke stelling rustig te lezen.</p>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <button @click="startQuiz()" class="btn btn--primary btn--lg" :disabled="!dataLoaded">
                        <?= pp_icon('arrow-right', 18) ?>
                        Start de Stemwijzer
                    </button>
                    <p class="text-xs text-[color:var(--color-ink-faint)]">Anoniem - geen data wordt aan jouw persoon gekoppeld.</p>
                </div>
            </section>

            <section class="pp-container pp-container--md mt-24 mb-24">
                <?= pp_render_component('section/section-header', [
                    'label' => 'Toelichting',
                    'title' => 'Zo werkt de Stemwijzer',
                ]) ?>
                <div class="prose-editorial">
                    <p>
                        De Stemwijzer is een verkorte versie van de officiele stemhulp, gebaseerd op publieke partijstandpunten en stemgedrag. Per stelling kun je kiezen voor <strong>eens</strong>, <strong>neutraal</strong> of <strong>oneens</strong>.
                    </p>
                    <p>
                        Elk antwoord wordt vergeleken met het standpunt van elke partij. Volledig overeenkomende antwoorden tellen 2 punten, een gedeeltelijk antwoord (bijv. jouw 'neutraal' versus partij 'eens') 1 punt.
                    </p>
                    <p>
                        Aan het eind krijg je een rangschikking met de procentuele overlap per partij. Het is een hulpmiddel - geen voorschrift.
                    </p>
                </div>
            </section>

            <section class="pp-container pp-container--md mb-24">
                <?= pp_render_component('section/section-header', [
                    'label' => 'Veelgestelde vragen',
                ]) ?>
                <div class="space-y-6">
                    <div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2">Hoe betrouwbaar zijn de resultaten?</h3>
                        <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                            De Stemwijzer is een redactionele momentopname. Programma's en standpunten veranderen; een partij kan in de Tweede Kamer anders stemmen dan in het programma. Gebruik dit als startpunt, niet als eindpunt.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-display text-lg text-[color:var(--color-ink)] mb-2">Worden mijn antwoorden bewaard?</h3>
                        <p class="text-[color:var(--color-ink-muted)] leading-relaxed">
                            Wij koppelen niets aan jouw persoon. Geanonimiseerde, geaggregeerde data kan worden gebruikt om de Stemwijzer te verbeteren.
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
                    <div class="text-xs text-[color:var(--color-ink-muted)]">Ik onderschrijf de stelling. <span class="font-mono">[1]</span></div>
                </button>
                <button @click="answerQuestion('neutraal')"
                        class="keyline-card p-5 text-left transition hover:border-[color:var(--color-ink-muted)] hover:bg-[color:var(--color-paper-2)]">
                    <div class="text-[color:var(--color-ink-muted)] mb-2"><?= pp_icon('minus', 20) ?></div>
                    <div class="font-display text-base text-[color:var(--color-ink)] mb-1">Neutraal</div>
                    <div class="text-xs text-[color:var(--color-ink-muted)]">Geen duidelijke mening. <span class="font-mono">[2]</span></div>
                </button>
                <button @click="answerQuestion('oneens')"
                        class="keyline-card p-5 text-left transition hover:border-[color:var(--color-terracotta)] hover:bg-[color:var(--color-paper-2)]">
                    <div style="color: var(--color-terracotta);" class="mb-2"><?= pp_icon('x', 20) ?></div>
                    <div class="font-display text-base text-[color:var(--color-ink)] mb-1">Oneens</div>
                    <div class="text-xs text-[color:var(--color-ink-muted)]">Ik onderschrijf de stelling niet. <span class="font-mono">[3]</span></div>
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
                'eyebrow' => 'Uitslag',
                'title'   => 'Dit zijn de partijen die het beste bij jouw antwoorden passen',
                'lead'    => 'De rangschikking is gebaseerd op de procentuele overlap tussen jouw antwoorden en de standpunten van elke partij.',
            ]) ?>

            <div class="space-y-4 mt-10">
                <template x-for="(result, index) in finalResults" :key="result.name">
                    <div class="keyline-card p-5 md:p-6">
                        <div class="flex items-center gap-4">
                            <div class="font-mono text-tabular text-2xl text-[color:var(--color-ink-faint)] w-10 text-center" x-text="String(index + 1).padStart(2, '0')"></div>
                            <template x-if="result.logo">
                                <img :src="result.logo" :alt="result.name" class="w-12 h-12 rounded-md object-contain bg-white border border-[color:var(--color-keyline)] p-1.5">
                            </template>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline justify-between mb-2">
                                    <a :href="`/partijen/${encodeURIComponent(result.name)}`" class="font-display text-lg text-[color:var(--color-ink)] hover:text-[color:var(--color-hague)]"
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
                    <a :href="`/partijen/${encodeURIComponent(finalResults[0]?.name || '')}`" class="btn btn--primary">
                        <?= pp_icon('arrow-right', 14) ?>
                        Top-1: <span x-text="finalResults[0]?.name || ''"></span>
                    </a>
                    <a href="<?= pp_e(pp_url('/politiek-kompas')) ?>" class="btn btn--secondary">
                        <?= pp_icon('scale', 14) ?>
                        Naar Politiek Kompas
                    </a>
                </div>
            </div>
        </section>
    </template>
</div>

<script>
window.STEMWIJZER_DATA = <?= $dataJson ?>;

function stemwijzer() {
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
            const data = window.STEMWIJZER_DATA;
            this.questions = data.questions || [];
            this.parties = data.parties || [];
            this.partyLogos = data.partyLogos || {};
            this.dataLoaded = this.questions.length > 0;

            document.addEventListener('keydown', (e) => {
                if (this.screen !== 'questions') return;
                if (e.key === '1') this.answerQuestion('eens');
                if (e.key === '2') this.answerQuestion('neutraal');
                if (e.key === '3') this.answerQuestion('oneens');
                if (e.key === 'ArrowLeft') this.previousQuestion();
                if (e.key === 'ArrowRight') this.skipQuestion();
            });
        },

        get totalSteps() { return this.questions.length; },

        currentQuestion() { return this.questions[this.currentStep] || null; },

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
