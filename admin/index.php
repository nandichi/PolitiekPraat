<?php
/**
 * Admin landing page.
 *
 * /admin (en /admin/) leverde op productie een 403 op omdat:
 *   1. Er geen `DirectoryIndex`-bestand in deze map stond.
 *   2. De root `.htaccess` "Options -Indexes" zet, waardoor Apache geen
 *      directory listing kan tonen en daarom 403 Forbidden teruggeeft.
 *
 * Door dit bestand neer te zetten heeft Apache wel een index om te serveren.
 * Het stuurt elke aanvraag door naar het dashboard, dat zelf controleert of
 * de bezoeker is ingelogd als admin en anders doorstuurt naar de login.
 */

header('Location: dashboard.php', true, 302);
exit;
