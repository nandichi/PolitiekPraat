<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
require_once '../vendor/erusev/parsedown/Parsedown.php';
require_once '../includes/BlogController.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$db = new Database();
$blogController = new BlogController();
$message = '';
$error = '';

// Verwerk form submission voor poll aanpassingen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_poll'])) {
    $pollId = filter_var($_POST['poll_id'], FILTER_VALIDATE_INT);
    $optionAVotes = filter_var($_POST['option_a_votes'], FILTER_VALIDATE_INT);
    $optionBVotes = filter_var($_POST['option_b_votes'], FILTER_VALIDATE_INT);
    
    if ($pollId && $optionAVotes !== false && $optionBVotes !== false) {
        try {
            $totalVotes = $optionAVotes + $optionBVotes;
            
            $db->query("UPDATE blog_polls SET 
                       option_a_votes = :option_a_votes, 
                       option_b_votes = :option_b_votes, 
                       total_votes = :total_votes,
                       updated_at = NOW()
                       WHERE id = :poll_id");
            
            $db->bind(':option_a_votes', $optionAVotes);
            $db->bind(':option_b_votes', $optionBVotes);
            $db->bind(':total_votes', $totalVotes);
            $db->bind(':poll_id', $pollId);
            
            if ($db->execute()) {
                $message = "Poll stemmen succesvol bijgewerkt!";
            } else {
                $error = "Fout bij het bijwerken van de poll.";
            }
        } catch (Exception $e) {
            $error = "Database fout: " . $e->getMessage();
        }
    } else {
        $error = "Ongeldige invoer. Controleer alle velden.";
    }
}

// Haal alle polls op met blog informatie
try {
    $db->query("SELECT 
                p.id as poll_id,
                p.question,
                p.option_a,
                p.option_b,
                p.option_a_votes,
                p.option_b_votes,
                p.total_votes,
                p.created_at as poll_created,
                b.id as blog_id,
                b.title as blog_title,
                b.slug as blog_slug,
                b.published_at,
                u.username as author_name
                FROM blog_polls p
                JOIN blogs b ON p.blog_id = b.id
                JOIN users u ON b.author_id = u.id
                ORDER BY p.created_at DESC");
    
    $polls = $db->resultSet();
} catch (Exception $e) {
    $polls = [];
    $error = "Fout bij het ophalen van polls: " . $e->getMessage();
}

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1);
}

.stat-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    backdrop-filter: blur(10px);
}

.poll-progress {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.poll-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 4px;
    transition: width 0.3s ease;
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Polls Beheer</h1>
                    <p class="text-blue-100 text-base md:text-lg">Beheer stemcijfers voor blog polls</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30 text-center">
                        ← Terug naar Dashboard
                    </a>
                    <a href="../blogs" target="_blank"
                       class="bg-white text-indigo-600 px-4 md:px-6 py-2 md:py-3 rounded-xl hover:bg-blue-50 transition-all duration-300 font-semibold text-center">
                        Bekijk Blogs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Status berichten -->
        <?php if ($message): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Polls</p>
                        <p class="text-3xl font-bold text-gray-800"><?= count($polls) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Totaal Stemmen</p>
                        <p class="text-3xl font-bold text-gray-800"><?= array_sum(array_column($polls, 'total_votes')) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6 border border-white/50 shadow-xl card-hover">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Gemiddeld per Poll</p>
                        <p class="text-3xl font-bold text-gray-800"><?= count($polls) > 0 ? round(array_sum(array_column($polls, 'total_votes')) / count($polls)) : 0 ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Polls beheer -->
        <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Alle Blog Polls</h2>
                <p class="text-sm text-gray-600 mt-1">Pas stemcijfers aan voor elke poll</p>
            </div>
            
            <div class="p-6">
                <?php if (empty($polls)): ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Geen polls gevonden</h3>
                        <p class="text-gray-600 mb-4">Er zijn nog geen polls aangemaakt in blogs.</p>
                        <a href="../blogs/create" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Maak eerste blog met poll
                        </a>
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($polls as $poll): ?>
                            <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                                <!-- Blog informatie header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                            <a href="../blogs/<?= $poll->blog_slug ?>" target="_blank" class="hover:text-indigo-600 transition-colors">
                                                <?= htmlspecialchars($poll->blog_title) ?>
                                            </a>
                                        </h3>
                                        <div class="flex items-center space-x-3 text-sm text-gray-500">
                                            <span>Door: <?= htmlspecialchars($poll->author_name) ?></span>
                                            <span>•</span>
                                            <span>Gepubliceerd: <?= formatDate($poll->published_at) ?></span>
                                            <span>•</span>
                                            <span>Poll ID: <?= $poll->poll_id ?></span>
                                        </div>
                                    </div>
                                    <a href="../blogs/<?= $poll->blog_slug ?>" target="_blank" 
                                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        Bekijk blog →
                                    </a>
                                </div>

                                <!-- Poll vraag -->
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-800 mb-2">Poll vraag:</h4>
                                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg"><?= htmlspecialchars($poll->question) ?></p>
                                </div>

                                <!-- Huidige resultaten -->
                                <div class="mb-6">
                                    <h4 class="font-medium text-gray-800 mb-3">Huidige resultaten:</h4>
                                    <div class="space-y-3">
                                        <!-- Optie A -->
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($poll->option_a) ?></span>
                                                <span class="text-sm text-gray-600">
                                                    <?= $poll->option_a_votes ?> stemmen 
                                                    (<?= $poll->total_votes > 0 ? round(($poll->option_a_votes / $poll->total_votes) * 100, 1) : 0 ?>%)
                                                </span>
                                            </div>
                                            <div class="poll-progress">
                                                <div class="poll-progress-bar" style="width: <?= $poll->total_votes > 0 ? ($poll->option_a_votes / $poll->total_votes) * 100 : 0 ?>%"></div>
                                            </div>
                                        </div>

                                        <!-- Optie B -->
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($poll->option_b) ?></span>
                                                <span class="text-sm text-gray-600">
                                                    <?= $poll->option_b_votes ?> stemmen 
                                                    (<?= $poll->total_votes > 0 ? round(($poll->option_b_votes / $poll->total_votes) * 100, 1) : 0 ?>%)
                                                </span>
                                            </div>
                                            <div class="poll-progress">
                                                <div class="poll-progress-bar" style="width: <?= $poll->total_votes > 0 ? ($poll->option_b_votes / $poll->total_votes) * 100 : 0 ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500">
                                        Totaal: <?= $poll->total_votes ?> stemmen
                                    </div>
                                </div>

                                <!-- Edit form -->
                                <form method="POST" class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-800 mb-3">Stemcijfers aanpassen:</h4>
                                    <input type="hidden" name="poll_id" value="<?= $poll->poll_id ?>">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <?= htmlspecialchars($poll->option_a) ?>
                                            </label>
                                            <input type="number" 
                                                   name="option_a_votes" 
                                                   value="<?= $poll->option_a_votes ?>" 
                                                   min="0" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <?= htmlspecialchars($poll->option_b) ?>
                                            </label>
                                            <input type="number" 
                                                   name="option_b_votes" 
                                                   value="<?= $poll->option_b_votes ?>" 
                                                   min="0" 
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        
                                        <div class="flex items-end">
                                            <button type="submit" 
                                                    name="update_poll"
                                                    class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
// Add animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('.poll-progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
    
    // Animate counters
    const counters = document.querySelectorAll('.text-3xl.font-bold');
    counters.forEach(counter => {
        const target = parseInt(counter.textContent);
        let current = 0;
        const increment = target / 30;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 50);
    });
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 