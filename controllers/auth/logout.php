<?php
// Verwijder alle sessie variabelen
session_unset();

// Vernietig de sessie
session_destroy();

// Redirect naar homepage
header('Location: ' . URLROOT);
exit; 