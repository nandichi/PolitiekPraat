<?php
// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/login');
    exit;
}

// Haal categorieën op voor het formulier
$categoryController = new CategoryController();
$categories = $categoryController->getAll();

$error = '';
$success = '';

if (empty($_SESSION['blog_create_csrf_token']) || !is_string($_SESSION['blog_create_csrf_token'])) {
    $_SESSION['blog_create_csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token = filter_input(INPUT_POST, 'csrf_token', FILTER_UNSAFE_RAW);

    if (!is_string($csrf_token) || !hash_equals($_SESSION['blog_create_csrf_token'], $csrf_token)) {
        $error = 'Je sessie is verlopen of ongeldig. Ververs de pagina en probeer opnieuw.';
    }

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $summary = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    
    // Validatie
    if (empty($error) && (empty($title) || empty($summary) || empty($content))) {
        $error = 'Vul alle velden in';
    }

    if (empty($error)) {
        $db = new Database();
        
        // Maak een slug van de titel
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Controleer of slug al bestaat
        $db->query("SELECT id FROM blogs WHERE slug = :slug");
        $db->bind(':slug', $slug);
        
        if ($db->single()) {
            // Als slug bestaat, voeg timestamp toe
            $slug .= '-' . time();
        }
        
        // Upload afbeelding als die is meegestuurd
        $image_path = null;
        if (isset($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $upload_dir = BASE_PATH . '/public/uploads/blogs/images';
            $relative_upload_dir = 'public/uploads/blogs/images';
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
            $max_image_size_bytes = 5 * 1024 * 1024; // 5 MB

            $upload_result = store_uploaded_media(
                $_FILES['image'],
                $upload_dir,
                $relative_upload_dir,
                $allowed_extensions,
                $allowed_mimes,
                $max_image_size_bytes
            );

            if (!$upload_result['ok']) {
                $upload_errors = [
                    'upload_error' => 'Uploaden van de afbeelding is mislukt. Probeer het opnieuw.',
                    'directory_error' => 'Uploadmap is niet beschikbaar. Neem contact op met een beheerder.',
                    'invalid_extension' => 'Alleen JPG, JPEG, PNG en GIF bestanden zijn toegestaan.',
                    'invalid_mime' => 'Ongeldig bestandstype. Upload een echte afbeelding (JPG, PNG of GIF).',
                    'file_too_large' => 'Afbeelding is te groot. Maximum is 5 MB.',
                    'move_failed' => 'Opslaan van de afbeelding is mislukt. Probeer het opnieuw.',
                ];

                $error_key = $upload_result['error'] ?? 'upload_error';
                $error = $upload_errors[$error_key] ?? $upload_errors['upload_error'];
            } else {
                $image_path = $upload_result['path'];
            }
        }

        if (empty($error)) {
            // Voeg blog toe
            $db->query("INSERT INTO blogs (title, slug, summary, content, image_path, category_id, author_id) 
                       VALUES (:title, :slug, :summary, :content, :image_path, :category_id, :author_id)");
            $db->bind(':title', $title);
            $db->bind(':slug', $slug);
            $db->bind(':summary', $summary);
            $db->bind(':content', $content);
            $db->bind(':image_path', $image_path);
            $db->bind(':category_id', $category_id);
            $db->bind(':author_id', $_SESSION['user_id']);
            
            if ($db->execute()) {
                $_SESSION['blog_create_csrf_token'] = bin2hex(random_bytes(32));
                header('Location: ' . URLROOT . '/blogs/' . $slug);
                exit;
            } else {
                $error = 'Er is iets misgegaan bij het aanmaken van je blog';
            }
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold mb-6">Nieuwe Blog</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo URLROOT; ?>/blogs/create" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['blog_create_csrf_token']); ?>">

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-bold mb-2">Titel</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"
                       required>
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-bold mb-2">Categorie</label>
                                        <select name="category_id" 
                        id="category_id" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Selecteer een categorie...</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" 
                                <?php echo (isset($category_id) && $category_id == $category->id) ? 'selected' : ''; ?>
                                style="color: <?php echo $category->color; ?>">
                            <?php echo htmlspecialchars($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Kies de categorie die het beste bij je artikel past (optioneel)
                </p>
            </div>

            <div class="mb-4">
                <label for="summary" class="block text-gray-700 font-bold mb-2">Samenvatting</label>
                <textarea name="summary" 
                          id="summary" 
                          rows="3"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          required><?php echo isset($summary) ? htmlspecialchars($summary) : ''; ?></textarea>
            </div>

            <div class="mb-4">
                <label for="content" class="block text-gray-700 font-bold mb-2">Content</label>
                <textarea name="content" 
                          id="content" 
                          rows="10"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
            </div>

            <div class="mb-6">
                <label for="image" class="block text-gray-700 font-bold mb-2">Afbeelding (optioneel)</label>
                <input type="file" 
                       name="image" 
                       id="image" 
                       accept="image/*"
                       class="w-full">
                <p class="text-sm text-gray-500 mt-1">
                    Toegestane formaten: JPG, JPEG, PNG, GIF (max 5 MB)
                </p>
            </div>

            <div class="flex justify-between">
                <a href="<?php echo URLROOT; ?>/blogs" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                    Annuleren
                </a>
                <button type="submit" 
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                    Blog Plaatsen
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 