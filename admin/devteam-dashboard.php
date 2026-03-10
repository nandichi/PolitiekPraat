<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('login.php');
}

$state_file = __DIR__ . '/devteam-state.json';
$state = null;
if (file_exists($state_file)) {
    $state = json_decode(file_get_contents($state_file), true);
}

$github_repo = 'nandichi/PolitiekPraat';

function github_api(string $endpoint): ?array {
    $url = "https://api.github.com/repos/{$GLOBALS['github_repo']}/{$endpoint}";
    $ctx = stream_context_create(['http' => [
        'header' => "User-Agent: PolitiekPraat-DevTeam-Dashboard\r\nAccept: application/vnd.github.v3+json\r\n",
        'timeout' => 8,
    ]]);
    $body = @file_get_contents($url, false, $ctx);
    if ($body === false) return null;
    return json_decode($body, true);
}

$issues = github_api('issues?state=open&per_page=20&sort=created&direction=desc') ?? [];
$closed_issues = github_api('issues?state=closed&per_page=10&sort=updated&direction=desc&since=' . date('Y-m-d\TH:i:s\Z', strtotime('-7 days'))) ?? [];
$pulls = github_api('pulls?state=open&per_page=10') ?? [];
$commits = github_api('commits?per_page=15') ?? [];

$real_issues = array_filter($issues, fn($i) => !isset($i['pull_request']));
$real_closed = array_filter($closed_issues, fn($i) => !isset($i['pull_request']));

$label_counts = [];
foreach ($real_issues as $issue) {
    foreach ($issue['labels'] ?? [] as $label) {
        $name = $label['name'];
        $label_counts[$name] = ($label_counts[$name] ?? 0) + 1;
    }
}

require_once '../views/templates/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap');

:root {
    --pp-primary: #1a365d;
    --pp-primary-dark: #0f2a44;
    --pp-primary-deep: #0a1e33;
    --pp-primary-light: #2d4a6b;
    --pp-secondary: #c41e3a;
    --pp-secondary-light: #d63856;
    --pp-accent: #F59E0B;
    --pp-accent-light: #FCD34D;
    --pp-surface: #112240;
    --pp-surface-light: #1d3461;
    --pp-border: rgba(45, 74, 107, 0.5);
    --pp-text: #ccd6e6;
    --pp-text-muted: #8892a8;
    --pp-text-bright: #e6ecf5;
    --pp-glow-blue: rgba(26, 54, 93, 0.4);
    --pp-glow-red: rgba(196, 30, 58, 0.3);
    --pp-glow-gold: rgba(245, 158, 11, 0.2);
}

.dt-page {
    font-family: 'Space Grotesk', sans-serif;
    background: var(--pp-primary-deep);
    min-height: 100vh;
}
.dt-mono { font-family: 'IBM Plex Mono', monospace; }

.dt-hero {
    background: linear-gradient(135deg, var(--pp-primary) 0%, #1e4178 50%, #254b8f 100%);
    position: relative;
    overflow: hidden;
}
.dt-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, transparent 70%);
    pointer-events: none;
}
.dt-hero::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(196,30,58,0.06) 0%, transparent 70%);
    pointer-events: none;
}

.dt-card {
    background: var(--pp-surface);
    border: 1px solid var(--pp-border);
    border-radius: 16px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.dt-card:hover {
    border-color: var(--pp-primary-light);
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
}

.dt-stat {
    background: linear-gradient(145deg, var(--pp-surface) 0%, var(--pp-primary-dark) 100%);
    border: 1px solid var(--pp-border);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    position: relative;
    overflow: hidden;
}
.dt-stat::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    border-radius: 0 16px 0 80px;
    opacity: 0.06;
    pointer-events: none;
}
.dt-stat-up::after { background: #34d399; }
.dt-stat-issues::after { background: var(--pp-accent); }
.dt-stat-prs::after { background: #60a5fa; }
.dt-stat-deploy::after { background: var(--pp-secondary); }

.dt-stat-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    font-family: 'Space Grotesk', sans-serif;
}
.dt-stat-label {
    font-size: 0.8rem;
    color: var(--pp-text-muted);
    margin-top: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.dt-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}
.dt-b-green  { background: rgba(52,211,153,0.12); color: #34d399; border: 1px solid rgba(52,211,153,0.2); }
.dt-b-red    { background: rgba(196,30,58,0.12);  color: #f87171; border: 1px solid rgba(196,30,58,0.2); }
.dt-b-gold   { background: rgba(245,158,11,0.12); color: var(--pp-accent); border: 1px solid rgba(245,158,11,0.2); }
.dt-b-blue   { background: rgba(96,165,250,0.12); color: #60a5fa; border: 1px solid rgba(96,165,250,0.2); }
.dt-b-purple { background: rgba(168,85,247,0.12); color: #c084fc; border: 1px solid rgba(168,85,247,0.2); }
.dt-b-muted  { background: rgba(136,146,168,0.1); color: var(--pp-text-muted); border: 1px solid rgba(136,146,168,0.15); }

.dt-timeline-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 5px;
    box-shadow: 0 0 8px currentColor;
}
.dt-timeline-line {
    width: 1px;
    background: linear-gradient(to bottom, var(--pp-border), transparent);
    flex-shrink: 0;
    margin-left: 4.5px;
}

.dt-bar-track {
    height: 5px;
    border-radius: 3px;
    background: rgba(45,74,107,0.3);
    overflow: hidden;
}
.dt-bar-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.dt-cron-row {
    display: grid;
    grid-template-columns: 1fr auto auto auto;
    gap: 8px 16px;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid rgba(45,74,107,0.25);
    transition: background 0.15s;
}
.dt-cron-row:hover { background: rgba(45,74,107,0.15); }
.dt-cron-row:last-child { border-bottom: none; }

.dt-grid-main { grid-template-columns: 3fr 2fr; }

.dt-section-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--pp-text-bright);
    display: flex;
    align-items: center;
    gap: 10px;
}
.dt-section-title svg { width: 18px; height: 18px; color: var(--pp-accent); }

.dt-link {
    color: var(--pp-text);
    transition: color 0.15s;
}
.dt-link:hover { color: var(--pp-accent-light); }

.dt-scroll::-webkit-scrollbar { width: 4px; }
.dt-scroll::-webkit-scrollbar-track { background: transparent; }
.dt-scroll::-webkit-scrollbar-thumb { background: var(--pp-border); border-radius: 2px; }

@keyframes glow-pulse {
    0%, 100% { opacity: 1; box-shadow: 0 0 6px currentColor; }
    50% { opacity: 0.5; box-shadow: 0 0 2px currentColor; }
}
.dt-pulse { animation: glow-pulse 2.5s ease-in-out infinite; }

@keyframes fade-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.dt-fade { animation: fade-in 0.4s ease-out both; }
.dt-fade-1 { animation-delay: 0.05s; }
.dt-fade-2 { animation-delay: 0.1s; }
.dt-fade-3 { animation-delay: 0.15s; }
.dt-fade-4 { animation-delay: 0.2s; }

@media (max-width: 1024px) {
    .dt-grid-main { grid-template-columns: 1fr; }
    .dt-grid-2col { grid-template-columns: 1fr !important; }
}
@media (max-width: 768px) {
    .dt-cron-row { grid-template-columns: 1fr auto; gap: 4px 8px; padding: 10px 12px; }
    .dt-hide-md { display: none; }
}
@media (max-width: 640px) {
    .dt-stats-grid { grid-template-columns: 1fr 1fr !important; }
    .dt-stat { padding: 1rem; }
    .dt-stat-value { font-size: 1.5rem; }
    .dt-hero-inner { padding-top: 1.5rem; padding-bottom: 1.5rem; }
    .dt-header-ts { display: none; }
    .dt-header-btns { width: 100%; }
    .dt-header-btns a { flex: 1; text-align: center; }
    .dt-card { border-radius: 12px; }
}
@media (max-width: 380px) {
    .dt-stats-grid { grid-template-columns: 1fr !important; }
}
</style>

<main class="dt-page">
    <!-- Hero -->
    <div class="dt-hero">
        <div class="max-w-7xl mx-auto px-4 py-8 md:py-10 relative z-10 dt-hero-inner">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">DevTeam Dashboard</h1>
                        </div>
                    </div>
                    <p class="text-blue-200/70 text-sm ml-13">PolitiekPraat -- Virtueel ICT-Bedrijf Operations Center</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 dt-header-btns">
                    <?php if ($state && $state['last_updated']): ?>
                        <span class="text-blue-300/50 text-xs dt-mono dt-header-ts"><?= htmlspecialchars($state['last_updated']) ?></span>
                    <?php endif; ?>
                    <a href="devteam-agents.php" class="text-sm bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 px-4 py-2 rounded-xl transition border border-amber-500/20">
                        Live Agents
                    </a>
                    <a href="dashboard.php" class="text-sm bg-white/10 hover:bg-white/15 text-white/80 px-4 py-2 rounded-xl transition border border-white/10">
                        Admin Panel
                    </a>
                    <a href="devteam-dashboard.php" class="text-sm bg-white/5 hover:bg-white/10 text-white/60 px-3 py-2 rounded-xl transition border border-white/5">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-5 relative z-10 pb-10">

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 dt-stats-grid">
            <?php
            $site_up = $state['site_status']['up'] ?? null;
            $site_ms = $state['site_status']['avg_ms'] ?? null;
            ?>
            <div class="dt-stat dt-stat-up dt-fade dt-fade-1">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2.5 h-2.5 rounded-full <?= $site_up === true ? 'bg-emerald-400 dt-pulse' : ($site_up === false ? 'bg-red-400 dt-pulse' : 'bg-slate-500') ?>" style="color: <?= $site_up === true ? '#34d399' : '#f87171' ?>"></div>
                    <span class="text-xs font-semibold uppercase tracking-widest" style="color: var(--pp-text-muted)">Site Status</span>
                </div>
                <div class="dt-stat-value <?= $site_up === true ? 'text-emerald-400' : ($site_up === false ? 'text-red-400' : 'text-slate-500') ?>">
                    <?= $site_up === true ? 'ONLINE' : ($site_up === false ? 'OFFLINE' : '--') ?>
                </div>
                <div class="dt-stat-label"><?= $site_ms !== null ? "{$site_ms}ms gemiddeld" : 'Wacht op eerste check' ?></div>
            </div>

            <div class="dt-stat dt-stat-issues dt-fade dt-fade-2">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-3.5 h-3.5" style="color: var(--pp-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>
                    <span class="text-xs font-semibold uppercase tracking-widest" style="color: var(--pp-text-muted)">Issues</span>
                </div>
                <div class="dt-stat-value" style="color: var(--pp-accent)"><?= count($real_issues) ?></div>
                <div class="dt-stat-label"><?= count($real_closed) ?> gesloten deze week</div>
            </div>

            <div class="dt-stat dt-stat-prs dt-fade dt-fade-3">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span class="text-xs font-semibold uppercase tracking-widest" style="color: var(--pp-text-muted)">Open PRs</span>
                </div>
                <div class="dt-stat-value text-blue-400"><?= count($pulls) ?></div>
                <div class="dt-stat-label">wachtend op review</div>
            </div>

            <?php
            $deploy = $state['last_deploy'] ?? null;
            $deploy_status = $deploy['status'] ?? null;
            $deploy_time = $deploy['timestamp'] ?? null;
            $deploy_ago = '';
            if ($deploy_time) {
                $diff = time() - strtotime($deploy_time);
                if ($diff < 3600) $deploy_ago = round($diff / 60) . 'm geleden';
                elseif ($diff < 86400) $deploy_ago = round($diff / 3600) . 'u geleden';
                else $deploy_ago = round($diff / 86400) . 'd geleden';
            }
            ?>
            <div class="dt-stat dt-stat-deploy dt-fade dt-fade-4">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-3.5 h-3.5" style="color: var(--pp-secondary-light, #d63856)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-xs font-semibold uppercase tracking-widest" style="color: var(--pp-text-muted)">Laatste Deploy</span>
                </div>
                <div class="dt-stat-value <?= $deploy_status === 'success' ? 'text-emerald-400' : ($deploy_status ? 'text-red-400' : 'text-slate-500') ?>">
                    <?= $deploy_status === 'success' ? 'OK' : ($deploy_status ?? '--') ?>
                </div>
                <div class="dt-stat-label dt-mono"><?= $deploy_ago ?: 'Nog geen deploy' ?><?= !empty($deploy['commit']) ? " ({$deploy['commit']})" : '' ?></div>
            </div>
        </div>

        <!-- Main grid: Activity + Sprint Board -->
        <div class="grid gap-4 mb-6 dt-grid-main">
            <!-- Activity Feed -->
            <div class="dt-card p-5 dt-fade" style="animation-delay: 0.25s">
                <h2 class="dt-section-title mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Activiteiten Feed
                </h2>
                <div class="space-y-0 max-h-96 overflow-y-auto dt-scroll pr-2">
                    <?php
                    $feed = $state['activity_feed'] ?? [];
                    if (empty($feed)):
                    ?>
                        <p style="color: var(--pp-text-muted)" class="text-sm">Nog geen activiteiten. De eerste cron-runs vullen dit automatisch.</p>
                    <?php else:
                        foreach (array_slice($feed, 0, 20) as $event):
                            $is_ok = $event['ok'] ?? false;
                            $dot_color = $is_ok ? '#34d399' : '#f87171';
                            $role_badge = match($event['role'] ?? '') {
                                'Tech Lead' => 'dt-b-purple',
                                'Developer' => 'dt-b-blue',
                                'Code Reviewer' => 'dt-b-gold',
                                'DevOps' => 'dt-b-green',
                                'QA Tester' => 'dt-b-muted',
                                'SEO Specialist' => 'dt-b-red',
                                default => 'dt-b-muted',
                            };
                    ?>
                        <div class="flex gap-3 py-2">
                            <div class="flex flex-col items-center">
                                <div class="dt-timeline-dot" style="background: <?= $dot_color ?>; color: <?= $dot_color ?>"></div>
                                <div class="dt-timeline-line flex-1 mt-1"></div>
                            </div>
                            <div class="flex-1 min-w-0 pb-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="dt-badge <?= $role_badge ?>"><?= htmlspecialchars($event['role'] ?? 'Onbekend') ?></span>
                                    <span class="text-xs dt-mono" style="color: var(--pp-text-muted)"><?= htmlspecialchars($event['ts'] ?? '') ?></span>
                                </div>
                                <p class="text-sm mt-1.5 truncate" style="color: var(--pp-text)"><?= htmlspecialchars($event['msg'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Sprint Board -->
            <div class="dt-card p-5 dt-fade" style="animation-delay: 0.3s">
                <h2 class="dt-section-title mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Sprint Board
                </h2>
                <?php
                $sb = $state['sprint_board'] ?? [];
                $columns = [
                    'planned'     => ['label' => 'Gepland',     'dot' => '#64748b'],
                    'in_progress' => ['label' => 'In Progress', 'dot' => '#60a5fa'],
                    'in_review'   => ['label' => 'In Review',   'dot' => '#fbbf24'],
                    'merged'      => ['label' => 'Gemerged',    'dot' => '#34d399'],
                ];
                $has_tasks = false;
                foreach ($columns as $key => $col):
                    $tasks = $sb[$key] ?? [];
                    if (!empty($tasks)) $has_tasks = true;
                ?>
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full" style="background: <?= $col['dot'] ?>"></div>
                            <span class="text-xs font-semibold uppercase tracking-wider" style="color: <?= $col['dot'] ?>"><?= $col['label'] ?></span>
                            <span class="text-xs" style="color: var(--pp-text-muted)"><?= count($tasks) ?></span>
                        </div>
                        <?php if (!empty($tasks)): foreach ($tasks as $task): ?>
                            <div class="ml-4 py-1.5 pl-3" style="border-left: 2px solid <?= $col['dot'] ?>30">
                                <span class="text-sm" style="color: var(--pp-text)"><?= htmlspecialchars(is_string($task) ? $task : ($task['title'] ?? json_encode($task))) ?></span>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="ml-4 py-1"><span class="text-xs" style="color: var(--pp-text-muted)">--</span></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach;
                if (!$has_tasks): ?>
                    <p class="text-sm mt-2" style="color: var(--pp-text-muted)">Sprint board vult zich na de eerste Tech Lead stand-up.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cron Jobs -->
        <div class="dt-card mb-6 dt-fade" style="animation-delay: 0.35s">
            <div class="p-5 pb-3">
                <h2 class="dt-section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Geplande Jobs
                    <span class="text-xs font-normal" style="color: var(--pp-text-muted)">8 agents</span>
                </h2>
            </div>
            <div class="px-2 pb-3">
                <?php
                $cron_defs = [
                    ['id' => 'pp-devteam-pc-onboarding', 'name' => 'PC Setup & Verificatie', 'schedule' => '06:00', 'role' => 'DevOps'],
                    ['id' => 'pp-devteam-techlead-01', 'name' => 'Tech Lead Stand-up', 'schedule' => '07:00', 'role' => 'Tech Lead'],
                    ['id' => 'pp-devteam-dev-ochtend-01', 'name' => 'Dev Sprint Ochtend', 'schedule' => '09:00', 'role' => 'Developer'],
                    ['id' => 'pp-devteam-review-01', 'name' => 'Code Review', 'schedule' => '11:00', 'role' => 'Reviewer'],
                    ['id' => 'pp-devteam-dev-middag-01', 'name' => 'Dev Sprint Middag', 'schedule' => '14:00', 'role' => 'Developer'],
                    ['id' => 'pp-devteam-dev-avond-01', 'name' => 'Dev Sprint Avond', 'schedule' => '17:00', 'role' => 'Developer'],
                    ['id' => 'pp-devteam-deploy-qa-01', 'name' => 'Deploy & QA', 'schedule' => '20:30', 'role' => 'DevOps'],
                    ['id' => 'pp-devteam-seo-01', 'name' => 'SEO Audit', 'schedule' => 'Ma 10:00', 'role' => 'SEO'],
                ];
                $cron_states = $state['cron_states'] ?? [];
                foreach ($cron_defs as $cron):
                    $cs = $cron_states[$cron['id']] ?? null;
                    $status = $cs['status'] ?? null;
                    $last_run = $cs['last_run'] ?? null;
                    $duration = $cs['duration_ms'] ?? null;
                ?>
                <div class="dt-cron-row">
                    <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        <span class="text-sm font-medium" style="color: var(--pp-text-bright)"><?= $cron['name'] ?></span>
                        <span class="text-xs ml-1.5 dt-hide-md" style="color: var(--pp-text-muted)"><?= $cron['role'] ?></span>
                    </div>
                    <span class="text-xs dt-mono dt-hide-md" style="color: var(--pp-text-muted)"><?= $cron['schedule'] ?></span>
                    <span class="text-xs dt-mono" style="color: var(--pp-text-muted)"><?= $last_run ? htmlspecialchars(substr($last_run, 11, 5)) : '--:--' ?></span>
                    <?php if ($status === 'ok'): ?>
                        <span class="dt-badge dt-b-green">OK<?= $duration ? ' ' . round($duration / 1000) . 's' : '' ?></span>
                    <?php elseif ($status === 'error'): ?>
                        <span class="dt-badge dt-b-red">FOUT</span>
                    <?php else: ?>
                        <span class="dt-badge dt-b-muted">Wacht</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Health Check -->
        <div class="dt-card mb-6 p-5 dt-fade" style="animation-delay: 0.4s">
            <h2 class="dt-section-title mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Site Health
                <?php if ($state['health_check']['avg_ms'] ?? null): ?>
                    <span class="text-xs font-normal" style="color: var(--pp-text-muted)">gem. <?= $state['health_check']['avg_ms'] ?>ms</span>
                <?php endif; ?>
            </h2>
            <?php
            $routes = $state['health_check']['routes'] ?? [];
            $apis = $state['health_check']['apis'] ?? [];
            if (empty($routes)):
            ?>
                <p class="text-sm" style="color: var(--pp-text-muted)">Nog geen health check data. Wordt gevuld na de eerste QA-run.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <?php foreach (array_merge($routes, $apis) as $r):
                        $route_name = $r['route'] ?? $r['endpoint'] ?? '?';
                        $ms = round(($r['time_s'] ?? 0) * 1000);
                        $level = $r['level'] ?? 'FAIL';
                        $max_ms = 2000;
                        $bar_pct = min(100, ($ms / $max_ms) * 100);
                        $bar_color = match($level) {
                            'OK' => '#34d399',
                            'SLOW' => '#fbbf24',
                            default => '#f87171',
                        };
                    ?>
                    <div style="background: var(--pp-primary-dark); border-radius: 10px; padding: 0.75rem 1rem;">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm dt-mono" style="color: var(--pp-text)"><?= htmlspecialchars($route_name) ?></span>
                            <span class="text-xs dt-mono" style="color: <?= $bar_color ?>"><?= $ms ?>ms</span>
                        </div>
                        <div class="dt-bar-track">
                            <div class="dt-bar-fill" style="width: <?= $bar_pct ?>%; background: <?= $bar_color ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- GitHub: Commits + Issues -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6 dt-grid-2col">
            <div class="dt-card p-5 dt-fade" style="animation-delay: 0.45s">
                <h2 class="dt-section-title mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Recent Commits
                </h2>
                <div class="space-y-1 max-h-80 overflow-y-auto dt-scroll pr-1">
                    <?php if (empty($commits)): ?>
                        <p class="text-sm" style="color: var(--pp-text-muted)">Kan commits niet laden.</p>
                    <?php else: foreach (array_slice($commits, 0, 10) as $c):
                        $sha = substr($c['sha'] ?? '', 0, 7);
                        $msg = $c['commit']['message'] ?? '';
                        $msg_first = strtok($msg, "\n");
                        $author = $c['commit']['author']['name'] ?? '?';
                        $date = $c['commit']['author']['date'] ?? '';
                        $date_short = $date ? date('d M H:i', strtotime($date)) : '';
                    ?>
                        <div class="flex items-start gap-3 py-2" style="border-bottom: 1px solid rgba(45,74,107,0.2)">
                            <code class="text-xs dt-mono flex-shrink-0 mt-0.5" style="color: var(--pp-accent)"><?= $sha ?></code>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm truncate" style="color: var(--pp-text)"><?= htmlspecialchars($msg_first) ?></p>
                                <span class="text-xs" style="color: var(--pp-text-muted)"><span class="dt-hide-md"><?= htmlspecialchars($author) ?> -- </span><?= $date_short ?></span>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="dt-card p-5 dt-fade" style="animation-delay: 0.5s">
                <h2 class="dt-section-title mb-3">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Open Issues
                    <span class="text-xs font-normal" style="color: var(--pp-text-muted)">(<?= count($real_issues) ?>)</span>
                </h2>
                <?php if (!empty($label_counts)): ?>
                <div class="flex flex-wrap gap-2 mb-3">
                    <?php foreach ($label_counts as $lbl => $cnt):
                        $lbl_class = match(true) {
                            str_contains($lbl, 'bug') => 'dt-b-red',
                            str_contains($lbl, 'security') => 'dt-b-red',
                            str_contains($lbl, 'performance') => 'dt-b-gold',
                            str_contains($lbl, 'enhancement') => 'dt-b-blue',
                            str_contains($lbl, 'refactor') => 'dt-b-purple',
                            default => 'dt-b-muted',
                        };
                    ?>
                        <span class="dt-badge <?= $lbl_class ?>"><?= htmlspecialchars($lbl) ?>: <?= $cnt ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="space-y-1 max-h-64 overflow-y-auto dt-scroll pr-1">
                    <?php if (empty($real_issues)): ?>
                        <p class="text-sm" style="color: var(--pp-text-muted)">Geen open issues.</p>
                    <?php else: foreach (array_slice(array_values($real_issues), 0, 10) as $issue):
                        $labels_html = '';
                        foreach ($issue['labels'] ?? [] as $l) {
                            $lc = match(true) {
                                str_contains($l['name'], 'bug') => 'dt-b-red',
                                str_contains($l['name'], 'security') => 'dt-b-red',
                                str_contains($l['name'], 'performance') => 'dt-b-gold',
                                default => 'dt-b-muted',
                            };
                            $labels_html .= "<span class='dt-badge {$lc} ml-1'>" . htmlspecialchars($l['name']) . "</span>";
                        }
                    ?>
                        <div class="flex items-start gap-2 py-2" style="border-bottom: 1px solid rgba(45,74,107,0.2)">
                            <span class="text-xs dt-mono flex-shrink-0 mt-0.5" style="color: var(--pp-text-muted)">#<?= $issue['number'] ?></span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm" style="color: var(--pp-text)">
                                    <a href="<?= htmlspecialchars($issue['html_url'] ?? '#') ?>" target="_blank" class="dt-link"><?= htmlspecialchars($issue['title'] ?? '?') ?></a>
                                    <?= $labels_html ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!-- Open PRs -->
        <?php if (!empty($pulls)): ?>
        <div class="dt-card p-5 mb-6 dt-fade" style="animation-delay: 0.55s">
            <h2 class="dt-section-title mb-3">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Open Pull Requests
            </h2>
            <div class="space-y-0">
                <?php foreach ($pulls as $pr):
                    $pr_date = $pr['created_at'] ? date('d M H:i', strtotime($pr['created_at'])) : '';
                ?>
                <div class="flex items-center gap-3 py-2.5" style="border-bottom: 1px solid rgba(45,74,107,0.2)">
                    <span class="dt-badge dt-b-blue flex-shrink-0">#<?= $pr['number'] ?></span>
                    <div class="flex-1 min-w-0">
                        <a href="<?= htmlspecialchars($pr['html_url'] ?? '#') ?>" target="_blank" class="text-sm dt-link truncate block"><?= htmlspecialchars($pr['title'] ?? '?') ?></a>
                    </div>
                    <span class="text-xs dt-mono dt-hide-md" style="color: var(--pp-text-muted)"><?= $pr_date ?></span>
                    <span class="text-xs dt-mono dt-hide-md" style="color: #34d399">+<?= $pr['additions'] ?? 0 ?></span>
                    <span class="text-xs dt-mono dt-hide-md" style="color: #f87171">-<?= $pr['deletions'] ?? 0 ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="text-center text-xs pb-6 dt-mono" style="color: var(--pp-text-muted); opacity: 0.5">
            PolitiekPraat DevTeam v1.0
        </div>
    </div>
</main>

<script>
setTimeout(() => location.reload(), 60000);
</script>

<?php require_once '../views/templates/footer.php'; ?>
