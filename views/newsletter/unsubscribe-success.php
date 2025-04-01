<?php
$title = "Uitschrijving Geslaagd - PolitiekPraat";
$description = "Je bent succesvol uitgeschreven van de nieuwsbrief.";
$data = [
    'title' => $title,
    'description' => $description
];
require_once BASE_PATH . '/views/templates/header.php';
?>

<div class="container max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-md p-8 mb-6">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Uitschrijving geslaagd</h1>
            <p class="text-gray-600">Je bent succesvol uitgeschreven van de PolitiekPraat nieuwsbrief.</p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <p class="text-gray-700 mb-4">Je zult geen e-mails meer ontvangen over nieuwe blogs op PolitiekPraat.</p>
            <p class="text-gray-700 mb-6">Wil je toch op de hoogte blijven? Je kunt je altijd weer aanmelden op de homepage.</p>
            
            <a href="/" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                Terug naar de homepage
            </a>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 