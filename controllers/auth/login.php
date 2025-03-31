<?php
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Vul alle velden in';
    } else {
        $db = new Database();
        
        // Zoek gebruiker op email
        $db->query("SELECT * FROM users WHERE email = :email");
        $db->bind(':email', $email);
        $user = $db->single();

        // Controleer of gebruiker bestaat en wachtwoord klopt
        if ($user && password_verify($password, $user->password)) {
            // Start sessie en sla gebruikersgegevens op
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['is_admin'] = $user->is_admin;
            $_SESSION['profile_photo'] = $user->profile_photo;
            
            // Redirect naar homepage
            header('Location: ' . URLROOT);
            exit;
        } else {
            $error = 'Ongeldige inloggegevens';
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';
?>

<main class="container mx-auto px-4 py-20">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Inloggen</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo URLROOT; ?>/login">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">E-mailadres</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                       required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-bold mb-2">Wachtwoord</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       required>
            </div>

            <button type="submit" 
                    class="w-full bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-opacity-90 transition">
                Inloggen
            </button>
        </form>

        <p class="mt-4 text-center">
            Nog geen account? 
            <a href="<?php echo URLROOT; ?>/register" class="text-primary hover:underline">
                Registreer hier
            </a>
        </p>
    </div>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 