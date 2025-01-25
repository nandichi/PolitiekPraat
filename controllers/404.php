<?php
require_once BASE_PATH . '/views/templates/header.php';
?>

<div class="container mx-auto px-4 py-12">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">404 - Pagina niet gevonden</h1>
        <p class="text-gray-600 mb-8">De pagina die je zoekt bestaat niet of is verplaatst.</p>
        <a href="<?php echo URLROOT; ?>" class="text-primary hover:underline">Terug naar home</a>
    </div>
</div>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 