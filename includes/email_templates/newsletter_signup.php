<?php
function getNewsletterSignupTemplate($email) {
    $date = date('d-m-Y H:i:s');
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Nieuwsbrief Aanmelding</title>
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
            max-width: 600px;
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
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-link {
            display: inline-block;
            margin: 0 10px;
            color: #ffffff;
            text-decoration: none;
        }
    </style>
</head>
<body style="background-color: #f3f4f6; padding: 20px;">
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>PolitiekPraat</h1>
            <div class="header-subtitle">Nieuwe Nieuwsbrief Aanmelding</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="intro-text">
                Er is een nieuwe aanmelding voor de PolitiekPraat nieuwsbrief. Hieronder vindt u de details van de aanmelding.
            </div>

            <div class="message-box">
                <div class="field-group">
                    <div class="field-label">E-mailadres</div>
                    <div class="field-value">{$email}</div>
                </div>
                
                <div class="field-group">
                    <div class="field-label">Datum Aanmelding</div>
                    <div class="field-value">{$date}</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-logo">PolitiekPraat</div>
                <div class="footer-text">
                    Samen bouwen aan een betrokken democratie
                </div>
                <div class="social-links">
                    <a href="#" class="social-link">Twitter</a>
                    <a href="#" class="social-link">LinkedIn</a>
                    <a href="#" class="social-link">Facebook</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
} 