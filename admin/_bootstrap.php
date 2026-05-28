<?php
declare(strict_types=1);

/**
 * Standaard bootstrap voor admin-pagina's: config, database, helpers + guard.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
}

require_once __DIR__ . '/_guard.php';
