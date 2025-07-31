<?php
// Include necessary files
require_once 'includes/config.php';
require_once 'includes/Database.php';

// Set header for XML output
header('Content-Type: application/xml; charset=utf-8');

// Website domain
$domain = 'https://politiekpraat.nl';

// Current timestamp for lastmod for static pages
$current_date = date('c');

// Create a new database instance
$db = new Database();

// Start XML output
ob_start(); // Buffer output to save to file later
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

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
    ['url' => 'partijmeter', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['url' => 'partijen', 'changefreq' => 'weekly', 'priority' => '0.7'],
    ['url' => 'politiek-kompas', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['url' => 'login', 'changefreq' => 'monthly', 'priority' => '0.5'],
    ['url' => 'register', 'changefreq' => 'monthly', 'priority' => '0.5'],
    ['url' => 'profile', 'changefreq' => 'weekly', 'priority' => '0.6'],
];

foreach ($static_pages as $page) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/" . htmlspecialchars($page['url']) . "</loc>\n";
    echo "    <lastmod>" . $current_date . "</lastmod>\n";
    echo "    <changefreq>" . $page['changefreq'] . "</changefreq>\n";
    echo "    <priority>" . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}

// Add blog posts with featured images
$query = "SELECT b.slug, b.published_at, b.title, b.image_path as featured_image 
          FROM blogs b 
          ORDER BY b.published_at DESC";
$blogs = $db->query($query)->resultSet();

foreach ($blogs as $blog) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/blogs/" . htmlspecialchars($blog->slug) . "</loc>\n";
    echo "    <lastmod>" . date('c', strtotime($blog->published_at)) . "</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.6</priority>\n";
    
    // Add featured image if available
    if (!empty($blog->featured_image)) {
        echo "    <image:image>\n";
        echo "      <image:loc>" . $domain . "/" . htmlspecialchars($blog->featured_image) . "</image:loc>\n";
        echo "      <image:title>" . htmlspecialchars($blog->title) . "</image:title>\n";
        echo "      <image:caption>" . htmlspecialchars($blog->title) . "</image:caption>\n";
        echo "    </image:image>\n";
    }
    
    echo "  </url>\n";
}

// Add forum topics (commented out - table may not exist)
// $query = "SELECT slug, updated_at FROM forum_topics WHERE status = 'active' ORDER BY updated_at DESC";
// $topics = $db->query($query)->resultSet();

// foreach ($topics as $topic) {
//     echo "  <url>\n";
//     echo "    <loc>" . $domain . "/forum/topic/" . htmlspecialchars($topic->slug) . "</loc>\n";
//     echo "    <lastmod>" . date('c', strtotime($topic->updated_at)) . "</lastmod>\n";
//     echo "    <changefreq>daily</changefreq>\n";
//     echo "    <priority>0.6</priority>\n";
//     echo "  </url>\n";
// }

// Add themas (belangrijkste politieke thema's)
$thema_slugs = [
    'klimaat-en-energie',
    'economie-en-financien',
    'onderwijs',
    'zorg-en-welzijn',
    'migratie-en-asiel',
    'veiligheid-en-justitie',
    'europa',
    'defensie',
    'landbouw-en-natuur',
    'wonen',
    'mobiliteit-en-verkeer',
    'digitalisering'
];

foreach ($thema_slugs as $slug) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/thema/" . htmlspecialchars($slug) . "</loc>\n";
    echo "    <lastmod>" . $current_date . "</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.6</priority>\n";
    echo "  </url>\n";
}

// Add nieuws items with images (commented out - table may not exist)
// $query = "SELECT slug, published_at, title, image FROM nieuws_items WHERE status = 'published' ORDER BY published_at DESC";
// $news = $db->query($query)->resultSet();

// foreach ($news as $item) {
//     echo "  <url>\n";
//     echo "    <loc>" . $domain . "/nieuws/artikel/" . htmlspecialchars($item->slug) . "</loc>\n";
//     echo "    <lastmod>" . date('c', strtotime($item->published_at)) . "</lastmod>\n";
//     echo "    <changefreq>monthly</changefreq>\n";
//     echo "    <priority>0.6</priority>\n";
    
//     // Add news image if available
//     if (!empty($item->image)) {
//         echo "    <image:image>\n";
//         echo "      <image:loc>" . $domain . "/" . htmlspecialchars($item->image) . "</image:loc>\n";
//         echo "      <image:title>" . htmlspecialchars($item->title) . "</image:title>\n";
//         echo "      <image:caption>" . htmlspecialchars($item->title) . "</image:caption>\n";
//         echo "    </image:image>\n";
        
//         // Add news-specific tags
//         echo "    <news:news>\n";
//         echo "      <news:publication>\n";
//         echo "        <news:name>PolitiekPraat</news:name>\n";
//         echo "        <news:language>nl</news:language>\n";
//         echo "      </news:publication>\n";
//         echo "      <news:publication_date>" . date('c', strtotime($item->published_at)) . "</news:publication_date>\n";
//         echo "      <news:title>" . htmlspecialchars($item->title) . "</news:title>\n";
//         echo "    </news:news>\n";
//     }
    
//     echo "  </url>\n";
// }

// Add politieke partijen (hardcoded from PoliticalParties class)
$partijen_slugs = [
    'pvv', 'gl-pvda', 'vvd', 'nsc', 'd66', 'bbb', 'cda', 'sp', 'fvd', 'pvdd', 'volt', 'ja21', 'sgp', 'denk'
];

foreach ($partijen_slugs as $slug) {
    echo "  <url>\n";
    echo "    <loc>" . $domain . "/partijen/" . htmlspecialchars($slug) . "</loc>\n";
    echo "    <lastmod>" . $current_date . "</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.7</priority>\n";
    echo "  </url>\n";
}

// Close XML
echo '</urlset>';

// Get the sitemap content
$sitemap_content = ob_get_contents();
ob_end_clean();

// Output to browser
echo $sitemap_content;

// Save the sitemap to a file
file_put_contents('sitemap.xml', $sitemap_content);

// Ping search engines about the new sitemap
function pingSearchEngines($sitemapUrl) {
    $searchEngines = [
        "https://www.google.com/ping?sitemap=",
        "https://www.bing.com/ping?sitemap="
    ];
    
    foreach ($searchEngines as $engine) {
        $pingUrl = $engine . urlencode($sitemapUrl);
        $ch = curl_init($pingUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}

// Ping search engines with the sitemap URL
pingSearchEngines($domain . '/sitemap.xml'); 