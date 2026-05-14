<?php
/**
 * Dev environment banner.
 *
 * Wordt alleen getoond wanneer we NIET op productie draaien, zodat het direct
 * duidelijk is wanneer je de lokale dev-versie bekijkt (bv. via
 * http://localhost:8000) i.p.v. politiekpraat.nl.
 *
 * Dismissible per sessie (sessionStorage). Wordt automatisch verborgen op
 * production hosts, ook als de constant per ongeluk niet correct staat.
 */

if (defined('APP_ENV') && APP_ENV === 'production') {
    return;
}

$host = $_SERVER['HTTP_HOST'] ?? '';
if (in_array(strtolower($host), ['politiekpraat.nl', 'www.politiekpraat.nl'], true)) {
    return;
}

$db_host  = defined('DB_HOST') ? DB_HOST : '?';
$db_port  = defined('DB_PORT') ? (string) DB_PORT : '';
$db_target = $db_port !== '' ? $db_host . ':' . $db_port : $db_host;
$env_label = defined('APP_ENV') ? APP_ENV : 'unknown';
?>

<div id="pp-dev-banner"
     class="pp-dev-banner"
     role="status"
     aria-label="Lokale ontwikkelomgeving"
     hidden>
    <div class="pp-dev-banner__inner">
        <span class="pp-dev-banner__dot" aria-hidden="true"></span>
        <span class="pp-dev-banner__label">Lokale ontwikkelomgeving</span>
        <span class="pp-dev-banner__sep" aria-hidden="true">·</span>
        <span class="pp-dev-banner__meta">
            <span class="pp-dev-banner__meta-key">env</span>
            <span class="pp-dev-banner__meta-value"><?= pp_e($env_label) ?></span>
        </span>
        <span class="pp-dev-banner__sep" aria-hidden="true">·</span>
        <span class="pp-dev-banner__meta">
            <span class="pp-dev-banner__meta-key">host</span>
            <span class="pp-dev-banner__meta-value"><?= pp_e($host !== '' ? $host : 'cli') ?></span>
        </span>
        <span class="pp-dev-banner__sep" aria-hidden="true">·</span>
        <span class="pp-dev-banner__meta">
            <span class="pp-dev-banner__meta-key">db</span>
            <span class="pp-dev-banner__meta-value"><?= pp_e($db_target) ?></span>
        </span>
        <button type="button"
                class="pp-dev-banner__dismiss"
                aria-label="Verberg dev-banner voor deze sessie"
                data-pp-dev-banner-dismiss>
            <?= pp_icon('x', 14) ?>
        </button>
    </div>
</div>

<script>
    (function () {
        var banner = document.getElementById('pp-dev-banner');
        if (!banner) return;
        try {
            if (sessionStorage.getItem('pp-dev-banner-dismissed') === '1') {
                return;
            }
        } catch (e) { /* sessionStorage geblokkeerd */ }
        banner.hidden = false;
        var btn = banner.querySelector('[data-pp-dev-banner-dismiss]');
        if (btn) {
            btn.addEventListener('click', function () {
                banner.hidden = true;
                try { sessionStorage.setItem('pp-dev-banner-dismissed', '1'); } catch (e) {}
            });
        }
    })();
</script>
