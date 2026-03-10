<?php
$datasetVersion = $datasetVersion ?? 'ede-2026-v1.0';
$peildatum = $peildatum ?? '2026-03-10';
?>
<div class="mt-6 max-w-4xl mx-auto text-left bg-amber-50 border border-amber-200 rounded-2xl p-4">
    <p class="text-sm text-amber-900">
        <strong>Belangrijk:</strong> dit is een voorkeursovereenkomst, geen stemadvies.
        Datasetversie: <?= htmlspecialchars($datasetVersion, ENT_QUOTES, 'UTF-8') ?> ·
        Peildatum: <?= htmlspecialchars($peildatum, ENT_QUOTES, 'UTF-8') ?>.
        <a href="https://github.com/nandichi/PolitiekPraat/blob/main/docs/gemeentelijke-stemwijzer/legal-ethics.md" target="_blank" class="underline">Methode & juridisch kader</a> ·
        <a href="https://github.com/nandichi/PolitiekPraat/blob/main/docs/gemeentelijke-stemwijzer/ede-2026-stellingen-v1.0.md" target="_blank" class="underline">Stellingen & bronkaders</a> ·
        Correctieverzoek: <a href="mailto:redactie@politiekpraat.nl" class="underline">redactie@politiekpraat.nl</a>
    </p>
</div>
