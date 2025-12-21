<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start sessie als die nog niet gestart is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = '';
$name = '';
$email = '';
$subject = '';
$message = '';

// Laad mail configuratie
$mail_config = require_once BASE_PATH . '/includes/mail_config.php';

// Controleer voor success/error berichten uit sessie
if (isset($_SESSION['contact_success'])) {
    $success = $_SESSION['contact_success'];
    unset($_SESSION['contact_success']);
}

if (isset($_SESSION['contact_error'])) {
    $error = $_SESSION['contact_error'];
    unset($_SESSION['contact_error']);
    
    // Bij een error, herstel de form velden
    $name = isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '';
    $email = isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : '';
    $subject = isset($_SESSION['form_data']['subject']) ? $_SESSION['form_data']['subject'] : '';
    $message = isset($_SESSION['form_data']['message']) ? $_SESSION['form_data']['message'] : '';
    
    // Verwijder form data uit sessie
    unset($_SESSION['form_data']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Validatie
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['contact_error'] = 'Vul alle velden in';
        $_SESSION['form_data'] = compact('name', 'email', 'subject', 'message');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_error'] = 'Ongeldig e-mailadres';
        $_SESSION['form_data'] = compact('name', 'email', 'subject', 'message');
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
            
            // Extra opties voor betere SSL/TLS compatibiliteit
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

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
            $_SESSION['contact_success'] = 'Je bericht is verzonden! We nemen zo snel mogelijk contact met je op.';
            
            // Redirect naar zelfde pagina om POST-Redirect-GET pattern te implementeren
            header('Location: ' . URLROOT . '/contact');
            exit();
        } catch (Exception $e) {
            $_SESSION['contact_error'] = "Er is een fout opgetreden bij het verzenden van je bericht. Probeer het later opnieuw. Foutmelding: {$mail->ErrorInfo}";
            $_SESSION['form_data'] = compact('name', 'email', 'subject', 'message');
        }
    }
    
    // Redirect na POST om dubbele submissie te voorkomen
    header('Location: ' . URLROOT . '/contact');
    exit();
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 via-primary to-blue-600 overflow-hidden py-20">
        <!-- Decoratieve top lijn -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-secondary via-white to-blue-400"></div>
        
        <!-- Decoratieve elementen -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20"></div>
        </div>

        <div class="container mx-auto px-4 relative">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Contact Icon -->
                <div class="inline-block p-6 rounded-2xl bg-white/10 backdrop-blur-lg mb-8">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">Contact</h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto mb-8">
                    Heb je een vraag, suggestie of wil je je mening delen? 
                    We staan open voor je feedback en ideeën!
                </p>
                
                <!-- Quick Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-white">
                        <svg class="w-8 h-8 mb-4 mx-auto text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-semibold mb-2">Snelle Reactie</h3>
                        <p class="text-gray-300 text-sm">Meestal binnen 2 werkdagen</p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-white">
                        <svg class="w-8 h-8 mb-4 mx-auto text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-semibold mb-2">Betrouwbaar</h3>
                        <p class="text-gray-300 text-sm">Veilige behandeling van je gegevens</p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-white">
                        <svg class="w-8 h-8 mb-4 mx-auto text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4z"></path>
                        </svg>
                        <h3 class="font-semibold mb-2">Open Dialoog</h3>
                        <p class="text-gray-300 text-sm">Constructieve gesprekken</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full h-auto" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="rgb(249 250 251)"/>
            </svg>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-16 relative">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Contact Form Card -->
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden relative">
                    <!-- Decoratieve accent -->
                    <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-primary via-secondary to-accent"></div>
                    
                    <div class="p-8 md:p-12">
                        <!-- Form Header -->
                        <div class="text-center mb-12">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                                    Stuur ons een bericht
                                </span>
                            </h2>
                            <div class="w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full mb-6"></div>
                            <p class="text-gray-600 text-lg">
                                We waarderen je feedback en staan klaar om je te helpen
                            </p>
                        </div>

                        <!-- Success/Error Messages -->
                        <?php if ($error): ?>
                            <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-lg animate-fade-in">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-red-800 font-medium">Er ging iets mis</h3>
                                        <p class="text-red-700 mt-1"><?php echo $error; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="mb-8 p-6 bg-green-50 border-l-4 border-green-500 rounded-lg animate-fade-in">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-green-800 font-medium">Bericht verzonden!</h3>
                                        <p class="text-green-700 mt-1"><?php echo $success; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Contact Form -->
                        <form method="POST" action="<?php echo URLROOT; ?>/contact" class="space-y-8">
                            <!-- Name and Email Row -->
                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Naam <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                                                                 <input type="text" 
                                                name="name" 
                                                id="name" 
                                                class="w-full px-4 py-4 bg-gray-50 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 group-hover:border-gray-400"
                                                value="<?php echo htmlspecialchars($name); ?>"
                                                placeholder="Je volledige naam"
                                                required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        E-mailadres <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                                                                 <input type="email" 
                                                name="email" 
                                                id="email" 
                                                class="w-full px-4 py-4 bg-gray-50 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 group-hover:border-gray-400"
                                                value="<?php echo htmlspecialchars($email); ?>"
                                                placeholder="je@email.nl"
                                                required>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="space-y-2">
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Onderwerp <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                                                         <input type="text" 
                                            name="subject" 
                                            id="subject" 
                                            class="w-full px-4 py-4 bg-gray-50 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 group-hover:border-gray-400"
                                            value="<?php echo htmlspecialchars($subject); ?>"
                                            placeholder="Waar gaat je bericht over?"
                                            required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="space-y-2">
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Bericht <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                                                         <textarea name="message" 
                                              id="message" 
                                              rows="6"
                                              class="w-full px-4 py-4 bg-gray-50 border border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 group-hover:border-gray-400 resize-none"
                                              placeholder="Vertel ons wat je op het hart hebt. We horen graag van je!"
                                              required><?php echo htmlspecialchars($message); ?></textarea>
                                    <div class="absolute top-4 right-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button type="submit" 
                                        class="w-full group relative overflow-hidden bg-gradient-to-r from-primary to-secondary text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-primary-dark to-secondary-dark opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <div class="relative flex items-center justify-center space-x-3">
                                        <span class="text-lg">Verstuur Bericht</span>
                                        <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <!-- Privacy Notice -->
                            <div class="text-center pt-4">
                                <p class="text-sm text-gray-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Je gegevens worden vertrouwelijk behandeld
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Additional Info Section -->
                <div class="mt-16 grid md:grid-cols-3 gap-8">
                    <div class="text-center group">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-100 to-blue-200 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Feedback & Suggesties</h3>
                        <p class="text-gray-600">
                            Heb je ideeën om PolitiekPraat te verbeteren? Deel ze met ons!
                        </p>
                    </div>

                    <div class="text-center group">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-green-100 to-green-200 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Hulp & Ondersteuning</h3>
                        <p class="text-gray-600">
                            Heb je een vraag over de website of ondervind je problemen?
                        </p>
                    </div>

                    <div class="text-center group">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-100 to-purple-200 rounded-2xl mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Politieke Discussie</h3>
                        <p class="text-gray-600">
                            Wil je meepraten over politieke onderwerpen? Start het gesprek!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.5s ease-out forwards;
}

.primary-dark {
    color: #0f172a;
}

.secondary-dark {
    color: #991b1b;
}

/* Custom gradient effects */
.bg-gradient-to-r.from-primary-dark.to-secondary-dark {
    background: linear-gradient(to right, #0f172a, #991b1b);
}

/* Input focus effects */
input:focus, textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 10px 25px -5px rgba(26, 54, 93, 0.1), 0 10px 10px -5px rgba(26, 54, 93, 0.04);
}

/* Hover effects for form elements */
.group:hover input, .group:hover textarea {
    background-color: #ffffff;
    border-color: #9ca3af;
}

/* Button hover animation */
button[type="submit"] {
    position: relative;
    overflow: hidden;
}

button[type="submit"]:hover {
    box-shadow: 0 20px 25px -5px rgba(26, 54, 93, 0.2), 0 10px 10px -5px rgba(26, 54, 93, 0.1);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    input, textarea {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}
</style>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 