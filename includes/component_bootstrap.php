<?php
/**
 * Component bootstrap.
 *
 * Laadt globale helpers en stelt pp_* functies beschikbaar
 * voor templates. Wordt geïncludeerd via includes/functions.php
 * (na het laden van de basis configuratie/URLROOT).
 */

$componentRoot = __DIR__ . '/../views/components';

require_once $componentRoot . '/helpers.php';
require_once $componentRoot . '/ui/icon.php';
