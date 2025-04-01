<?php
function getNewBlogNotificationTemplate($email, $blog_title, $blog_summary, $blog_slug, $blog_image, $author_name) {
    // Base URL of the website
    $baseUrl = "https://" . $_SERVER['HTTP_HOST'];
    $blogUrl = $baseUrl . "/blogs/view/" . $blog_slug;
    $unsubscribeUrl = $baseUrl . "/newsletter/unsubscribe?email=" . urlencode($email);
    
    // Image URL with fallback
    $imageUrl = $blog_image ? $baseUrl . $blog_image : $baseUrl . "/images/default-blog.jpg";
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuw artikel op PolitiekPraat</title>
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
        }
        
        .blog-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .blog-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        
        .blog-content {
            padding: 25px;
        }
        
        .blog-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 12px;
        }
        
        .blog-summary {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .blog-meta {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .read-more-btn {
            display: inline-block;
            background-color: #1a365d;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s;
        }
        
        .read-more-btn:hover {
            background-color: #1e40af;
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
            margin-top: 20px;
        }
        
        .footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: underline;
            font-size: 14px;
        }
    </style>
</head>
<body style="background-color: #f3f4f6; padding: 20px;">
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>PolitiekPraat</h1>
            <div class="header-subtitle">Nieuw Artikel Gepubliceerd</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="intro-text">
                Er is een nieuw artikel gepubliceerd op PolitiekPraat! Lees hieronder een samenvatting en klik om verder te lezen.
            </div>

            <div class="blog-card">
                <img src="{$imageUrl}" alt="{$blog_title}" class="blog-image">
                
                <div class="blog-content">
                    <div class="blog-title">{$blog_title}</div>
                    
                    <div class="blog-meta">
                        Door: {$author_name}
                    </div>
                    
                    <div class="blog-summary">
                        {$blog_summary}
                    </div>
                    
                    <a href="{$blogUrl}" class="read-more-btn">Lees Verder</a>
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
                <div class="footer-links">
                    <a href="{$unsubscribeUrl}" class="footer-link">Uitschrijven</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
} 