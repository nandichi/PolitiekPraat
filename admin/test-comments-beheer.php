<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

// Controleer of gebruiker is ingelogd en admin is
if (!isAdmin()) {
    redirect('login.php');
}

$db = new Database();
$message = '';
$error = '';

// Functie voor realistische timing met onregelmatige intervallen
function generateRealisticTimeOffset($max_hours, $max_seconds = null) {
    if ($max_seconds !== null) {
        // Voor nieuwere blogs: gebruik maximum seconden
        $max_offset = min($max_seconds, $max_hours * 3600);
    } else {
        // Voor oudere blogs: gebruik maximum uren
        $max_offset = $max_hours * 3600;
    }
    
    // Genereer natuurlijke intervallen (niet op hele uren/minuten)
    $patterns = [
        // Snelle reacties (binnen 1 uur)
        ['min' => 300, 'max' => 3600, 'weight' => 30], // 5 min - 1 uur
        // Normale reacties (1-6 uur)  
        ['min' => 3600, 'max' => 21600, 'weight' => 40], // 1-6 uur
        // Late reacties (6+ uur)
        ['min' => 21600, 'max' => $max_offset, 'weight' => 30], // 6 uur - max
    ];
    
    // Kies een patroon op basis van gewichten
    $total_weight = array_sum(array_column($patterns, 'weight'));
    $random = rand(1, $total_weight);
    $current_weight = 0;
    
    foreach ($patterns as $pattern) {
        $current_weight += $pattern['weight'];
        if ($random <= $current_weight && $pattern['max'] <= $max_offset) {
            // Genereer random tijd binnen dit patroon
            $base_time = rand($pattern['min'], min($pattern['max'], $max_offset));
            
            // Voeg onregelmatige minuten en seconden toe
            $extra_minutes = rand(0, 59); // Random minuten
            $extra_seconds = rand(0, 59); // Random seconden
            
            $final_time = $base_time + ($extra_minutes * 60) + $extra_seconds;
            
            // Zorg ervoor dat we binnen de limiet blijven
            return min($final_time, $max_offset);
        }
    }
    
    // Fallback: volledig random binnen de limiet
    $base_time = rand(300, $max_offset); // Minimaal 5 minuten
    $extra_minutes = rand(0, 59);
    $extra_seconds = rand(0, 59);
    
    return min($base_time + ($extra_minutes * 60) + $extra_seconds, $max_offset);
}

// Verwerk formulier voor nieuwe test reactie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $blog_id = filter_input(INPUT_POST, 'blog_id', FILTER_VALIDATE_INT);
    $anonymous_name = filter_input(INPUT_POST, 'anonymous_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
    $delay_hours = filter_input(INPUT_POST, 'delay_hours', FILTER_VALIDATE_INT);
    
    if ($blog_id && $anonymous_name && $content) {
        // Haal blog publicatiedatum op
        $db->query("SELECT published_at FROM blogs WHERE id = :blog_id");
        $db->bind(':blog_id', $blog_id);
        $blog = $db->single();
        
        if ($blog) {
            // Bereken natuurlijke random tijdstip met onregelmatige intervallen
            $blog_time = strtotime($blog->published_at);
            $max_time = $blog_time + ($delay_hours * 3600);
            $current_time = time();
            
            // Maak realistische random timing met onregelmatige intervallen
            if ($current_time > $max_time) {
                // Blog is oud genoeg, genereer random tijd binnen de periode
                $random_offset = generateRealisticTimeOffset($delay_hours);
                $comment_time = date('Y-m-d H:i:s', $blog_time + $random_offset);
            } else {
                // Blog is nieuw, genereer tijd tussen publicatie en nu
                $max_offset = $current_time - $blog_time;
                $random_offset = generateRealisticTimeOffset(floor($max_offset / 3600), $max_offset);
                $comment_time = date('Y-m-d H:i:s', $blog_time + $random_offset);
            }
            
            // Plaats de reactie
            $db->query("INSERT INTO comments (blog_id, anonymous_name, content, created_at) VALUES (:blog_id, :anonymous_name, :content, :created_at)");
            $db->bind(':blog_id', $blog_id);
            $db->bind(':anonymous_name', $anonymous_name);
            $db->bind(':content', $content);
            $db->bind(':created_at', $comment_time);
            
            if ($db->execute()) {
                $time_diff = strtotime($comment_time) - $blog_time;
                $hours = floor($time_diff / 3600);
                $minutes = floor(($time_diff % 3600) / 60);
                $time_description = "";
                
                if ($hours > 0) {
                    $time_description = "{$hours} uur en {$minutes} minuten na blog publicatie";
                } else {
                    $time_description = "{$minutes} minuten na blog publicatie";
                }
                
                $message = "Test reactie succesvol toegevoegd als '{$anonymous_name}' - {$time_description} (" . date('d-m-Y H:i', strtotime($comment_time)) . ")";
            } else {
                $error = "Er ging iets mis bij het toevoegen van de reactie";
            }
        } else {
            $error = "Blog niet gevonden";
        }
    } else {
        $error = "Vul alle velden in";
    }
}

// Verwerk verwijdering van reactie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
    
    if ($comment_id) {
        $db->query("DELETE FROM comments WHERE id = :id AND anonymous_name IS NOT NULL");
        $db->bind(':id', $comment_id);
        
        if ($db->execute()) {
            $message = "Test reactie verwijderd";
        } else {
            $error = "Er ging iets mis bij het verwijderen";
        }
    }
}

// Haal alle blogs op
$db->query("SELECT id, title, slug, published_at FROM blogs ORDER BY published_at DESC");
$blogs = $db->resultSet();

// Haal alle anonieme test reacties op met blog info
$db->query("SELECT c.*, b.title as blog_title, b.slug as blog_slug, b.published_at as blog_published_at
           FROM comments c 
           JOIN blogs b ON c.blog_id = b.id 
           WHERE c.anonymous_name IS NOT NULL 
           ORDER BY c.created_at DESC");
$test_comments = $db->resultSet();

// Voorgedefinieerde namen voor test reacties
$test_names = [
    'Jan van der Berg', 'Marie Jansen', 'Piet de Vries', 'Anna Bakker', 'Tom Visser',
    'Lisa de Jong', 'Ruben Mulder', 'Sophie van Dijk', 'Mark Peters', 'Emma de Wit',
    'Lars van den Berg', 'Julia Koning', 'Mike van der Meer', 'Sara de Boer', 'Tim Smit',
    'Nina van Leeuwen', 'Rick de Graaf', 'Mila Hendriks', 'Bas van der Laan', 'Zoë Dekker'
];

// Voorgedefinieerde reactie templates
$comment_templates = [
    "Interessant artikel! Ik ben het {sentiment} eens met dit standpunt.",
    "Goed geschreven stuk. Dit geeft me veel om over na te denken.",
    "Bedankt voor deze inzichten. Ik had hier nog niet zo over nagedacht.",
    "Mooi artikel, al ben ik het niet helemaal eens met alle punten.",
    "Dit is precies waarom ik deze blog zo waardevol vind. Dankjewel!",
    "Interessante kijk op de zaak. Zou graag meer willen lezen over dit onderwerp.",
    "Goed dat iemand dit eindelijk eens uitspreekt. Volledig mee eens!",
    "Bedankt voor het delen van je mening. Dit geeft stof tot nadenken.",
    "Helder uitgelegd! Dit helpt me om de situatie beter te begrijpen.",
    "Waardevolle inzichten. Ik ga dit zeker delen met anderen."
];

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

.gradient-bg {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
}

.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>

<main class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50">
    
    <!-- Header Section -->
    <div class="gradient-bg">
        <div class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Test Reacties Beheer</h1>
                    <p class="text-blue-100 text-lg">Plaats realistische test reacties op blogs</p>
                </div>
                <div class="flex space-x-4">
                    <a href="stemwijzer-dashboard.php" 
                       class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-300 border border-white/30">
                        ← Terug naar Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-6 relative z-10">
        
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div class="text-green-800"><?= htmlspecialchars($message) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div class="text-red-800"><?= htmlspecialchars($error) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
            
            <!-- Add Test Comment Form -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Nieuwe Test Reactie</h2>
                </div>
                
                <form method="POST" class="p-6 space-y-6">
                    <input type="hidden" name="add_comment" value="1">
                    
                    <!-- Blog Selection -->
                    <div>
                        <label for="blog_id" class="block text-sm font-medium text-gray-700 mb-2">Selecteer Blog</label>
                        <select name="blog_id" id="blog_id" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Kies een blog...</option>
                            <?php foreach ($blogs as $blog): ?>
                                <option value="<?= $blog->id ?>">
                                    <?= htmlspecialchars($blog->title) ?> 
                                    (<?= date('d-m-Y', strtotime($blog->published_at)) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="anonymous_name" class="block text-sm font-medium text-gray-700 mb-2">Naam</label>
                        <div class="flex gap-2">
                            <input type="text" name="anonymous_name" id="anonymous_name" required 
                                   placeholder="Vul een naam in..."
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="generateRandomName()" 
                                    class="px-4 py-3 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl transition-colors">
                                🎲 Random
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Reactie</label>
                        <div class="space-y-2">
                            <textarea name="content" id="content" rows="4" required 
                                      placeholder="Schrijf een realistische reactie..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                            <div class="flex gap-2">
                                <button type="button" onclick="generateTemplate('positive')" 
                                        class="px-3 py-2 bg-green-100 hover:bg-green-200 text-green-800 rounded-lg text-sm transition-colors">
                                    😊 Positief Template
                                </button>
                                <button type="button" onclick="generateTemplate('neutral')" 
                                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg text-sm transition-colors">
                                    😐 Neutraal Template
                                </button>
                                <button type="button" onclick="generateTemplate('critical')" 
                                        class="px-3 py-2 bg-orange-100 hover:bg-orange-200 text-orange-800 rounded-lg text-sm transition-colors">
                                    🤔 Kritisch Template
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Timing -->
                    <div>
                        <label for="delay_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            Maximale tijd na blog publicatie (uren)
                        </label>
                        <select name="delay_hours" id="delay_hours" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="2">2 uur (snel)</option>
                            <option value="6">6 uur</option>
                            <option value="12">12 uur</option>
                            <option value="24" selected>24 uur (1 dag)</option>
                            <option value="48">48 uur (2 dagen)</option>
                            <option value="72">72 uur (3 dagen)</option>
                            <option value="168">168 uur (1 week)</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">
                            Reactie wordt op een natuurlijk, onregelmatig tijdstip geplaatst (bijv. 2u 17min na publicatie)
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-6 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300 font-semibold">
                        🚀 Plaats Test Reactie
                    </button>
                </form>
            </div>

            <!-- Existing Test Comments -->
            <div class="bg-white/90 backdrop-blur-2xl rounded-2xl shadow-xl border border-white/50">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">Bestaande Test Reacties</h2>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium">
                            <?= count($test_comments) ?> reacties
                        </span>
                    </div>
                </div>
                
                <div class="p-6 max-h-96 overflow-y-auto">
                    <?php if (empty($test_comments)): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-gray-600">Nog geen test reacties geplaatst</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($test_comments as $comment): 
                                // Bereken timing na blog publicatie
                                $blog_time = strtotime($comment->blog_published_at);
                                $comment_time = strtotime($comment->created_at);
                                $time_diff = $comment_time - $blog_time;
                                $hours = floor($time_diff / 3600);
                                $minutes = floor(($time_diff % 3600) / 60);
                                
                                if ($hours > 0) {
                                    $timing_display = "{$hours}u {$minutes}min na publicatie";
                                } else {
                                    $timing_display = "{$minutes}min na publicatie";
                                }
                            ?>
                                <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($comment->anonymous_name) ?></h4>
                                            <p class="text-sm text-gray-500">
                                                Op: <a href="<?= URLROOT ?>/blogs/<?= $comment->blog_slug ?>" target="_blank" class="text-blue-600 hover:text-blue-800"><?= htmlspecialchars($comment->blog_title) ?></a>
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                                <span><?= date('d-m-Y H:i', strtotime($comment->created_at)) ?></span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                                    ⏱️ <?= $timing_display ?>
                                                </span>
                                            </div>
                                        </div>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="delete_comment" value="1">
                                            <input type="hidden" name="comment_id" value="<?= $comment->id ?>">
                                            <button type="submit" 
                                                    onclick="return confirm('Weet je zeker dat je deze test reactie wilt verwijderen?')"
                                                    class="text-red-600 hover:text-red-800 p-1 rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-gray-700 text-sm"><?= nl2br(htmlspecialchars($comment->content)) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Voorgedefinieerde namen
const testNames = <?= json_encode($test_names) ?>;

// Comment templates
const commentTemplates = <?= json_encode($comment_templates) ?>;

function generateRandomName() {
    const randomName = testNames[Math.floor(Math.random() * testNames.length)];
    document.getElementById('anonymous_name').value = randomName;
}

function generateTemplate(sentiment) {
    const templates = commentTemplates.slice(); // Copy array
    let template = templates[Math.floor(Math.random() * templates.length)];
    
    // Replace sentiment placeholder
    if (template.includes('{sentiment}')) {
        const sentiments = {
            'positive': 'volledig',
            'neutral': 'grotendeels',
            'critical': 'niet helemaal'
        };
        template = template.replace('{sentiment}', sentiments[sentiment] || 'grotendeels');
    }
    
    document.getElementById('content').value = template;
}

// Auto-fill first blog on page load
document.addEventListener('DOMContentLoaded', function() {
    const blogSelect = document.getElementById('blog_id');
    if (blogSelect.options.length > 1) {
        blogSelect.selectedIndex = 1; // Select first blog
    }
});
</script>

<?php require_once '../views/templates/footer.php'; ?> 