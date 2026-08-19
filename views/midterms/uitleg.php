<?php require_once 'views/templates/header.php'; ?>
<?php
$eyebrow = 'Midterms 2026 · Uitleg';
$title = 'De midterms begrijpen in tien minuten.';
$lead = 'Geen ingewikkeld Amerikaans jargon. Dit is wat er wordt gekozen, hoe de meerderheden werken en waarom een paar races zoveel nationale macht kunnen bepalen.';
$meta = [
    ['icon' => 'clock', 'text' => '10 minuten lezen'],
    ['icon' => 'landmark', 'text' => 'Huis · Senaat · staten'],
];
require __DIR__ . '/partials/page-intro.php';

$chapters = [
    ['id' => 'wat', 'nr' => '01', 'title' => 'Wat zijn de midterms?', 'text' => 'De midterms zijn de landelijke verkiezingen halverwege een presidentiële termijn. Donald Trump werd op 20 januari 2025 opnieuw president; op 3 november 2026 kiezen Amerikanen het volledige Huis van Afgevaardigden en ongeveer een derde van de Senaat. De president staat zelf niet op het stembiljet.', 'aside' => 'Ze bepalen het 120e Congres, dat op 3 januari 2027 aantreedt.'],
    ['id' => 'huis', 'nr' => '02', 'title' => 'Het Huis: 435 lokale races', 'text' => 'Iedere staat is verdeeld in congresdistricten. Elk district kiest één afgevaardigde voor twee jaar. Alle 435 stemgerechtigde zetels zijn tegelijk verkiesbaar. Bij een volledig bezet Huis zijn 218 zetels nodig voor een meerderheid en kiest die meerderheid de Speaker.', 'aside' => 'Nieuwe districtsgrenzen in meerdere staten maken vergelijking met 2024 extra lastig.'],
    ['id' => 'senaat', 'nr' => '03', 'title' => 'De Senaat: zes jaar per zetel', 'text' => 'Elke staat heeft twee senatoren. Hun termijnen duren zes jaar en zijn verdeeld over drie klassen, zodat iedere twee jaar ongeveer een derde wordt gekozen. In 2026 zijn er 33 reguliere Class II-races en speciale verkiezingen in Ohio en Florida: 35 races in totaal.', 'aside' => 'Bij 50-50 geeft vicepresident JD Vance de doorslag. Democraten hebben daardoor netto vier zetels winst nodig.'],
    ['id' => 'gouverneurs', 'nr' => '04', 'title' => 'Gouverneurs: macht buiten Washington', 'text' => 'In 36 staten wordt ook een gouverneur gekozen. Gouverneurs tekenen of blokkeren staatswetten, stellen begrotingen op en hebben invloed op onderwijs, gezondheidszorg, kiesregels en de uitvoering van federaal beleid.', 'aside' => 'De huidige nationale balans is 26 Republikeinse en 24 Democratische gouverneurs.'],
    ['id' => 'ratings', 'nr' => '05', 'title' => 'Ratings zijn geen kansen', 'text' => '“Zeker”, “waarschijnlijk”, “neigt” en “toss-up” zijn redactionele categorieën voor de uitgangspositie van een race. Ze zijn gebaseerd op onder meer eerdere uitslagen, kandidaten, campagnekwaliteit en de politieke omgeving. Een toss-up is niet automatisch exact 50-50.', 'aside' => 'PolitiekPraat vat ratings samen en toont altijd bron en peildatum.'],
    ['id' => 'markten', 'nr' => '06', 'title' => 'Marktprijzen zijn geen peilingen', 'text' => 'Op een voorspellingsmarkt handelen deelnemers contracten die uitbetalen als een gebeurtenis plaatsvindt. Een prijs van 0,60 wordt vaak gelezen als ongeveer 60 procent marktinschatting. Liquiditeit, sentiment en handelsregels beïnvloeden die prijs. Het is geen wetenschappelijk peilingsgemiddelde.', 'aside' => 'Daarom labelen we Polymarket-cijfers overal expliciet als marktprijzen.'],
    ['id' => 'gevolgen', 'nr' => '07', 'title' => 'Wat verandert er na november?', 'text' => 'Als een andere partij één of beide kamers wint, ontstaat divided government. Het Huis controleert begrotingen en kan onderzoeken starten; de Senaat beslist mee over rechters en andere benoemingen. Wetgeving wordt moeilijker zonder samenwerking tussen partijen.', 'aside' => 'De nieuwe leden worden op 3 januari 2027 beëdigd.'],
];
?>

<?php $active = 'uitleg'; require __DIR__ . '/partials/section-nav.php'; ?>
<?php require __DIR__ . '/partials/snapshot-strip.php'; ?>

<main class="midterms-main">
    <div class="pp-container pp-container--xl">
        <div class="mt-explainer-layout">
            <aside class="mt-explainer-nav" data-mt-reveal>
                <span class="mt-panel-label">In deze uitleg</span>
                <nav aria-label="Inhoud uitleg">
                    <?php foreach ($chapters as $chapter): ?><a href="#<?= pp_e($chapter['id']) ?>"><span><?= pp_e($chapter['nr']) ?></span><?= pp_e($chapter['title']) ?></a><?php endforeach; ?>
                </nav>
            </aside>

            <article class="mt-explainer-copy">
                <?php foreach ($chapters as $chapter): ?>
                    <section id="<?= pp_e($chapter['id']) ?>" class="midterms-uitleg__block" data-mt-reveal>
                        <span class="midterms-uitleg__number"><?= pp_e($chapter['nr']) ?></span>
                        <div>
                            <h2 class="midterms-uitleg__title"><?= pp_e($chapter['title']) ?></h2>
                            <p class="midterms-uitleg__text"><?= pp_e($chapter['text']) ?></p>
                            <aside><?= pp_icon('info', 14) ?> <?= pp_e($chapter['aside']) ?></aside>
                        </div>
                    </section>
                <?php endforeach; ?>
            </article>
        </div>

        <section class="mt-explainer-cta" data-mt-reveal>
            <div><span class="mt-panel-label">Klaar om de kaart te lezen?</span><h2>Bekijk waar de Senaat echt wordt beslist.</h2><p>Filter de kaart op toss-ups en open zetels en open daarna het battleboard.</p></div>
            <a href="<?= pp_e(pp_url('/midterms-2026/senaat')) ?>" class="mt-button mt-button--dark">Naar de Senaat <?= pp_icon('arrow-right', 15) ?></a>
        </section>

        <?php require __DIR__ . '/partials/source-dock.php'; ?>
        <?php require __DIR__ . '/partials/disclaimer.php'; ?>
    </div>
</main>
<?php require_once 'views/templates/footer.php'; ?>
