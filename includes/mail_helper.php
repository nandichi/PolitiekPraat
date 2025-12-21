<?php
// Mail helper functie voor het versturen van cron job status emails
// Gebruikt bestaande mail configuratie uit mail_config.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// PHPMailer laden (aanpassing afhankelijk van je installatie)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    // Alternatieve methode als PHPMailer niet via Composer is geïnstalleerd
    require_once __DIR__ . '/phpmailer/PHPMailer.php';
    require_once __DIR__ . '/phpmailer/SMTP.php';
    require_once __DIR__ . '/phpmailer/Exception.php';
}

/**
 * Verstuur een cron job status email
 * 
 * @param string $jobName - Naam van de cron job
 * @param string $status - Status: 'success', 'error', 'warning'
 * @param string $summary - Korte samenvatting
 * @param string $details - Uitgebreide details
 * @param string $logFile - Path naar log bestand (optioneel)
 * @return bool - Success/failure
 */
function sendCronJobEmail($jobName, $status, $summary, $details = '', $logFile = '') {
    try {
        // Laad mail configuratie
        $mailConfig = include __DIR__ . '/mail_config.php';
        
        // Maak PHPMailer instantie
        $mail = new PHPMailer(true);
        
        // SMTP configuratie
        $mail->isSMTP();
        $mail->Host = $mailConfig['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mailConfig['smtp_username'];
        $mail->Password = $mailConfig['smtp_password'];
        $mail->SMTPSecure = $mailConfig['smtp_secure'];
        $mail->Port = $mailConfig['smtp_port'];
        $mail->CharSet = 'UTF-8';
        
        // Extra opties voor betere SSL/TLS compatibiliteit
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        
        // Zender
        $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
        
        // Ontvanger - specifiek naar naoufal.exe14@gmail.com
        $mail->addAddress('naoufal.exe14@gmail.com', 'Naoufal');
        
        // Email content
        $statusEmoji = [
            'success' => '✅',
            'error' => '❌',
            'warning' => '⚠️'
        ];
        
        $statusColor = [
            'success' => '#22c55e',
            'error' => '#ef4444',
            'warning' => '#f59e0b'
        ];
        
        $emoji = $statusEmoji[$status] ?? '📋';
        $color = $statusColor[$status] ?? '#6b7280';
        
        $mail->isHTML(true);
        $mail->Subject = "$emoji PolitiekPraat Cron Job: $jobName";
        
        // HTML email template
        $htmlBody = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, $color 0%, " . adjustBrightness($color, -20) . " 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
                .status { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                .summary { font-size: 18px; margin-bottom: 20px; }
                .details { background: white; padding: 15px; border-radius: 4px; margin: 15px 0; }
                .footer { text-align: center; margin-top: 20px; color: #666; font-size: 14px; }
                .log-section { background: #1a1a1a; color: #00ff00; padding: 15px; border-radius: 4px; font-family: monospace; overflow-x: auto; }
                .timestamp { color: #888; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>$emoji PolitiekPraat Cron Job Status</h1>
                    <div class='timestamp'>" . date('Y-m-d H:i:s') . "</div>
                </div>
                <div class='content'>
                    <div class='status'>Job: $jobName</div>
                    <div class='summary'>$summary</div>
                    
                    " . (!empty($details) ? "<div class='details'><h3>Details:</h3><pre>$details</pre></div>" : "") . "
                    
                    " . (!empty($logFile) && file_exists($logFile) ? "
                    <div class='details'>
                        <h3>Recente Log Entries:</h3>
                        <div class='log-section'>" . getRecentLogEntries($logFile, 10) . "</div>
                    </div>" : "") . "
                    
                    <div class='footer'>
                        <p>Dit is een automatisch bericht van PolitiekPraat</p>
                        <p>Server: " . gethostname() . " | Tijd: " . date('Y-m-d H:i:s') . "</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
        
        $mail->Body = $htmlBody;
        
        // Plain text alternatief
        $textBody = "
PolitiekPraat Cron Job Status

Job: $jobName
Status: $status
Tijd: " . date('Y-m-d H:i:s') . "

Samenvatting:
$summary

" . (!empty($details) ? "Details:\n$details\n\n" : "") . "

Dit is een automatisch bericht van PolitiekPraat.
        ";
        
        $mail->AltBody = $textBody;
        
        // Verstuur email
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Cron email fout: " . $e->getMessage());
        return false;
    }
}

/**
 * Haal recente log entries op
 */
function getRecentLogEntries($logFile, $lines = 10) {
    if (!file_exists($logFile)) {
        return "Log bestand niet gevonden: $logFile";
    }
    
    $logEntries = file($logFile);
    $recentEntries = array_slice($logEntries, -$lines);
    
    return htmlspecialchars(implode('', $recentEntries));
}

/**
 * Kleur helderheid aanpassen
 */
function adjustBrightness($hexColor, $percent) {
    $hex = str_replace('#', '', $hexColor);
    
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = max(0, min(255, round($r + ($r * $percent / 100))));
    $g = max(0, min(255, round($g + ($g * $percent / 100))));
    $b = max(0, min(255, round($b + ($b * $percent / 100))));
    
    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) .
                 str_pad(dechex($g), 2, '0', STR_PAD_LEFT) .
                 str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}

/**
 * Verstuur test email
 */
function sendTestCronEmail() {
    return sendCronJobEmail(
        'Test Email',
        'success',
        'Dit is een test email om te controleren of de cron job email functionaliteit werkt.',
        'Test details: Alle systemen functioneren correct.',
        ''
    );
}
?> 