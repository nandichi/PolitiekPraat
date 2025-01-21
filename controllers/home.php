<?php
$db = new Database();

// Haal de laatste 6 blogs op
$db->query("SELECT blogs.*, users.username as author_name 
           FROM blogs 
           JOIN users ON blogs.author_id = users.id 
           ORDER BY published_at DESC 
           LIMIT 6");
$latest_blogs = $db->resultSet();

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
            <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                <div class="text-4xl font-bold text-primary mb-2">1.2K+</div>
                <div class="text-gray-600">Actieve leden</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                <div class="text-4xl font-bold text-primary mb-2">500+</div>
                <div class="text-gray-600">Politieke blogs</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg text-center">
                <div class="text-4xl font-bold text-primary mb-2">2.5K+</div>
                <div class="text-gray-600">Forum discussies</div>
            </div>
        </div>
    </section>

    <!-- Laatste Blogs Section -->
    <section class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Laatste Blogs</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Ontdek de meest recente inzichten en analyses over de Nederlandse politiek
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($latest_blogs as $blog): ?>
                <article class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                    <?php if($blog->image_path): ?>
                        <div class="relative h-48 overflow-hidden">
                            <img src="<?php echo URLROOT; ?>/public/images/<?php echo $blog->image_path; ?>" 
                                 alt="<?php echo $blog->title; ?>"
                                 class="w-full h-full object-cover transform transition-transform duration-500 hover:scale-110">
                        </div>
                    <?php endif; ?>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo substr($blog->author_name, 0, 1); ?>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900"><?php echo $blog->author_name; ?></p>
                                <p class="text-xs text-gray-500">
                                    <?php echo date('d M Y', strtotime($blog->published_at)); ?>
                                </p>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-primary transition-colors">
                            <?php echo $blog->title; ?>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?php echo substr($blog->summary, 0, 150) . '...'; ?>
                        </p>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                5 min leestijd
                            </div>
                            <a href="<?php echo URLROOT; ?>/blogs/view/<?php echo $blog->slug; ?>" 
                               class="inline-flex items-center text-primary font-semibold hover:text-secondary transition-colors">
                                Lees meer
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo URLROOT; ?>/blogs" 
               class="inline-flex items-center px-6 py-3 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary hover:text-white transition-all">
                Bekijk alle blogs
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="bg-gradient-to-br from-primary/10 to-secondary/10 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
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