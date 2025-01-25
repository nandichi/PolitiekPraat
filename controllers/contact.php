<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Validatie
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Vul alle velden in';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ongeldig e-mailadres';
    } else {
        // Hier kun je de e-mail versturen
        // Voor nu simuleren we dit met een succesbericht
        $success = 'Je bericht is verzonden! We nemen zo snel mogelijk contact met je op.';
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-4xl font-bold mb-8 text-center">Contact</h1>
        
        <div class="bg-white rounded-lg shadow-md p-8">
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo URLROOT; ?>/contact">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Naam</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                           value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                           required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">E-mailadres</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                           required>
                </div>

                <div class="mb-4">
                    <label for="subject" class="block text-gray-700 font-bold mb-2">Onderwerp</label>
                    <input type="text" 
                           name="subject" 
                           id="subject" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                           value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>"
                           required>
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-gray-700 font-bold mb-2">Bericht</label>
                    <textarea name="message" 
                              id="message" 
                              rows="6"
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                              required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                </div>

                <button type="submit" 
                        class="w-full bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-opacity-90 transition">
                    Verstuur Bericht
                </button>
            </form>

            <div class="mt-8 text-center text-gray-600">
                <p class="mb-2">Of neem direct contact op:</p>
                <p>Email: info@politiekpraat.nl</p>
                <p>Tel: 020-1234567</p>
            </div>
        </div>
    </div>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 