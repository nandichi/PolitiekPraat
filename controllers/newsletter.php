<?php
// Voorkom dat PHP errors de JSON output verstoren
header('Content-Type: application/json');
error_reporting(0);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/Controller.php';  // Aangepast pad naar includes directory
require_once __DIR__ . '/../includes/email_templates/newsletter_signup.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Newsletter extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try {
                    // Laad de mail configuratie
                    $mail_config = require __DIR__ . '/../includes/mail_config.php';
                    
                    // PHPMailer setup
                    $mail = new PHPMailer(true);
                    
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = $mail_config['smtp_host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $mail_config['smtp_username'];
                    $mail->Password = $mail_config['smtp_password'];
                    $mail->SMTPSecure = $mail_config['smtp_secure'];
                    $mail->Port = $mail_config['smtp_port'];
                    $mail->CharSet = 'UTF-8';
                    
                    // Recipients
                    $mail->setFrom($mail_config['from_email'], $mail_config['from_name']);
                    foreach ($mail_config['to_emails'] as $to_email) {
                        $mail->addAddress($to_email);
                    }
                    
                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Nieuwe nieuwsbrief aanmelding - PolitiekPraat';
                    $mail->Body = getNewsletterSignupTemplate($email);
                    $mail->AltBody = "Nieuwe nieuwsbrief aanmelding\n\nE-mail: {$email}\nDatum: " . date('d-m-Y H:i:s');
                    
                    $mail->send();
                    $this->json(['status' => 'success', 'message' => 'Bedankt voor je aanmelding!']);
                } catch (Exception $e) {
                    $this->json([
                        'status' => 'error', 
                        'message' => 'Er is iets misgegaan. Probeer het later opnieuw.'
                    ]);
                }
            } else {
                $this->json(['status' => 'error', 'message' => 'Ongeldig e-mailadres']);
            }
        }
    }
}

// Instantieer de controller en roep de juiste methode aan
$controller = new Newsletter();
$controller->subscribe(); 