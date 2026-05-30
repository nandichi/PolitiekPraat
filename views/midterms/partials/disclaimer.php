<?php
/**
 * Disclaimer + bronvermelding voor de Midterms 2026 sectie.
 * Prop: $text (string) optioneel.
 */
$text = $text ?? ($props['text'] ?? 'De voorspellingen en odds op deze pagina zijn inschattingen, geen zekerheden en geen stemadvies. Cijfers kunnen elk moment veranderen.');
?>
<aside class="midterms-disclaimer" role="note">
    <span class="midterms-disclaimer__icon" aria-hidden="true"><?= pp_icon('info', 16) ?></span>
    <p><?= pp_e($text) ?></p>
</aside>
