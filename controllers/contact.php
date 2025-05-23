<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

// Laad mail configuratie
$mail_config = require_once BASE_PATH . '/includes/mail_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Validatie
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Vul alle velden in';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ongeldig e-mailadres';
    } else {
        // E-mail versturen met PHPMailer
        $mail = new PHPMailer(true);

        try {
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
            $mail->addAddress('naoufal.exe@gmail.com');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Contact Formulier: " . $subject;
            
            // HTML Email Template
            $emailTemplate = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset styles voor email clients */
        body, table, td, div, p {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
        }
        
        /* Container styling */
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Header styling met Nederlandse vlag elementen */
        .header {
            position: relative;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
            border-bottom: 5px solid #AE1C28;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background-color: #FFFFFF;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            height: 5px;
            background-color: #21468B;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .header-subtitle {
            margin-top: 10px;
            font-size: 16px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
        }
        
        /* Decoratieve elementen */
        .political-decoration {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            opacity: 0.1;
        }
        
        .political-decoration.left {
            left: 20px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23ffffff"><path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/></svg>');
            background-size: contain;
        }
        
        .political-decoration.right {
            right: 20px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23ffffff"><path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/></svg>');
            background-size: contain;
            transform: translateY(-50%) scaleX(-1);
        }
        
        /* Content styling */
        .content {
            padding: 40px 30px;
            background-color: #ffffff;
        }
        
        .intro-text {
            font-size: 16px;
            color: #374151;
            margin-bottom: 30px;
            padding: 20px;
            background: #F8FAFC;
            border-left: 4px solid #1e40af;
            border-radius: 0 8px 8px 0;
        }
        
        .message-box {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .field-group {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .field-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .field-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .field-value {
            color: #1f2937;
            font-size: 16px;
            line-height: 1.6;
        }
        
        /* Vervolg van de styling */
        .quote-box {
            margin: 30px 0;
            padding: 20px;
            background: #F8FAFC;
            border-radius: 8px;
            position: relative;
        }
        
        .quote-box::before {
            content: '"';
            position: absolute;
            top: -20px;
            left: 20px;
            font-size: 60px;
            color: #1e40af;
            opacity: 0.1;
            font-family: Georgia, serif;
        }
        
        /* Call-to-action styling */
        .cta-section {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border-radius: 12px;
            text-align: center;
            color: #ffffff;
        }
        
        .cta-text {
            font-size: 16px;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        /* Footer styling */
        .footer {
            background: #1f2937;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        
        .footer-content {
            max-width: 400px;
            margin: 0 auto;
        }
        
        .footer-logo {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        
        .footer-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }
        
        .footer-links {
            margin-bottom: 20px;
        }
        
        .footer-link {
            color: #ffffff;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        
        .footer-social {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .social-icon {
            display: inline-block;
            width: 32px;
            height: 32px;
            margin: 0 8px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            line-height: 32px;
            font-size: 14px;
        }
        
        /* Responsive design */
        @media screen and (max-width: 650px) {
            .email-container {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .cta-section {
                margin: 20px -20px;
                border-radius: 0;
            }
        }
    </style>
</head>
<body style="background-color: #f3f4f6; margin: 0; padding: 20px;">
    <div class="email-container">
        <div class="header">
            <div class="political-decoration left"></div>
            <h1>Nieuw Bericht</h1>
            <div class="header-subtitle">via PolitiekPraat Contactformulier</div>
            <div class="political-decoration right"></div>
        </div>
        
        <div class="content">
            <div class="intro-text">
                Er is een nieuw bericht ontvangen via het contactformulier op PolitiekPraat. 
                Hieronder vindt u de details van het bericht.
            </div>
            
            <div class="message-box">
                <div class="field-group">
                    <div class="field-label">Naam</div>
                    <div class="field-value">{$name}</div>
                </div>
                
                <div class="field-group">
                    <div class="field-label">E-mailadres</div>
                    <div class="field-value">{$email}</div>
                </div>
                
                <div class="field-group">
                    <div class="field-label">Onderwerp</div>
                    <div class="field-value">{$subject}</div>
                </div>
                
                <div class="field-group">
                    <div class="field-label">Bericht</div>
                    <div class="field-value quote-box" style="white-space: pre-wrap;">{$message}</div>
                </div>
            </div>
            
            <div class="cta-section">
                <div class="cta-text">
                    Wilt u reageren op dit bericht?
                </div>
                <div>
                    U kunt direct antwoorden op deze e-mail.
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <div class="footer-logo">PolitiekPraat</div>
                <div class="footer-text">
                    Samen in gesprek over de Nederlandse politiek
                </div>
                <div class="footer-links">
                    <a href="<?php echo URLROOT; ?>" class="footer-link">Website</a>
                    <a href="<?php echo URLROOT; ?>/contact" class="footer-link">Contact</a>
                </div>
                <div class="footer-social">
                    <a href="#" class="social-icon">FB</a>
                    <a href="#" class="social-icon">TW</a>
                    <a href="#" class="social-icon">LI</a>
                </div>
                <div style="margin-top: 20px; font-size: 12px; color: rgba(255, 255, 255, 0.5);">
                    &copy; <?php echo date('Y'); ?> PolitiekPraat. Alle rechten voorbehouden.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;

            $mail->Body = $emailTemplate;
            
            // Plain text versie
            $mail->AltBody = "Nieuw contactformulier bericht\n\n" .
                            "Naam: {$name}\n" .
                            "E-mail: {$email}\n" .
                            "Onderwerp: {$subject}\n\n" .
                            "Bericht:\n{$message}\n\n" .
                            "--- \n" .
                            "Dit bericht is verzonden via het contactformulier op PolitiekPraat.";

            $mail->send();
            $success = 'Je bericht is verzonden! We nemen zo snel mogelijk contact met je op.';
        } catch (Exception $e) {
            $error = "Er is een fout opgetreden bij het verzenden van je bericht. Probeer het later opnieuw. Foutmelding: {$mail->ErrorInfo}";
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="relative min-h-screen py-16">
    <!-- Enhanced background with subtle pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%231e40af\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); background-size: 100px 100px;">
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-600 via-white to-blue-700 opacity-60"></div>
        <div class="absolute top-2 left-0 w-full h-2 bg-primary opacity-30"></div>
    </div>

    <div class="container mx-auto px-4 relative">
        <div class="max-w-4xl mx-auto">
            <!-- Enhanced header section -->
            <div class="mb-16 text-center relative">
                <span class="inline-block px-4 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4 animate-fadeIn">Neem contact op</span>
                <h1 class="text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-primary mb-4">Contact</h1>
                <div class="h-1 w-20 bg-primary mx-auto mb-8 rounded-full"></div>
                
                <div class="max-w-2xl mx-auto mt-8 space-y-6">
                    <p class="text-xl text-gray-700 leading-relaxed">
                        Heb je een vraag of wil je iets met ons delen? We horen graag van je!
                    </p>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Misschien heb je een tof idee voor de website, zie je iets wat beter kan, of wil je gewoon je mening delen over de Nederlandse politiek - we staan open voor alles!
                    </p>
                    <div class="flex items-center justify-center mt-8 text-gray-500">
                        <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-base">Je hoort meestal binnen 2 werkdagen van ons terug</p>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced form card with improved styling -->
            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 relative overflow-hidden border border-gray-100 backdrop-blur-sm transform transition-all duration-300 hover:shadow-2xl">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-primary/5 to-primary/10 rounded-bl-full -z-10"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-primary/5 to-primary/10 rounded-tr-full -z-10"></div>
                
                <?php if ($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-5 rounded-lg mb-8 shadow-sm animate-fadeIn">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm md:text-base"><?php echo $error; ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-5 rounded-lg mb-8 shadow-sm animate-fadeIn">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm md:text-base"><?php echo $success; ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo URLROOT; ?>/contact" class="space-y-8">
                    <div class="grid md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="relative group">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2 transition-all duration-200 group-focus-within:text-primary">Naam</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="block w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                       required>
                                <div class="absolute bottom-0 left-0 h-0.5 bg-primary transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left"></div>
                            </div>
                        </div>

                        <div class="relative group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2 transition-all duration-200 group-focus-within:text-primary">E-mailadres</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="block w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                       required>
                                <div class="absolute bottom-0 left-0 h-0.5 bg-primary transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left"></div>
                            </div>
                        </div>
                    </div>

                    <div class="relative group">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2 transition-all duration-200 group-focus-within:text-primary">Onderwerp</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   name="subject" 
                                   id="subject" 
                                   class="block w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 bg-gray-50 focus:bg-white"
                                   value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>"
                                   required>
                            <div class="absolute bottom-0 left-0 h-0.5 bg-primary transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left"></div>
                        </div>
                    </div>

                    <div class="relative group">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2 transition-all duration-200 group-focus-within:text-primary">Bericht</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <textarea name="message" 
                                      id="message" 
                                      rows="6"
                                      class="block w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 bg-gray-50 focus:bg-white resize-none"
                                      required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                            <div class="absolute bottom-0 left-0 h-0.5 bg-primary transform scale-x-0 group-focus-within:scale-x-100 transition-transform duration-300 origin-left"></div>
                        </div>
                    </div>

                    <div class="relative pt-4">
                        <button type="submit" 
                                class="w-full group bg-primary text-white font-semibold py-4 px-6 rounded-lg hover:bg-primary-dark transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 active:scale-98 shadow-md hover:shadow-lg overflow-hidden">
                            <div class="absolute inset-0 w-3/12 bg-white/10 skew-x-[-20deg] transform -translate-x-full group-hover:animate-shimmer"></div>
                            <div class="flex items-center justify-center space-x-2">
                                <span>Verstuur Bericht</span>
                                <svg class="w-5 h-5 transition-transform duration-200 transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </div>
                        </button>
                    </div>

                    <div class="text-center mt-6 text-gray-500 text-sm">
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-primary/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>Je gegevens worden vertrouwelijk behandeld</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes shimmer {
        100% { transform: translateX(250%); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .animate-shimmer {
        animation: shimmer 1.5s infinite;
    }
    
    .active\:scale-98:active {
        transform: scale(0.98);
    }
</style>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 