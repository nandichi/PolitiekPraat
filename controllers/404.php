<?php
require_once '../views/templates/header.php';
?>

<main class="container mx-auto px-4 py-20 text-center">
    <h1 class="text-4xl font-bold mb-4">404 - Pagina niet gevonden</h1>
    <p class="text-xl mb-8">De pagina die je zoekt bestaat niet.</p>
    <a href="<?php echo URLROOT; ?>" 
       class="bg-secondary text-white px-8 py-3 rounded-lg hover:bg-opacity-90 transition">
        Terug naar home
    </a>
</main>

<?php require_once '../views/templates/footer.php'; ?> 