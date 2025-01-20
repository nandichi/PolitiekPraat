<?php
function redirect($page) {
    header('location: ' . URLROOT . '/' . $page);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function sanitize($dirty) {
    return htmlspecialchars($dirty, ENT_QUOTES, 'UTF-8');
}

function generateSlug($text) {
    // Vervang non-alphanumerieke karakters met een streepje
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Translitereer
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Verwijder ongewenste karakters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Verwijder duplicate streepjes
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);
    
    return $text;
}

function formatDate($date) {
    return date('d-m-Y', strtotime($date));
} 