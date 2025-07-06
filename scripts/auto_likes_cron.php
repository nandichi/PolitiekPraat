<?php
// Automatische likes cron job script
// Dit script kan worden uitgevoerd door een cron job om automatisch likes toe te voegen

// Bepaal het absolute pad naar de project root
$scriptDir = dirname(__FILE__);
$projectRoot = dirname($scriptDir);

// Gebruik absolute paden voor alle includes
require_once $projectRoot . '/includes/config.php';
require_once $projectRoot . '/includes/Database.php';
require_once $projectRoot . '/includes/functions.php';
require_once $projectRoot . '/includes/mail_helper.php';

// Logging functie
function logMessage($message) {
    global $projectRoot;
    $logFile = $projectRoot . '/logs/auto_likes.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message" . PHP_EOL;
    
    // Zorg ervoor dat logs directory bestaat
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    echo $logEntry;
}

try {
    logMessage("Starting auto likes cron job");
    
    // Controleer of logs directory bestaat
    if (!is_dir($projectRoot . '/logs')) {
        mkdir($projectRoot . '/logs', 0755, true);
    }
    
    // Laad automatische instellingen
    $autoSettingsFile = $projectRoot . '/cache/auto_likes_settings.json';
    if (!file_exists($autoSettingsFile)) {
        logMessage("Auto likes settings file not found. Exiting.");
        exit;
    }
    
    $autoSettings = json_decode(file_get_contents($autoSettingsFile), true);
    
    // Controleer of automatische likes is ingeschakeld
    if (!($autoSettings['enabled'] ?? false)) {
        logMessage("Auto likes is disabled. Exiting.");
        exit;
    }
    
    // Controleer of het tijd is voor een nieuwe run
    $lastRun = $autoSettings['last_run'] ?? 0;
    $intervalSeconds = ($autoSettings['interval_hours'] ?? 6) * 3600;
    $now = time();
    
    if (($now - $lastRun) < $intervalSeconds) {
        $nextRun = $lastRun + $intervalSeconds;
        $remainingTime = $nextRun - $now;
        logMessage("Not time yet for next run. Next run in " . round($remainingTime / 3600, 1) . " hours");
        exit;
    }
    
    // Database verbinding
    $db = new Database();
    
    // Haal alle blogs op
    $db->query("SELECT id, title, likes, published_at FROM blogs WHERE published_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY published_at DESC");
    $blogs = $db->resultSet();
    
    if (empty($blogs)) {
        logMessage("No blogs found to update");
        exit;
    }
    
    $minLikes = $autoSettings['min_likes'] ?? 1;
    $maxLikes = $autoSettings['max_likes'] ?? 5;
    $updatedCount = 0;
    $totalLikesAdded = 0;
    
    logMessage("Processing " . count($blogs) . " blogs");
    
    foreach ($blogs as $blog) {
        // Willekeurige kans (70%) dat deze blog likes krijgt
        if (rand(1, 100) <= 70) {
            $randomLikes = rand($minLikes, $maxLikes);
            $newLikes = $blog->likes + $randomLikes;
            
            $db->query("UPDATE blogs SET likes = :likes WHERE id = :id");
            $db->bind(':likes', $newLikes);
            $db->bind(':id', $blog->id);
            
            if ($db->execute()) {
                $updatedCount++;
                $totalLikesAdded += $randomLikes;
                logMessage("Added $randomLikes likes to blog: " . substr($blog->title, 0, 50) . "...");
            } else {
                logMessage("Failed to update likes for blog ID: " . $blog->id);
            }
        }
    }
    
    // Update last run tijd
    $autoSettings['last_run'] = $now;
    file_put_contents($autoSettingsFile, json_encode($autoSettings));
    
    logMessage("Auto likes cron job completed successfully");
    logMessage("Updated $updatedCount blogs with $totalLikesAdded total likes");
    
    // Email notificatie versturen
    $emailSummary = "Auto Likes succesvol uitgevoerd! {$updatedCount} blogs bijgewerkt met {$totalLikesAdded} likes.";
    $emailDetails = "Likes Resultaten:\n";
    $emailDetails .= "- Blogs verwerkt: " . count($blogs) . "\n";
    $emailDetails .= "- Blogs bijgewerkt: {$updatedCount}\n";
    $emailDetails .= "- Totale likes toegevoegd: {$totalLikesAdded}\n";
    $emailDetails .= "- Min likes per blog: {$minLikes}\n";
    $emailDetails .= "- Max likes per blog: {$maxLikes}\n";
    $emailDetails .= "- Interval: " . ($autoSettings['interval_hours'] ?? 6) . " uur\n";
    $emailDetails .= "- Tijd: " . date('Y-m-d H:i:s') . "\n";
    
    // Verstuur email
    $logFile = $projectRoot . '/logs/auto_likes.log';
    $emailSent = sendCronJobEmail(
        'Auto Likes',
        'success',
        $emailSummary,
        $emailDetails,
        $logFile
    );
    
    if ($emailSent) {
        logMessage("Email notificatie verstuurd naar admin");
    } else {
        logMessage("Waarschuwing: Email notificatie kon niet worden verstuurd");
    }
    
} catch (Exception $e) {
    logMessage("Error in auto likes cron job: " . $e->getMessage());
    logMessage("Stack trace: " . $e->getTraceAsString());
    
    // Verstuur error email
    $errorSummary = "Auto Likes FOUT! Het script is gestopt met een kritieke fout.";
    $errorDetails = "Fout: " . $e->getMessage() . "\n\n";
    $errorDetails .= "Stack trace:\n" . $e->getTraceAsString() . "\n\n";
    $errorDetails .= "Tijd: " . date('Y-m-d H:i:s') . "\n";
    $errorDetails .= "Server: " . gethostname() . "\n";
    
    $logFile = $projectRoot . '/logs/auto_likes.log';
    $emailSent = sendCronJobEmail(
        'Auto Likes - ERROR',
        'error',
        $errorSummary,
        $errorDetails,
        $logFile
    );
    
    if ($emailSent) {
        logMessage("Error email notificatie verstuurd naar admin");
    } else {
        logMessage("Kritiek: Error email kon niet worden verstuurd!");
    }
    
    // Log naar main error log ook
    error_log("Auto Likes Cron Error: " . $e->getMessage());
}
?> 