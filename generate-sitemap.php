<?php
// Include necessary files
require_once 'includes/config.php';
require_once 'includes/Database.php';

// Set header for XML output
header('Content-Type: application/xml; charset=utf-8');

// Website domain
$domain = 'https://politiekpraat.nl';

// Create a new database instance
$db = new Database();

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Add static pages
$static_pages = [
    ['url' => '', 'changefreq' => 'daily', 'priority' => '1.0'],
    ['url' => 'home', 'changefreq' => 'daily', 'priority' => '1.0'],
    ['url' => 'blogs', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['url' => 'forum', 'changefreq' => 'daily', 'priority' => '0.8'],
    ['url' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.5'],
    ['url' => 'themas', 'changefreq' => 'weekly', 'priority' => '0.7'],
    ['url' => 'over-mij', 'changefreq' => 'monthly', 'priority' => '0.3'],
    ['url' => 'nieuws', 'changefreq' => 'daily', 'priority' => '0.8'],
    ['url' => 'stemwijzer', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['url' => 'login', 'changefreq' => 'monthly', 'priority' => '0.5'],
    ['url' => 'register', 'changefreq' => 'monthly', 'priority' => '0.5'],
];

foreach ($static_pages as $page) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/" . htmlspecialchars($page['url']) . "</loc>\n";
    echo "    <changefreq>" . $page['changefreq'] . "</changefreq>\n";
    echo "    <priority>" . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}

// Add blog posts
$query = "SELECT slug, updated_at FROM blog_posts WHERE status = 'published' ORDER BY updated_at DESC";
$blogs = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($blogs as $blog) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/blogs/view/" . htmlspecialchars($blog['slug']) . "</loc>\n";
    echo "    <lastmod>" . date('c', strtotime($blog['updated_at'])) . "</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.6</priority>\n";
    echo "  </url>\n";
}

// Add forum topics
$query = "SELECT slug, updated_at FROM forum_topics WHERE status = 'active' ORDER BY updated_at DESC";
$topics = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($topics as $topic) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/forum/topic/" . htmlspecialchars($topic['slug']) . "</loc>\n";
    echo "    <lastmod>" . date('c', strtotime($topic['updated_at'])) . "</lastmod>\n";
    echo "    <changefreq>daily</changefreq>\n";
    echo "    <priority>0.6</priority>\n";
    echo "  </url>\n";
}

// Add themas
$query = "SELECT slug FROM themas ORDER BY id";
$themas = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($themas as $thema) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/thema/" . htmlspecialchars($thema['slug']) . "</loc>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.6</priority>\n";
    echo "  </url>\n";
}

// Add nieuws items
$query = "SELECT slug, published_at FROM nieuws_items WHERE status = 'published' ORDER BY published_at DESC";
$news = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($news as $item) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/nieuws/artikel/" . htmlspecialchars($item['slug']) . "</loc>\n";
    echo "    <lastmod>" . date('c', strtotime($item['published_at'])) . "</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.6</priority>\n";
    echo "  </url>\n";
}

// Close XML
echo '</urlset>';

// Also save the sitemap to a file
$sitemap_content = ob_get_contents();
file_put_contents('sitemap.xml', $sitemap_content); 