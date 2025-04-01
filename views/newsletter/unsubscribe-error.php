<?php
$title = "Uitschrijving Mislukt - PolitiekPraat";
$description = "Er is een fout opgetreden bij het uitschrijven van de nieuwsbrief.";
$data = [
    'title' => $title,
    'description' => $description
];
require_once BASE_PATH . '/views/templates/header.php';
?>

<div class="container max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-md p-8 mb-6">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Uitschrijving mislukt</h1>
            <p class="text-gray-600">Er is een probleem opgetreden bij het uitschrijven van de nieuwsbrief.</p>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <p class="text-gray-700 mb-4">De link is mogelijk ongeldig of verlopen. Probeer het later nog eens.</p>
            <p class="text-gray-700 mb-6">Je kunt ook contact opnemen met ons via de contactpagina voor ondersteuning.</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-primary/90 transition-colors">
                    Terug naar de homepage
                </a>
                <a href="/contact" class="inline-block bg-white border border-primary text-primary px-6 py-3 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Contact opnemen
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/templates/footer.php'; ?> 