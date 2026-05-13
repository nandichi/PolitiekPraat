<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start sessie als die nog niet gestart is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once BASE_PATH . '/includes/auth_csrf.php';

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

$csrf_token = auth_ensure_csrf_token();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!auth_require_csrf_token_from_post()) {
        $_SESSION['contact_error'] = 'Sessie verlopen of ongeldig formulierverzoek. Probeer het opnieuw.';
        header('Location: ' . URLROOT . '/contact');
        exit();
    }

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

<?php
$emailValue = $email ?? '';
$nameValue = $name ?? '';
$subjectValue = $subject ?? '';
$messageValue = $message ?? '';
?>

<?= pp_render_component('section/page-hero', [
    'eyebrow' => 'Contact',
    'title'   => 'Heb je een vraag, suggestie of tip?',
    'lead'    => 'We staan open voor feedback, redactionele suggesties en samenwerking. Vul het formulier in en we proberen binnen twee werkdagen te reageren.',
]) ?>

<section class="pp-container pp-container--narrow py-12 md:py-16">
    <?php if (!empty($error)): ?>
        <div class="border-l-4 border-[color:var(--color-terracotta)] bg-[color:var(--color-terracotta-tint)] text-[color:var(--color-terracotta)] p-4 mb-6 rounded-r">
            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 mt-0.5"><?= pp_icon('alert-circle', 18) ?></span>
                <span class="text-sm leading-relaxed"><?= pp_e($error) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="border-l-4 border-[color:var(--color-olive)] bg-[color:var(--color-olive-tint)] text-[color:var(--color-olive)] p-4 mb-6 rounded-r">
            <div class="flex items-start gap-3">
                <span class="flex-shrink-0 mt-0.5"><?= pp_icon('check-circle', 18) ?></span>
                <div class="text-sm leading-relaxed"><?= pp_e($success) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="keyline-card p-6 md:p-10">
        <form method="POST" action="<?= pp_e(pp_url('/contact')) ?>" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= pp_e($csrf_token) ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="field">
                    <label for="name" class="field__label">Naam</label>
                    <input type="text" id="name" name="name" required autocomplete="name"
                           class="input" placeholder="Je naam"
                           value="<?= pp_e($nameValue) ?>">
                </div>
                <div class="field">
                    <label for="email" class="field__label">E-mailadres</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           class="input" placeholder="jouw@email.nl"
                           value="<?= pp_e($emailValue) ?>">
                </div>
            </div>

            <div class="field">
                <label for="subject" class="field__label">Onderwerp</label>
                <input type="text" id="subject" name="subject" required
                       class="input" placeholder="Waar gaat je bericht over?"
                       value="<?= pp_e($subjectValue) ?>">
            </div>

            <div class="field">
                <label for="message" class="field__label">Bericht</label>
                <textarea id="message" name="message" required rows="7"
                          class="textarea" placeholder="Schrijf hier je bericht..."><?= pp_e($messageValue) ?></textarea>
                <div class="field__hint">We waarderen heldere, constructieve berichten.</div>
            </div>

            <button type="submit" class="btn btn--primary btn--lg">
                <?= pp_icon('send', 16) ?>
                Verstuur bericht
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        <div class="keyline-card p-5 text-center">
            <div class="text-[color:var(--color-hague)] mb-3 flex justify-center"><?= pp_icon('clock', 22) ?></div>
            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Reactietijd</h3>
            <p class="text-sm text-[color:var(--color-ink-muted)]">Meestal binnen twee werkdagen.</p>
        </div>
        <div class="keyline-card p-5 text-center">
            <div class="text-[color:var(--color-hague)] mb-3 flex justify-center"><?= pp_icon('lock', 22) ?></div>
            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Privacy</h3>
            <p class="text-sm text-[color:var(--color-ink-muted)]">Je gegevens blijven bij ons.</p>
        </div>
        <div class="keyline-card p-5 text-center">
            <div class="text-[color:var(--color-hague)] mb-3 flex justify-center"><?= pp_icon('message-circle', 22) ?></div>
            <h3 class="font-display text-base text-[color:var(--color-ink)] mb-1">Open dialoog</h3>
            <p class="text-sm text-[color:var(--color-ink-muted)]">We waarderen kritiek en suggesties.</p>
        </div>
    </div>
</section>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?>
