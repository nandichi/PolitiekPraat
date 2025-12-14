<?php
/**
 * Auto Likes Cronjob
 * 
 * Dit script simuleert realistische likes op blogs door:
 * - Onregelmatige timing (willekeurige kans per blog)
 * - Variabele hoeveelheid likes
 * - Logging naar database voor beheer
 * 
 * Aanbevolen cron: elke 15 minuten - zie setup_cron.sh
 */

$scriptDir = dirname(__FILE__);
$projectRoot = dirname($scriptDir);

require_once $projectRoot . '/includes/config.php';
require_once $projectRoot . '/includes/Database.php';
require_once $projectRoot . '/includes/functions.php';

// Optioneel: mail helper voor notificaties
if (file_exists($projectRoot . '/includes/mail_helper.php')) {
    require_once $projectRoot . '/includes/mail_helper.php';
}

/**
 * Log naar console/file
 */
function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message";
    echo $logEntry . PHP_EOL;
}

/**
 * Haal een instelling op uit de database
 */
function getSetting($db, $key, $default = null) {
    $db->query("SELECT setting_value FROM auto_likes_settings WHERE setting_key = :key");
    $db->bind(':key', $key);
    $result = $db->single();
    return $result ? $result->setting_value : $default;
}

/**
 * Update een instelling in de database
 */
function updateSetting($db, $key, $value) {
    $db->query("UPDATE auto_likes_settings SET setting_value = :value WHERE setting_key = :key");
    $db->bind(':value', $value);
    $db->bind(':key', $key);
    return $db->execute();
}

try {
    logMessage("=== Auto Likes Cron gestart ===");
    
    // Random delay van 0-120 seconden voor extra onregelmatigheid
    $randomDelay = rand(0, 120);
    logMessage("Random delay: {$randomDelay} seconden");
    sleep($randomDelay);
    
    $db = new Database();
    
    // Controleer of de benodigde tabellen bestaan
    $db->query("SHOW TABLES LIKE 'auto_likes_settings'");
    $tableExists = $db->single();
    
    if (!$tableExists) {
        logMessage("FOUT: Database tabellen bestaan niet. Voer eerst de migratie uit:");
        logMessage("php " . $projectRoot . "/scripts/create_auto_likes_tables.php");
        exit(1);
    }
    
    // Controleer of het systeem globaal is ingeschakeld
    $globalEnabled = getSetting($db, 'global_enabled', '0');
    if ($globalEnabled !== '1') {
        logMessage("Auto likes systeem is globaal uitgeschakeld. Stoppen.");
        exit;
    }
    
    // Haal globale instellingen op
    $likeChanceMin = (int)getSetting($db, 'like_chance_min', '30');
    $likeChanceMax = (int)getSetting($db, 'like_chance_max', '70');
    $defaultMinLikes = (int)getSetting($db, 'default_min_likes', '1');
    $defaultMaxLikes = (int)getSetting($db, 'default_max_likes', '5');
    
    logMessage("Instellingen: kans {$likeChanceMin}-{$likeChanceMax}%, likes {$defaultMinLikes}-{$defaultMaxLikes}");
    
    // Haal alle ingeschakelde blogs op met hun configuratie
    $db->query("
        SELECT 
            b.id,
            b.title,
            b.likes,
            COALESCE(alc.enabled, 1) as auto_enabled,
            COALESCE(alc.min_likes_per_run, :default_min) as min_likes,
            COALESCE(alc.max_likes_per_run, :default_max) as max_likes
        FROM blogs b
        LEFT JOIN auto_likes_config alc ON b.id = alc.blog_id
        WHERE COALESCE(alc.enabled, 1) = 1
        ORDER BY b.published_at DESC
    ");
    $db->bind(':default_min', $defaultMinLikes);
    $db->bind(':default_max', $defaultMaxLikes);
    $blogs = $db->resultSet();
    
    if (empty($blogs)) {
        logMessage("Geen blogs gevonden met auto likes ingeschakeld.");
        exit;
    }
    
    logMessage("Gevonden: " . count($blogs) . " blogs met auto likes ingeschakeld");
    
    $updatedCount = 0;
    $totalLikesAdded = 0;
    
    foreach ($blogs as $blog) {
        // Genereer willekeurige kans voor deze run
        $randomChance = rand($likeChanceMin, $likeChanceMax);
        $roll = rand(1, 100);
        
        if ($roll <= $randomChance) {
            // Deze blog krijgt likes
            $likesToAdd = rand($blog->min_likes, $blog->max_likes);
            $newLikeCount = $blog->likes + $likesToAdd;
            
            // Update likes in blogs tabel
            $db->query("UPDATE blogs SET likes = :likes WHERE id = :id");
            $db->bind(':likes', $newLikeCount);
            $db->bind(':id', $blog->id);
            
            if ($db->execute()) {
                // Log naar auto_likes_log
                $db->query("INSERT INTO auto_likes_log (blog_id, likes_added) VALUES (:blog_id, :likes_added)");
                $db->bind(':blog_id', $blog->id);
                $db->bind(':likes_added', $likesToAdd);
                $db->execute();
                
                $updatedCount++;
                $totalLikesAdded += $likesToAdd;
                
                $shortTitle = mb_substr($blog->title, 0, 40);
                logMessage("+ {$likesToAdd} likes -> \"{$shortTitle}...\" (roll: {$roll} <= {$randomChance})");
            }
        }
    }
    
    // Update last_run timestamp
    updateSetting($db, 'last_run', time());
    
    logMessage("=== Samenvatting ===");
    logMessage("Blogs verwerkt: " . count($blogs));
    logMessage("Blogs bijgewerkt: {$updatedCount}");
    logMessage("Totaal likes toegevoegd: {$totalLikesAdded}");
    logMessage("=== Auto Likes Cron voltooid ===");
    
} catch (Exception $e) {
    logMessage("FOUT: " . $e->getMessage());
    logMessage("Stack trace: " . $e->getTraceAsString());
    
    // Log naar error log
    error_log("Auto Likes Cron Error: " . $e->getMessage());
    
    exit(1);
}
?>
