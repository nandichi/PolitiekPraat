<?php
function getNewBlogNotificationTemplate($email, $blog_title, $blog_summary, $blog_slug, $blog_image, $author_name) {
    // Base URL of the website
    $baseUrl = "https://" . $_SERVER['HTTP_HOST'];
    $blogUrl = $baseUrl . "/blogs/view/" . $blog_slug;
    $unsubscribeUrl = $baseUrl . "/newsletter/unsubscribe?email=" . urlencode($email);
    
    // Formateer de datum in Nederlands
    $current_date = date("j F Y");
    $dutch_months = [
        'January' => 'januari',
        'February' => 'februari',
        'March' => 'maart',
        'April' => 'april',
        'May' => 'mei',
        'June' => 'juni',
        'July' => 'juli',
        'August' => 'augustus',
        'September' => 'september',
        'October' => 'oktober',
        'November' => 'november',
        'December' => 'december'
    ];
    
    foreach ($dutch_months as $english => $dutch) {
        $current_date = str_replace($english, $dutch, $current_date);
    }
    
    return <<<HTML
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nieuw artikel op PolitiekPraat</title>
    <style>
        /* Reset styles voor email clients */
        body, table, td, div, p, a, li, ul {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        
        /* Container styling */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        
        /* Header styling met moderne uitstraling */
        .header {
            background-color: #1a365d;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 3px solid #e53e3e;
        }
        
        .header h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffffff;
        }
        
        .header-subtitle {
            margin-top: 8px;
            font-size: 16px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.5px;
        }
        
        .date-banner {
            background-color: #f7fafc;
            color: #4a5568;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        /* Content styling */
        .content {
            padding: 40px 30px;
            background-color: #ffffff;
        }
        
        .intro-text {
            font-size: 16px;
            color: #2d3748;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .blog-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: relative;
        }
        
        .blog-content {
            padding: 30px;
        }
        
        .blog-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .blog-meta {
            display: inline-block;
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 5px 10px;
            background-color: #edf2f7;
            border-radius: 4px;
        }
        
        .blog-meta strong {
            font-weight: 600;
        }
        
        .blog-summary {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #edf2f7;
        }
        
        .cta-container {
            text-align: center;
            margin-top: 5px;
        }
        
        .read-more-btn {
            display: inline-block;
            background-color: #e53e3e;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            letter-spacing: 0.3px;
            transition: background-color 0.3s;
        }
        
        .read-more-btn:hover {
            background-color: #c53030;
        }
        
        /* Footer styling */
        .footer {
            background: #2d3748;
            color: #ffffff;
            padding: 35px 20px;
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
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .social-links {
            margin-bottom: 25px;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .footer-links {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            padding: 0 10px;
        }
        
        .footer-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        
        /* Responsive design */
        @media only screen and (max-width: 480px) {
            .content {
                padding: 30px 20px;
            }
            
            .blog-content {
                padding: 20px;
            }
            
            .blog-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body style="background-color: #f7fafc; padding: 30px 10px; margin: 0; width: 100%;">
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>PolitiekPraat</h1>
            <div class="header-subtitle">Nieuw Artikel Gepubliceerd</div>
        </div>
        
        <!-- Date Banner -->
        <div class="date-banner">
            {$current_date}
        </div>

        <!-- Content -->
        <div class="content">
            <div class="intro-text">
                Beste lezer,<br>
                Er is een nieuw artikel gepubliceerd op PolitiekPraat dat wellicht interessant voor u is. Hieronder vindt u een samenvatting en een link om het volledige artikel te lezen.
            </div>

            <div class="blog-card">
                <div class="blog-content">
                    <div class="blog-title">{$blog_title}</div>
                    
                    <div class="blog-meta">
                        <strong>Auteur:</strong> {$author_name}
                    </div>
                    
                    <div class="blog-summary">
                        {$blog_summary}
                    </div>
                    
                    <div class="cta-container">
                        <a href="{$blogUrl}" class="read-more-btn">Lees het volledige artikel</a>
                    </div>
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
                    <a href="{$baseUrl}/contact">Contact</a>
                    <a href="{$baseUrl}/over-ons">Over Ons</a>
                </div>
                <div class="footer-links">
                    <a href="{$baseUrl}" class="footer-link">Website</a>
                    <a href="{$baseUrl}/privacy" class="footer-link">Privacy</a>
                    <a href="{$unsubscribeUrl}" class="footer-link">Uitschrijven</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
} 