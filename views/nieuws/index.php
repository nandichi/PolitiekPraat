<main class="bg-gray-50 dark:bg-gray-900">
    <!-- Hero Section -->
    <section class="bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-dark to-black opacity-80"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.04"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        
        <div class="container mx-auto px-6 py-16 md:py-24 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-blue-300">
                    Politiek Neutraal Nieuws
                </span>
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
                Een gebalanceerd overzicht van het laatste nieuws, objectief en overzichtelijk gepresenteerd vanuit diverse politieke hoeken.
            </p>
            
            <!-- Statistieken -->
            <div class="mt-10 max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                <div class="bg-white/5 border border-white/10 rounded-lg p-5 backdrop-blur-sm">
                    <p class="text-sm text-gray-400">Totaal Artikelen</p>
                    <p class="text-2xl font-bold text-white"><?php echo $stats['total_articles']; ?></p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-5 backdrop-blur-sm">
                    <p class="text-sm text-blue-300">Progressieve Artikelen</p>
                    <p class="text-2xl font-bold text-white"><?php echo $stats['progressive_count']; ?></p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-5 backdrop-blur-sm">
                    <p class="text-sm text-secondary-light">Conservatieve Artikelen</p>
                    <p class="text-2xl font-bold text-white"><?php echo $stats['conservative_count']; ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Nieuws Content -->
    <section class="py-12" id="artikelen">
        <div class="container mx-auto px-6">
            <!-- Filter Knoppen -->
            <div class="flex flex-wrap items-center justify-center gap-4 mb-10">
                <a href="?filter=alle#artikelen" 
                   class="px-5 py-2.5 rounded-full text-sm font-medium border-2 transition-all duration-300 flex items-center shadow-sm <?php echo $filter === 'alle' ? 'bg-primary text-white border-primary-dark shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-transparent hover:border-primary hover:text-primary hover:shadow-md'; ?>">
                    <i class="fas fa-globe-europe mr-2"></i> Alle Bronnen
                </a>
                <a href="?filter=progressief#artikelen" 
                   class="px-5 py-2.5 rounded-full text-sm font-medium border-2 transition-all duration-300 flex items-center shadow-sm <?php echo $filter === 'progressief' ? 'bg-primary text-white border-primary-dark shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-transparent hover:border-primary hover:text-primary hover:shadow-md'; ?>">
                    <i class="fas fa-arrow-left mr-2"></i> Progressief
                </a>
                <a href="?filter=conservatief#artikelen" 
                   class="px-5 py-2.5 rounded-full text-sm font-medium border-2 transition-all duration-300 flex items-center shadow-sm <?php echo $filter === 'conservatief' ? 'bg-primary text-white border-primary-dark shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-transparent hover:border-primary hover:text-primary hover:shadow-md'; ?>">
                    <i class="fas fa-arrow-right mr-2"></i> Conservatief
                </a>
                <a href="?clear_cache=1<?php echo !empty($filter) && $filter !== 'alle' ? "&filter=$filter" : ""; ?>#artikelen" 
                   class="px-5 py-2.5 rounded-full text-sm font-medium border-2 transition-all duration-300 flex items-center shadow-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-transparent hover:border-primary hover:text-primary hover:shadow-md group">
                    <i class="fas fa-sync-alt mr-2 group-hover:rotate-90 transition-transform duration-300"></i> Vernieuwen
                </a>
            </div>

            <!-- Artikelen Grid -->
            <?php if (!empty($latest_news)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($latest_news as $article): ?>
                        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-2xl transition-shadow duration-300 flex flex-col overflow-hidden border-l-4 <?php echo $article['bias'] === 'Progressief' ? 'border-primary' : 'border-secondary'; ?>">
                            <div class="p-6 flex-grow flex flex-col">
                                <header class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-semibold uppercase tracking-wider <?php echo $article['bias'] === 'Progressief' ? 'text-primary' : 'text-secondary'; ?>">
                                            <?php echo htmlspecialchars($article['bias']); ?>
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            <?php echo htmlspecialchars($article['source']); ?>
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-3">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </h3>
                                </header>
                                <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-4 flex-grow">
                                    <?php echo htmlspecialchars($article['description']); ?>
                                </p>
                                <footer class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <time class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                            <i class="far fa-clock mr-1.5"></i>
                                            <?php 
                                                // According to a memory from a past conversation, all dates on the site must be in Dutch format.
                                                setlocale(LC_TIME, 'nl_NL.UTF-8', 'Dutch');
                                                $date = new DateTime($article['publishedAt']);
                                                // Use IntlDateFormatter for localized date formatting
                                                $formatter = new IntlDateFormatter('nl_NL', IntlDateFormatter::LONG, IntlDateFormatter::NONE, null, null, 'd MMMM yyyy');
                                                echo $formatter->format($date);
                                            ?>
                                        </time>
                                        <a href="<?php echo htmlspecialchars($article['url']); ?>" target="_blank" class="text-sm font-semibold text-primary hover:underline">
                                            Lees meer <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                                        </a>
                                    </div>
                                </footer>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Geen artikelen -->
                <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-lg shadow-md border-t-4 border-primary">
                    <i class="fas fa-newspaper fa-3x text-primary mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Geen Artikelen Gevonden</h3>
                    <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                        Er zijn momenteel geen artikelen die aan je selectie voldoen. Probeer een andere filter of vernieuw de data.
                    </p>
                    <div class="mt-6">
                        <a href="?clear_cache=1" class="bg-primary text-white font-bold py-2 px-4 rounded-lg hover:bg-primary-dark transition-colors duration-300">
                            <i class="fas fa-sync-alt mr-2"></i> Data Vernieuwen
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
/* Keeping line-clamp utilities for cross-browser support */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php require_once 'views/templates/footer.php'; ?> 