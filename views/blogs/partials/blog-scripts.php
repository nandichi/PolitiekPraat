<?php
require_once BASE_PATH . '/includes/auth_csrf.php';
$partyData = [];
foreach ($dbParties as $key => $party) {
    $partyData[$key] = [
        'name' => $party['name'],
        'leader' => $party['leader'],
        'logo' => $party['logo'],
        'leaderPhoto' => $party['leader_photo'] ?? ''
    ];
}

$formattedPublishedDate = (new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(strtotime($blog->published_at));
$blogImageUrl = $blog->image_path ? getBlogImageUrl($blog->image_path) : null;
$csrfToken = auth_ensure_csrf_token();
?>
<script>
window.blogViewData = <?= json_encode([
    'currentBlogSlug' => $blog->slug,
    'urlRoot' => URLROOT,
    'baseUrl' => URLROOT,
    'title' => $blog->title,
    'author' => $blog->author_name,
    'formattedDate' => $formattedPublishedDate,
    'imageUrl' => $blogImageUrl,
    'audioEnabled' => !empty($blog->audio_path) || !empty($blog->audio_url),
    'partyData' => $partyData,
    'csrfToken' => $csrfToken,
    'blogShareData' => [
        'title' => $blog->title,
        'author' => $blog->author_name,
        'date' => $formattedPublishedDate,
        'imageUrl' => $blogImageUrl,
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
</script>
<script defer src="<?= URLROOT; ?>/public/js/blog-interactive.js"></script>
