<?php
/**
 * Countdown naar Election Day.
 * Props: $electionDate (Y-m-d), $daysLeft (int, server-side fallback).
 */
$electionDate = $electionDate ?? ($props['electionDate'] ?? '2026-11-03');
$daysLeft = $daysLeft ?? ($props['daysLeft'] ?? null);
// Polluiting sluit doorgaans rond 19:00-20:00 ET; we mikken op middernacht ET.
$targetIso = $electionDate . 'T00:00:00-05:00';
?>
<div class="midterms-countdown" data-midterms-countdown data-target="<?= pp_e($targetIso) ?>">
    <div class="midterms-countdown__unit">
        <span class="midterms-countdown__value" data-cd-days><?= $daysLeft !== null ? (int) $daysLeft : '--' ?></span>
        <span class="midterms-countdown__label">dagen</span>
    </div>
    <div class="midterms-countdown__unit">
        <span class="midterms-countdown__value" data-cd-hours>--</span>
        <span class="midterms-countdown__label">uren</span>
    </div>
    <div class="midterms-countdown__unit">
        <span class="midterms-countdown__value" data-cd-minutes>--</span>
        <span class="midterms-countdown__label">minuten</span>
    </div>
    <div class="midterms-countdown__unit">
        <span class="midterms-countdown__value" data-cd-seconds>--</span>
        <span class="midterms-countdown__label">seconden</span>
    </div>
</div>
