<?php
require_once '../includes/Database.php';
require_once '../includes/NewsAPI.php';
require_once '../includes/OpenDataAPI.php';

$db = new Database();
$newsAPI = new NewsAPI();
$openDataAPI = new OpenDataAPI();

// Haal de laatste 5 blogs op (was 6)
$db->query("SELECT blogs.*, users.username as author_name 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC 
           LIMIT 5");
$latest_blogs = $db->resultSet();

// Haal het laatste nieuws op en limiteer tot 5 items
$latest_news = array_slice($newsAPI->getLatestPoliticalNews(), 0, 5);

// Haal actuele data op
$actuele_themas = $openDataAPI->getActueleThemas();
$debatten = $openDataAPI->getPolitiekeDebatten();
$agenda_items = $openDataAPI->getPolitiekeAgenda();

require_once '../views/templates/header.php';
?>

<main class="bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-primary to-secondary overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="container mx-auto px-4 py-24 relative">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight animate-fade-in">
                    Welkom bij PolitiekPraat
                </h1>
                <p class="text-xl md:text-2xl text-white/90 mb-10 leading-relaxed">
                    Het platform waar jouw stem telt. Ontdek, discussieer en draag bij aan het politieke debat in Nederland.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo URLROOT; ?>/blogs" 
                       class="inline-block bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-opacity-90 transition-all transform hover:scale-105 shadow-lg">
                        Ontdek onze blogs
                    </a>
                    <a href="<?php echo URLROOT; ?>/forum" 
                       class="inline-block bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition-all">
                        Ga naar het forum
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </section>

    <!-- Statistieken Section -->
    <section class="container mx-auto px-4 -mt-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold text-primary mb-2">1.2K+</div>
                <div class="text-gray-600">Actieve leden</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold text-primary mb-2">500+</div>
                <div class="text-gray-600">Politieke blogs</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center transform hover:scale-105 transition-transform">
                <div class="text-4xl font-bold text-primary mb-2">2.5K+</div>
                <div class="text-gray-600">Forum discussies</div>
            </div>
        </div>
    </section>

    <!-- Laatste Nieuws & Blogs Grid -->
    <section class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Laatste Nieuws -->
                <div class="h-full">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Laatste Politiek Nieuws</h2>
                        <p class="text-gray-600">Blijf op de hoogte van de laatste ontwikkelingen</p>
                    </div>
                    <div class="space-y-6 h-full">
                        <?php foreach($latest_news as $news): 
                            // Haal de domeinnaam uit de bron URL voor de favicon
                            $source_domains = [
                                'NOS' => 'nos.nl',
                                'NU.nl' => 'nu.nl',
                                'RTL Nieuws' => 'rtlnieuws.nl',
                                'AD' => 'ad.nl',
                                'Volkskrant' => 'volkskrant.nl',
                                'NRC' => 'nrc.nl',
                                'Trouw' => 'trouw.nl'
                            ];
                            $domain = isset($source_domains[$news['source']]) ? $source_domains[$news['source']] : parse_url($news['url'], PHP_URL_HOST);
                            $favicon_url = "https://www.google.com/s2/favicons?domain=" . $domain . "&sz=32";
                        ?>
                            <article class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:-translate-y-1 flex flex-col h-[220px]">
                                <div class="flex items-center mb-3">
                                    <div class="flex items-center flex-1">
                                        <img src="<?php echo $favicon_url; ?>" 
                                             alt="<?php echo htmlspecialchars($news['source']); ?> logo"
                                             class="w-6 h-6 object-contain mr-2">
                                        <span class="text-sm text-gray-500"><?php echo $news['source']; ?></span>
                                        <span class="mx-2 text-gray-300">•</span>
                                        <span class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($news['publishedAt'])); ?></span>
                                    </div>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 hover:text-primary transition-colors">
                                    <?php echo $news['title']; ?>
                                </h3>
                                <p class="text-gray-600 mb-3 line-clamp-2 flex-grow">
                                    <?php echo $news['description']; ?>
                                </p>
                                <a href="<?php echo $news['url']; ?>" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center text-primary hover:text-secondary font-semibold mt-auto">
                                    Lees meer
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </article>
                        <?php endforeach; ?>
                        <div class="text-center mt-6">
                            <a href="<?php echo URLROOT; ?>/nieuws" 
                               class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-all">
                                Bekijk al het nieuws
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laatste Blogs -->
                <div class="h-full">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Laatste Blogs</h2>
                        <p class="text-gray-600">Lees de meest recente politieke analyses</p>
                    </div>
                    <div class="space-y-6 h-full">
                        <?php foreach($latest_blogs as $blog): ?>
                            <article class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1 h-[220px]">
                                <div class="p-6 flex flex-col h-full">
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            <?php echo substr($blog->author_name, 0, 1); ?>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo $blog->author_name; ?></p>
                                            <p class="text-xs text-gray-500"><?php echo date('d M Y', strtotime($blog->published_at)); ?></p>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1 hover:text-primary transition-colors">
                                        <?php echo $blog->title; ?>
                                    </h3>
                                    <p class="text-gray-600 line-clamp-2 flex-grow">
                                        <?php echo $blog->summary; ?>
                                    </p>
                                    <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                                       class="inline-flex items-center text-primary font-semibold hover:text-secondary transition-colors mt-2">
                                        Lees meer
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                        <div class="text-center mt-6">
                            <a href="<?php echo URLROOT; ?>/blogs" 
                               class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-all">
                                Bekijk alle blogs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Actuele Thema's Grid -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Actuele Thema's</h2>
                <p class="text-gray-600">De belangrijkste politieke onderwerpen van dit moment</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach($actuele_themas as $thema): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6">
                            <div class="text-4xl mb-4"><?php echo $thema['icon']; ?></div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo $thema['title']; ?></h3>
                            <p class="text-gray-600"><?php echo $thema['description']; ?></p>
                            <a href="<?php echo URLROOT; ?>/themas/<?php echo strtolower(str_replace(' ', '-', $thema['title'])); ?>" 
                               class="inline-flex items-center mt-4 text-primary hover:text-secondary font-semibold">
                                Lees meer
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Politieke Debatten -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Politieke Debatten</h2>
                <p class="text-gray-600">Volg de belangrijkste debatten in de Tweede Kamer</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach($debatten as $debat): 
                    $is_upcoming = $debat['status'] === 'Aankomend';
                ?>
                    <div class="bg-gray-50 rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="<?php echo $is_upcoming ? 'bg-primary' : 'bg-gray-500'; ?> text-white px-3 py-1 rounded-full text-sm">
                                    <?php echo $debat['status']; ?>
                                </span>
                                <span class="text-gray-500"><?php echo date('d M Y', strtotime($debat['datum'])); ?></span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo $debat['titel']; ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo $debat['beschrijving']; ?></p>
                            <a href="<?php echo URLROOT; ?>/debatten/<?php echo strtolower(str_replace(' ', '-', $debat['titel'])); ?>"
                               class="inline-flex items-center text-primary hover:text-secondary font-semibold">
                                Meer details
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Politieke Inzichten -->
    <section class="py-16 bg-gradient-to-br from-primary/5 to-secondary/5">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Politieke Inzichten</h2>
                <p class="text-gray-600">Ontdek de laatste trends en ontwikkelingen in de Nederlandse politiek</p>
            </div>
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Actuele Statistieken -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Actuele Statistieken</h3>
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Kamervragen deze week</span>
                                    <span class="text-primary font-bold">127</span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full">
                                    <div class="bg-primary h-2 rounded-full" style="width: 75%"></div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Moties ingediend</span>
                                    <span class="text-primary font-bold">45</span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full">
                                    <div class="bg-primary h-2 rounded-full" style="width: 60%"></div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Wetsvoorstellen behandeld</span>
                                    <span class="text-primary font-bold">12</span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full">
                                    <div class="bg-primary h-2 rounded-full" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trending Onderwerpen -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Trending Onderwerpen</h3>
                            <div class="space-y-4">
                                <div class="flex items-center p-3 bg-primary/5 rounded-lg">
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-gray-900">Klimaatbeleid</p>
                                        <p class="text-sm text-gray-600">247 discussies deze week</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-secondary/5 rounded-lg">
                                    <div class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center text-secondary">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-gray-900">Woningmarkt</p>
                                        <p class="text-sm text-gray-600">189 discussies deze week</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-primary/5 rounded-lg">
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-gray-900">Migratie</p>
                                        <p class="text-sm text-gray-600">178 discussies deze week</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="<?php echo URLROOT; ?>/trends" 
                                   class="inline-flex items-center text-primary hover:text-secondary font-semibold">
                                    Bekijk alle trends
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Politieke Agenda -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-3">Politieke Agenda</h2>
                <p class="text-gray-600">Belangrijke politieke gebeurtenissen en mijlpalen</p>
            </div>
            <div class="max-w-4xl mx-auto">
                <div class="space-y-6">
                    <?php foreach($agenda_items as $item): ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <span class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($item['datum'])); ?></span>
                                        <h3 class="text-xl font-bold text-gray-900"><?php echo $item['titel']; ?></h3>
                                    </div>
                                    <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm">
                                        <?php echo $item['type']; ?>
                                    </span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <?php echo $item['locatie']; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="py-16 bg-gradient-to-br from-primary/10 to-secondary/10">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 p-8 md:p-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            Doe mee aan het debat
                        </h2>
                        <p class="text-gray-600 mb-6">
                            Word lid van onze community en deel jouw perspectief op de Nederlandse politiek. 
                            Start discussies, schrijf blogs en draag bij aan het politieke debat.
                        </p>
                        <div class="space-y-4">
                            <a href="<?php echo URLROOT; ?>/auth/register" 
                               class="block text-center bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                Registreer nu
                            </a>
                            <a href="<?php echo URLROOT; ?>/auth/login" 
                               class="block text-center text-primary hover:text-secondary transition-colors">
                                Al een account? Log in
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/2 bg-gradient-to-br from-primary to-secondary p-8 md:p-12 text-white">
                        <h3 class="text-2xl font-bold mb-6">Waarom meedoen?</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Deel je politieke inzichten
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Neem deel aan discussies
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Schrijf en publiceer blogs
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Verbind met gelijkgestemden
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once '../views/templates/footer.php'; ?> 