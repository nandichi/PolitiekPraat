<?php
// Privacy Controller
// Handles privacy-related pages like Privacy Policy, Cookie Policy, Accessibility Statement

class PrivacyController {
    
    private array $data;
    
    public function __construct() {
        // Set common data
        $this->data = [
            'site_name' => SITENAME,
            'site_url' => URLROOT,
            'contact_email' => 'privacy@politiekpraat.nl',
            'last_updated' => date('d F Y')
        ];
    }
    
    public function privacyPolicy() {
        $data = array_merge($this->data, [
            'page_title' => 'Privacy Policy',
            'page_description' => 'Privacy Policy van PolitiekPraat - Hoe wij uw persoonlijke gegevens verzamelen, gebruiken en beschermen conform de AVG/GDPR.',
            'page_keywords' => 'privacy policy, AVG, GDPR, persoonlijke gegevens, cookies, PolitiekPraat'
        ]);
        
        require_once 'views/privacy/privacy-policy.php';
    }
    
    public function cookiePolicy() {
        $data = array_merge($this->data, [
            'page_title' => 'Cookie Policy',
            'page_description' => 'Cookie Policy van PolitiekPraat - Hoe wij cookies gebruiken en hoe u uw voorkeuren kunt beheren.',
            'page_keywords' => 'cookie policy, cookies, tracking, privacy, voorkeuren'
        ]);
        
        require_once 'views/privacy/cookie-policy.php';
    }
    
    public function accessibilityStatement() {
        $data = array_merge($this->data, [
            'page_title' => 'Toegankelijkheidsverklaring',
            'page_description' => 'Toegankelijkheidsverklaring van PolitiekPraat conform de European Accessibility Act (EAA) en WCAG 2.1.',
            'page_keywords' => 'toegankelijkheid, accessibility, EAA, WCAG, inclusief design'
        ]);
        
        require_once 'views/privacy/accessibility-statement.php';
    }
    
    public function termsOfService() {
        $data = array_merge($this->data, [
            'page_title' => 'Gebruiksvoorwaarden',
            'page_description' => 'Gebruiksvoorwaarden van PolitiekPraat - De regels en voorwaarden voor het gebruik van onze website.',
            'page_keywords' => 'gebruiksvoorwaarden, terms of service, regels, voorwaarden'
        ]);
        
        require_once 'views/privacy/terms-of-service.php';
    }
}

// Handle routing
$controller = new PrivacyController();
$page = $_GET['page'] ?? 'privacy-policy';

switch ($page) {
    case 'privacy-policy':
        $controller->privacyPolicy();
        break;
    case 'cookie-policy':
        $controller->cookiePolicy();
        break;
    case 'accessibility':
    case 'toegankelijkheid':
        $controller->accessibilityStatement();
        break;
    case 'terms':
    case 'gebruiksvoorwaarden':
        $controller->termsOfService();
        break;
    default:
        $controller->privacyPolicy();
        break;
} 