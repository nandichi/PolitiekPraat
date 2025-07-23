<?php
// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/login');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $summary = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Validatie
    if (empty($title) || empty($summary) || empty($content)) {
        $error = 'Vul alle velden in';
    } else {
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
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed)) {
                $image_path = 'blog-' . time() . '.' . $file_ext;
                move_uploaded_file(
                    $_FILES['image']['tmp_name'], 
                    dirname(dirname(__DIR__)) . '/public/images/' . $image_path
                );
            }
        }
        
        // Voeg blog toe
        $db->query("INSERT INTO blogs (title, slug, summary, content, image_path, author_id) 
                   VALUES (:title, :slug, :summary, :content, :image_path, :author_id)");
        $db->bind(':title', $title);
        $db->bind(':slug', $slug);
        $db->bind(':summary', $summary);
        $db->bind(':content', $content);
        $db->bind(':image_path', $image_path);
        $db->bind(':author_id', $_SESSION['user_id']);
        
        if ($db->execute()) {
            $blog_id = $db->lastInsertId();
            
            // Verwerk poll data indien aanwezig
            if (isset($_POST['poll_question']) && !empty(trim($_POST['poll_question']))) {
                $poll_question = filter_input(INPUT_POST, 'poll_question', FILTER_SANITIZE_SPECIAL_CHARS);
                $poll_description = filter_input(INPUT_POST, 'poll_description', FILTER_SANITIZE_SPECIAL_CHARS);
                $poll_type = filter_input(INPUT_POST, 'poll_type', FILTER_SANITIZE_SPECIAL_CHARS);
                $poll_show_results = filter_input(INPUT_POST, 'poll_show_results', FILTER_SANITIZE_SPECIAL_CHARS);
                $poll_options = $_POST['poll_options'] ?? [];
                
                // Filter lege opties eruit
                $poll_options = array_filter($poll_options, function($option) {
                    return !empty(trim($option));
                });
                
                // Controleer of er minimaal 2 opties zijn
                if (count($poll_options) >= 2) {
                    try {
                        // Begin transactie voor poll data
                        $db->beginTransaction();
                        
                        // Voeg poll toe
                        $db->query("INSERT INTO blog_polls (blog_id, question, description, poll_type, show_results) 
                                   VALUES (:blog_id, :question, :description, :poll_type, :show_results)");
                        $db->bind(':blog_id', $blog_id);
                        $db->bind(':question', $poll_question);
                        $db->bind(':description', $poll_description);
                        $db->bind(':poll_type', $poll_type ?: 'single');
                        $db->bind(':show_results', $poll_show_results ?: 'after_vote');
                        $db->execute();
                        
                        $poll_id = $db->lastInsertId();
                        
                        // Voeg poll opties toe
                        foreach ($poll_options as $index => $option_text) {
                            $option_text = filter_var(trim($option_text), FILTER_SANITIZE_SPECIAL_CHARS);
                            if (!empty($option_text)) {
                                $db->query("INSERT INTO poll_options (poll_id, option_text, option_order) 
                                           VALUES (:poll_id, :option_text, :option_order)");
                                $db->bind(':poll_id', $poll_id);
                                $db->bind(':option_text', $option_text);
                                $db->bind(':option_order', $index + 1);
                                $db->execute();
                            }
                        }
                        
                        $db->commit();
                    } catch (Exception $e) {
                        $db->rollback();
                        $error = 'Er is iets misgegaan bij het opslaan van de poll';
                    }
                }
            }
            
            if (empty($error)) {
                header('Location: ' . URLROOT . '/blogs/' . $slug);
                exit;
            }
        } else {
            $error = 'Er is iets misgegaan bij het aanmaken van je blog';
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
                    Toegestane formaten: JPG, JPEG, PNG, GIF
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