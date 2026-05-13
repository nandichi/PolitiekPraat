<?php
/**
 * Light/Dark theme toggle.
 *
 * Werkt samen met public/js/theme.js die `data-theme` op <html> set
 * en de gebruikersvoorkeur in localStorage bewaart.
 */
?>
<button type="button"
        class="theme-toggle"
        data-theme-toggle
        aria-label="Wissel licht/donker modus"
        title="Wissel licht/donker modus">
    <span class="icon-sun"><?= pp_icon('sun', 18) ?></span>
    <span class="icon-moon"><?= pp_icon('moon', 18) ?></span>
</button>
