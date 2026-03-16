<?php 
// Laad PartyModel voor dynamische partij logo's
require_once __DIR__ . '/../../models/PartyModel.php';

// Haal partij data dynamisch op uit de database
$partyModel = new PartyModel();
$dbParties = $partyModel->getAllParties();

// Voeg dynamische meta tags toe voor deze specifieke blog
$pageTitle = htmlspecialchars($blog->title) . ' | PolitiekPraat';
$pageDescription = htmlspecialchars(stripMarkdownForSocialMedia($blog->summary, 160));

// Zorg ervoor dat de afbeelding URL altijd absoluut is voor social media sharing
if ($blog->image_path) {
    // getBlogImageUrl() retourneert al een absolute URL
    $pageImage = getBlogImageUrl($blog->image_path);
} else {
    // Fallback naar default metadata foto
    $pageImage = rtrim(URLROOT, '/') . '/metadata-foto.png';
}

// Voeg deze variabelen toe aan $data voor de header
$data = [
    'title' => $pageTitle,
    'description' => $pageDescription,
    'image' => $pageImage,
    // Voeg extra meta data toe voor betere social sharing
    'og_type' => 'article',
    'og_url' => rtrim(URLROOT, '/') . '/blogs/' . $blog->slug,
    'article_author' => $blog->author_name,
    'article_published_time' => date('c', strtotime($blog->published_at))
];

// Haal comments op (zowel van ingelogde als anonieme gebruikers) met like info
$db = new Database();
$db->query("SELECT comments.*, 
           COALESCE(users.username, comments.anonymous_name) as author_name,
           CASE 
               WHEN comments.user_id IS NOT NULL THEN 'registered'
               ELSE 'anonymous'
           END as author_type,
           comments.is_liked_by_author,
           comments.likes_count
           FROM comments 
           LEFT JOIN users ON comments.user_id = users.id 
           WHERE comments.blog_id = :blog_id 
           ORDER BY comments.created_at DESC");
$db->bind(':blog_id', $blog->id);
$comments = $db->resultSet();

// Comment toevoegen (zowel voor ingelogde als anonieme gebruikers)
$comment_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $anonymous_name = isset($_POST['anonymous_name']) ? filter_input(INPUT_POST, 'anonymous_name', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    
    if (empty($content)) {
        $comment_error = 'Vul een reactie in';
    } elseif (!isset($_SESSION['user_id']) && empty($anonymous_name)) {
        $comment_error = 'Vul je naam in';
    } elseif (!isset($_SESSION['user_id']) && strlen($anonymous_name) > 100) {
        $comment_error = 'Naam mag maximaal 100 karakters zijn';
    } elseif (strlen($content) < 10) {
        $comment_error = 'Reactie moet minimaal 10 karakters zijn';
    } elseif (strlen($content) > 1000) {
        $comment_error = 'Reactie mag maximaal 1000 karakters zijn';
    } else {
        if (isset($_SESSION['user_id'])) {
            // Ingelogde gebruiker
            $db->query("INSERT INTO comments (blog_id, user_id, content) VALUES (:blog_id, :user_id, :content)");
            $db->bind(':blog_id', $blog->id);
            $db->bind(':user_id', $_SESSION['user_id']);
            $db->bind(':content', $content);
        } else {
            // Anonieme gebruiker
            $db->query("INSERT INTO comments (blog_id, anonymous_name, content) VALUES (:blog_id, :anonymous_name, :content)");
            $db->bind(':blog_id', $blog->id);
            $db->bind(':anonymous_name', $anonymous_name);
            $db->bind(':content', $content);
        }
        
        if ($db->execute()) {
            header('Location: ' . URLROOT . '/blogs/' . $blog->slug . '#comments');
            exit;
        } else {
            $comment_error = 'Er is iets misgegaan bij het plaatsen van je reactie';
        }
    }
}

require_once 'views/templates/header.php'; ?>


<?php require __DIR__ . '/partials/blog-layout-main.php'; ?>
<?php require __DIR__ . '/partials/blog-interactive-sections.php'; ?>
<?php require __DIR__ . '/partials/blog-styles.php'; ?>
<?php require __DIR__ . '/partials/blog-scripts.php'; ?>
