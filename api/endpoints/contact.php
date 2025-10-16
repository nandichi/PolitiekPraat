<?php
/**
 * Contact API Endpoint
 * Handles contact form submissions
 */

class ContactAPI {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function handle($method, $segments) {
        switch ($method) {
            case 'POST':
                $this->submitContact();
                break;
                
            default:
                sendApiError('Method niet toegestaan. Alleen POST is beschikbaar.', 405);
        }
    }
    
    private function submitContact() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            $required = ['name', 'email', 'subject', 'message'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty(trim($data[$field]))) {
                    sendApiError("Veld '{$field}' is verplicht", 400);
                    return;
                }
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                sendApiError('Ongeldig email adres', 400);
                return;
            }
            
            // Sanitize input
            $name = htmlspecialchars(trim($data['name']));
            $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
            $subject = htmlspecialchars(trim($data['subject']));
            $message = htmlspecialchars(trim($data['message']));
            
            // Send email (if configured)
            $to = 'naoufal.exe@gmail.com';
            $emailSubject = "Contact formulier: {$subject}";
            $emailBody = "Naam: {$name}\n";
            $emailBody .= "Email: {$email}\n\n";
            $emailBody .= "Bericht:\n{$message}";
            
            $headers = "From: {$email}\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            
            // Note: mail() function may not work on all servers
            // Consider using a proper email service like SendGrid, Mailgun, etc.
            $mailSent = @mail($to, $emailSubject, $emailBody, $headers);
            
            sendApiResponse([
                'message' => 'Contactbericht succesvol verzonden',
                'email_sent' => $mailSent,
                'note' => $mailSent ? null : 'Email verzending kan gefaald zijn. Neem rechtstreeks contact op als u geen antwoord ontvangt.'
            ]);
            
        } catch (Exception $e) {
            sendApiError('Fout bij verzenden contactbericht: ' . $e->getMessage(), 500);
        }
    }
}

