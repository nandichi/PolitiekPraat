<?php
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validatie
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Vul alle velden in';
    } elseif (strlen($username) < 3) {
        $error = 'Gebruikersnaam moet minimaal 3 karakters bevatten';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ongeldig e-mailadres';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 karakters bevatten';
    } elseif ($password !== $confirm_password) {
        $error = 'Wachtwoorden komen niet overeen';
    } else {
        $db = new Database();
        
        // Controleer of email al bestaat
        $db->query("SELECT id FROM users WHERE email = :email");
        $db->bind(':email', $email);
        
        if ($db->single()) {
            $error = 'Dit e-mailadres is al in gebruik';
        } else {
            // Controleer of username al bestaat
            $db->query("SELECT id FROM users WHERE username = :username");
            $db->bind(':username', $username);
            
            if ($db->single()) {
                $error = 'Deze gebruikersnaam is al in gebruik';
            } else {
                // Hash het wachtwoord
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Voeg gebruiker toe
                $db->query("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $db->bind(':username', $username);
                $db->bind(':email', $email);
                $db->bind(':password', $hashed_password);
                
                if ($db->execute()) {
                    $success = 'Account succesvol aangemaakt! Je kunt nu inloggen.';
                } else {
                    $error = 'Er is iets misgegaan bij het aanmaken van je account';
                }
            }
        }
    }
}

require_once BASE_PATH . '/views/templates/header.php';

?>

<main class="container mx-auto px-4 py-20">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Registreren</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
                <p class="mt-2">
                    <a href="<?php echo URLROOT; ?>/login" class="text-green-700 underline">
                        Ga naar de inlogpagina
                    </a>
                </p>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo URLROOT; ?>/register">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-bold mb-2">Gebruikersnaam</label>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                           value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>"
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
                    <label for="password" class="block text-gray-700 font-bold mb-2">Wachtwoord</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                           required>
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block text-gray-700 font-bold mb-2">
                        Bevestig wachtwoord
                    </label>
                    <input type="password" 
                           name="confirm_password" 
                           id="confirm_password" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-primary"
                           required>
                </div>

                <button type="submit" 
                        class="w-full bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-opacity-90 transition">
                    Registreren
                </button>
            </form>

            <p class="mt-4 text-center">
                Heb je al een account? 
                <a href="<?php echo URLROOT; ?>/login" class="text-primary hover:underline">
                    Log hier in
                </a>
            </p>
        <?php endif; ?>
    </div>
</main>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 