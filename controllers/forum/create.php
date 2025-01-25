<?php
// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . URLROOT . '/login');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Validatie
    if (empty($title) || empty($content)) {
        $error = 'Vul alle velden in';
    } else {
        $db = new Database();
        
        // Voeg topic toe
        $db->query("INSERT INTO forum_topics (title, content, author_id) VALUES (:title, :content, :author_id)");
        $db->bind(':title', $title);
        $db->bind(':content', $content);
        $db->bind(':author_id', $_SESSION['user_id']);
        
        if ($db->execute()) {
            $topic_id = $db->lastInsertId();
            header('Location: ' . URLROOT . '/forum/topic/' . $topic_id);
            exit;
        } else {
            $error = 'Er is iets misgegaan bij het aanmaken van je discussie';
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold mb-6">Nieuwe Discussie</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo URLROOT; ?>/forum/create">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-bold mb-2">Onderwerp</label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>"
                       required>
            </div>

            <div class="mb-6">
                <label for="content" class="block text-gray-700 font-bold mb-2">Bericht</label>
                <textarea name="content" 
                          id="content" 
                          rows="10"
                          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
            </div>

            <div class="flex justify-between">
                <a href="<?php echo URLROOT; ?>/forum" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                    Annuleren
                </a>
                <button type="submit" 
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition">
                    Discussie Starten
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 