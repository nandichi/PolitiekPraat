<?php
// Voorkom dat PHP errors de JSON output verstoren
if (!defined('CALLED_FROM_BLOG_CONTROLLER')) {
    header('Content-Type: application/json');
    error_reporting(0);
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/Controller.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/email_templates/newsletter_signup.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Newsletter extends Controller {
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->db = new Database();
    }

    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try {
                    // Controleer of het e-mailadres al bestaat in de database
                    $this->db->query("SELECT * FROM newsletter_subscribers WHERE email = :email");
                    $this->db->bind(':email', $email);
                    $existingSubscriber = $this->db->single();
                    
                    if ($existingSubscriber) {
                        // E-mail bestaat al, controleer status
                        if ($existingSubscriber['status'] === 'unsubscribed') {
                            // Update status naar actief
                            $this->db->query("UPDATE newsletter_subscribers SET status = 'active' WHERE email = :email");
                            $this->db->bind(':email', $email);
                            $this->db->execute();
                            
                            $this->json(['status' => 'success', 'message' => 'Je bent opnieuw aangemeld voor de nieuwsbrief!']);
                        } else {
                            // E-mail is al aangemeld
                            $this->json(['status' => 'info', 'message' => 'Je bent al aangemeld voor de nieuwsbrief!']);
                        }
                    } else {
                        // Voeg de nieuwe abonnee toe aan de database
                        $this->db->query("INSERT INTO newsletter_subscribers (email) VALUES (:email)");
                        $this->db->bind(':email', $email);
                        $this->db->execute();
                        
                        // Stuur een bevestigingsmail naar de beheerder
                        $this->sendConfirmationEmail($email);
                        
                        $this->json(['status' => 'success', 'message' => 'Bedankt voor je aanmelding! Je ontvangt vanaf nu updates over nieuwe blogs.']);
                    }
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
    
    public function unsubscribe() {
        $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
        
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                // Update de status in de database
                $this->db->query("UPDATE newsletter_subscribers SET status = 'unsubscribed' WHERE email = :email");
                $this->db->bind(':email', $email);
                $this->db->execute();
                
                // Return success page
                $this->redirectTo('/newsletter/unsubscribe-success');
            } catch (Exception $e) {
                // Return error page
                $this->redirectTo('/newsletter/unsubscribe-error');
            }
        } else {
            $this->redirectTo('/newsletter/unsubscribe-error');
        }
    }
    
    public function unsubscribeSuccess() {
        require_once __DIR__ . '/../views/newsletter/unsubscribe-success.php';
    }
    
    public function unsubscribeError() {
        require_once __DIR__ . '/../views/newsletter/unsubscribe-error.php';
    }
    
    private function sendConfirmationEmail($email) {
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
        $mail->addAddress('info@politiekpraat.nl');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Nieuwe nieuwsbrief aanmelding - PolitiekPraat';
        $mail->Body = getNewsletterSignupTemplate($email);
        $mail->AltBody = "Nieuwe nieuwsbrief aanmelding\n\nE-mail: {$email}\nDatum: " . date('d-m-Y H:i:s');
        
        $mail->send();
    }
    
    // Deze methode kan vanuit BlogsController worden aangeroepen wanneer een nieuwe blog wordt gepubliceerd
    public function sendNewBlogNotifications($blogId) {
        require_once __DIR__ . '/../includes/email_templates/new_blog_notification.php';
        
        // Laad de blog details
        $this->db->query("SELECT blogs.*, users.username as author_name 
                         FROM blogs 
                         JOIN users ON blogs.author_id = users.id 
                         WHERE blogs.id = :blog_id");
        $this->db->bind(':blog_id', $blogId);
        $blog = $this->db->single();
        
        if (!$blog) {
            return false;
        }
        
        // Haal alle actieve subscribers op
        $this->db->query("SELECT * FROM newsletter_subscribers WHERE status = 'active'");
        $subscribers = $this->db->resultSet();
        
        if (empty($subscribers)) {
            return false;
        }
        
        // Laad de mail configuratie
        $mail_config = require __DIR__ . '/../includes/mail_config.php';
        
        // Mail naar elke abonnee
        foreach ($subscribers as $subscriber) {
            try {
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
                $mail->addAddress($subscriber->email);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Nieuw artikel: ' . $blog->title . ' - PolitiekPraat';
                $mail->Body = getNewBlogNotificationTemplate(
                    $subscriber->email,
                    $blog->title,
                    $blog->summary,
                    $blog->slug,
                    $blog->image_path,
                    $blog->author_name
                );
                $mail->AltBody = "Nieuw artikel op PolitiekPraat: {$blog->title}\n\nGeschreven door: {$blog->author_name}\n\n{$blog->summary}\n\nLees verder op: " . URLROOT . "/blogs/{$blog->slug}";
                
                $mail->send();
                
                // Update last_notification_at voor deze abonnee
                $this->db->query("UPDATE newsletter_subscribers SET last_notification_at = NOW() WHERE id = :id");
                $this->db->bind(':id', $subscriber->id);
                $this->db->execute();
            } catch (Exception $e) {
                // Log de fout, maar ga door met de volgende abonnee
                error_log("Error sending notification to {$subscriber->email}: " . $e->getMessage());
            }
        }
        
        return true;
    }
}

// Route dispatcher - alleen uitvoeren als deze controller direct wordt aangeroepen
if (!defined('CALLED_FROM_BLOG_CONTROLLER')) {
    $action = isset($_GET['action']) ? $_GET['action'] : 'subscribe';
    $controller = new Newsletter();
    
    switch ($action) {
        case 'unsubscribe':
            $controller->unsubscribe();
            break;
        case 'unsubscribe-success':
            $controller->unsubscribeSuccess();
            break;
        case 'unsubscribe-error':
            $controller->unsubscribeError();
            break;
        default:
            $controller->subscribe();
            break;
    }
} 