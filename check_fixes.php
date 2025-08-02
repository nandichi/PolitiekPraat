<?php
/**
 * StemmenTracker Fixes Verificatie Script
 * 
 * Run dit script om te checken of alle fixes correct zijn toegepast
 */

echo "🔍 StemmenTracker Fixes Verificatie\n";
echo "=====================================\n\n";

// Check 1: Router.php bevat query parameter fix
$routerContent = file_get_contents('includes/Router.php');
if (strpos($routerContent, '$originalUrl = $url;') !== false && 
    strpos($routerContent, 'parse_str($queryString, $queryParams);') !== false) {
    echo "✅ Router.php: Query parameter fix aanwezig\n";
} else {
    echo "❌ Router.php: Query parameter fix ONTBREEKT\n";
}

// Check 2: functions.php isAdmin fix
$functionsContent = file_get_contents('includes/functions.php');
if (strpos($functionsContent, '$_SESSION[\'is_admin\'] == 1') !== false) {
    echo "✅ functions.php: isAdmin() fix aanwezig\n";
} else {
    echo "❌ functions.php: isAdmin() fix ONTBREEKT\n";
}

// Check 3: helpers.php isAdmin fix
$helpersContent = file_get_contents('includes/helpers.php');
if (strpos($helpersContent, '$_SESSION[\'is_admin\'] == 1') !== false) {
    echo "✅ helpers.php: isAdmin() fix aanwezig\n";
} else {
    echo "❌ helpers.php: isAdmin() fix ONTBREEKT\n";
}

// Check 4: index.php nieuwe routes
$indexContent = file_get_contents('index.php');
if (strpos($indexContent, 'stemmentracker/detail/([0-9]+)') !== false) {
    echo "✅ index.php: Nieuwe stemmentracker routes aanwezig\n";
} else {
    echo "❌ index.php: Nieuwe stemmentracker routes ONTBREKEN\n";
}

// Check 5: views/stemmentracker/index.php links
$viewContent = file_get_contents('views/stemmentracker/index.php');
if (strpos($viewContent, 'stemmentracker/detail/') !== false && 
    strpos($viewContent, '?action=detail&id=') === false) {
    echo "✅ views/stemmentracker/index.php: Nieuwe URL structuur aanwezig\n";
} else {
    echo "❌ views/stemmentracker/index.php: Nieuwe URL structuur ONTBREEKT\n";
}

echo "\n📋 Bestanden klaar voor upload:\n";
echo "- includes/Router.php\n";
echo "- includes/functions.php\n";
echo "- includes/helpers.php\n";
echo "- index.php\n";
echo "- views/stemmentracker/index.php\n";

echo "\n🚀 Upload deze bestanden naar politiekpraat.nl om de fixes live te krijgen!\n";
?>