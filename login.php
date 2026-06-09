<?php
/**
 * Legacy login-bestand.
 *
 * Dit rootbestand gebruikte voorheen relatieve includes (../includes/...) die
 * buiten de webroot wezen en daardoor HTTP 500 gaven. De actuele login draait
 * via de router op /login (controllers/auth/login.php). We sturen oude
 * bookmarks/links daarom permanent door naar de juiste route.
 */

header('Location: /login', true, 301);
exit;
