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

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                pp: { deep: '#0a1e33', dark: '#0f2a44', DEFAULT: '#1a365d', light: '#2d4a6b', surface: '#112240', surfaceLight: '#1d3461' },
                ppRed: { DEFAULT: '#c41e3a', light: '#d63856' },
                ppGold: { DEFAULT: '#F59E0B', light: '#FCD34D' },
            }
        }
    }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap');

body {
    background: #0a1e33 !important;
    color: #ccd6e6 !important;
    font-family: 'Space Grotesk', system-ui, sans-serif !important;
}

.dt-mono { font-family: 'IBM Plex Mono', monospace; }

.dt-hero {
    background: linear-gradient(135deg, #1a365d 0%, #1e4178 50%, #254b8f 100%);
    position: relative;
    overflow: hidden;
}
.dt-hero::before {
    content: '';
    position: absolute;
    top: -50%; right: -20%;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(245,158,11,0.07) 0%, transparent 70%);
    pointer-events: none;
}

.dt-card {
    background: #112240;
    border: 1px solid rgba(45,74,107,0.5);
    border-radius: 16px;
    transition: all 0.25s ease;
}
.dt-card:hover {
    border-color: #2d4a6b;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
}

.dt-stat {
    background: linear-gradient(145deg, #112240 0%, #0f2a44 100%);
    border: 1px solid rgba(45,74,107,0.5);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    position: relative; overflow: hidden;
}

.dt-badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px; border-radius: 8px;
    font-size: 0.7rem; font-weight: 600;
    letter-spacing: 0.02em; text-transform: uppercase;
}

.dt-bar-track { height: 5px; border-radius: 3px; background: rgba(45,74,107,0.3); overflow: hidden; }
.dt-bar-fill { height: 100%; border-radius: 3px; transition: width 0.4s ease; }

.dt-cron-row {
    display: grid;
    grid-template-columns: 1fr auto auto auto;
    gap: 8px 16px; align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid rgba(45,74,107,0.2);
    transition: background 0.15s;
}
.dt-cron-row:hover { background: rgba(45,74,107,0.15); }
.dt-cron-row:last-child { border-bottom: none; }

.dt-scroll::-webkit-scrollbar { width: 4px; }
.dt-scroll::-webkit-scrollbar-track { background: transparent; }
.dt-scroll::-webkit-scrollbar-thumb { background: rgba(45,74,107,0.5); border-radius: 2px; }

.dt-quick-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 12px;
    font-size: 0.85rem; font-weight: 600;
    transition: all 0.2s; cursor: pointer;
    border: 1px solid transparent;
    flex: 1; justify-content: center;
}

@keyframes glow-pulse {
    0%, 100% { opacity: 1; box-shadow: 0 0 6px currentColor; }
    50% { opacity: 0.5; box-shadow: 0 0 2px currentColor; }
}
.dt-pulse { animation: glow-pulse 2.5s ease-in-out infinite; }

@keyframes fade-in {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.dt-fade { animation: fade-in 0.4s ease-out both; }

@media (max-width: 1024px) {
    .dt-grid-main { grid-template-columns: 1fr !important; }
    .dt-grid-2col { grid-template-columns: 1fr !important; }
}
@media (max-width: 768px) {
    .dt-cron-row { grid-template-columns: 1fr auto; gap: 4px 8px; padding: 10px 12px; }
    .dt-hide-md { display: none; }
    .dt-quick-row { flex-direction: column; }
    .dt-quick-btn { width: 100%; }
}
@media (max-width: 640px) {
    .dt-hero-inner { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
    .dt-header-ts { display: none; }
    .dt-header-btns { width: 100%; }
    .dt-header-btns a { flex: 1; text-align: center; }
    .dt-stat { padding: 1rem; }
}
</style>

<main class="min-h-screen">
    <!-- Hero -->
    <div class="dt-hero">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-10 relative z-10 dt-hero-inner">
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
                    <p class="text-blue-200/60 text-sm pl-[52px]">PolitiekPraat -- Virtueel ICT-Bedrijf Operations Center</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 dt-header-btns">
                    <?php if ($state && $state['last_updated']): ?>
                        <span class="text-blue-300/40 text-xs dt-mono dt-header-ts"><?= htmlspecialchars($state['last_updated']) ?></span>
                    <?php endif; ?>
                    <a href="devteam-agents.php" class="text-sm bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 px-4 py-2 rounded-xl transition border border-amber-500/20">
                        Live Agents
                    </a>
                    <a href="dashboard.php" class="text-sm bg-white/10 hover:bg-white/15 text-white/80 px-4 py-2 rounded-xl transition border border-white/10">
                        Admin Panel
                    </a>
                    <a href="devteam-dashboard.php" class="text-sm bg-white/5 hover:bg-white/10 text-white/50 px-3 py-2 rounded-xl transition border border-white/5" title="Ververs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 -mt-5 relative z-10 pb-10">

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-3 mb-6 dt-quick-row dt-fade" style="animation-delay:0.05s">
            <button onclick="triggerAgent('pp-devteam-deploy-qa-01','Guardian')" class="dt-quick-btn bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Health Check
            </button>
            <button onclick="if(confirm('Deploy starten?'))triggerAgent('pp-devteam-deploy-qa-01','Guardian')" class="dt-quick-btn bg-ppRed/10 text-red-400 border-ppRed/20 hover:bg-ppRed/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Deploy
            </button>
            <button onclick="triggerAgent('pp-devteam-techlead-01','Nova')" class="dt-quick-btn bg-purple-500/10 text-purple-400 border-purple-500/20 hover:bg-purple-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                Tech Lead
            </button>
            <a href="https://politiekpraat.nl" target="_blank" class="dt-quick-btn bg-blue-500/10 text-blue-400 border-blue-500/20 hover:bg-blue-500/20 no-underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Live Site
            </a>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <?php
            $site_up = $state['site_status']['up'] ?? null;
            $site_ms = $state['site_status']['avg_ms'] ?? null;
            ?>
            <div class="dt-stat dt-fade" style="animation-delay:0.1s">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2.5 h-2.5 rounded-full <?= $site_up === true ? 'bg-emerald-400 dt-pulse' : ($site_up === false ? 'bg-red-400 dt-pulse' : 'bg-slate-500') ?>" style="color:<?= $site_up === true ? '#34d399' : '#f87171' ?>"></div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Site Status</span>
                </div>
                <div class="text-2xl font-bold leading-none <?= $site_up === true ? 'text-emerald-400' : ($site_up === false ? 'text-red-400' : 'text-slate-500') ?>">
                    <?= $site_up === true ? 'ONLINE' : ($site_up === false ? 'OFFLINE' : '--') ?>
                </div>
                <div class="text-xs text-slate-500 mt-1.5"><?= $site_ms !== null ? "{$site_ms}ms gemiddeld" : 'Wacht op eerste check' ?></div>
            </div>

            <div class="dt-stat dt-fade" style="animation-delay:0.15s">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>
                    <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Issues</span>
                </div>
                <div class="text-2xl font-bold leading-none text-amber-400"><?= count($real_issues) ?></div>
                <div class="text-xs text-slate-500 mt-1.5"><?= count($real_closed) ?> gesloten deze week</div>
            </div>

            <div class="dt-stat dt-fade" style="animation-delay:0.2s">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Open PRs</span>
                </div>
                <div class="text-2xl font-bold leading-none text-blue-400"><?= count($pulls) ?></div>
                <div class="text-xs text-slate-500 mt-1.5">wachtend op review</div>
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
            <div class="dt-stat dt-fade" style="animation-delay:0.25s">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Deploy</span>
                </div>
                <div class="text-2xl font-bold leading-none <?= $deploy_status === 'success' ? 'text-emerald-400' : ($deploy_status ? 'text-red-400' : 'text-slate-500') ?>">
                    <?= $deploy_status === 'success' ? 'OK' : ($deploy_status ?? '--') ?>
                </div>
                <div class="text-xs text-slate-500 mt-1.5 dt-mono truncate"><?= $deploy_ago ?: 'Nog geen deploy' ?><?= !empty($deploy['commit']) ? " ({$deploy['commit']})" : '' ?></div>
            </div>
        </div>

        <!-- Performance Chart + Agent Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6 dt-grid-2col">
            <div class="dt-card p-5 dt-fade" style="animation-delay:0.3s">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Response Times
                </h2>
                <div style="height:180px;position:relative">
                    <canvas id="perfChart"></canvas>
                </div>
            </div>
            <div class="dt-card p-5 dt-fade" style="animation-delay:0.35s">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Agent Prestaties
                </h2>
                <?php
                $cron_states = $state['cron_states'] ?? [];
                $agent_names = [
                    'pp-devteam-techlead-01' => ['Nova', '#c084fc'],
                    'pp-devteam-dev-ochtend-01' => ['Pulse', '#60a5fa'],
                    'pp-devteam-review-01' => ['Sentinel', '#fbbf24'],
                    'pp-devteam-dev-middag-01' => ['Forge', '#38bdf8'],
                    'pp-devteam-dev-avond-01' => ['Echo', '#818cf8'],
                    'pp-devteam-deploy-qa-01' => ['Guardian', '#f472b6'],
                    'pp-devteam-seo-01' => ['Compass', '#fb923c'],
                ];
                ?>
                <div class="space-y-3">
                    <?php foreach ($agent_names as $aid => [$aname, $acolor]):
                        $cs = $cron_states[$aid] ?? null;
                        $runs = $cs['total_runs'] ?? ($cs ? 1 : 0);
                        $ok = $cs['ok_runs'] ?? ($cs && ($cs['status'] ?? '') === 'ok' ? 1 : 0);
                        $pct = $runs > 0 ? round(($ok / $runs) * 100) : 0;
                    ?>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-medium w-16 truncate" style="color:<?= $acolor ?>"><?= $aname ?></span>
                        <div class="flex-1 dt-bar-track">
                            <div class="dt-bar-fill" style="width:<?= $runs > 0 ? $pct : 0 ?>%;background:<?= $acolor ?>"></div>
                        </div>
                        <span class="text-xs dt-mono text-slate-400 w-10 text-right"><?= $runs > 0 ? $pct . '%' : '--' ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Activity + Sprint -->
        <div class="grid gap-4 mb-6" style="grid-template-columns:3fr 2fr">
            <div class="dt-card p-5 dt-fade" style="animation-delay:0.4s">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Activiteiten Feed
                </h2>
                <div class="space-y-0 max-h-96 overflow-y-auto dt-scroll pr-2">
                    <?php
                    $feed = $state['activity_feed'] ?? [];
                    if (empty($feed)):
                    ?>
                        <p class="text-sm text-slate-500">Nog geen activiteiten. De eerste cron-runs vullen dit automatisch.</p>
                    <?php else:
                        foreach (array_slice($feed, 0, 20) as $event):
                            $is_ok = $event['ok'] ?? false;
                            $dot_color = $is_ok ? '#34d399' : '#f87171';
                            $badge_colors = ['Tech Lead' => ['#c084fc','rgba(192,132,252,0.12)','rgba(192,132,252,0.2)'], 'Developer' => ['#60a5fa','rgba(96,165,250,0.12)','rgba(96,165,250,0.2)'], 'Code Reviewer' => ['#fbbf24','rgba(251,191,36,0.12)','rgba(251,191,36,0.2)'], 'DevOps' => ['#34d399','rgba(52,211,153,0.12)','rgba(52,211,153,0.2)'], 'QA Tester' => ['#94a3b8','rgba(148,163,184,0.1)','rgba(148,163,184,0.15)'], 'SEO Specialist' => ['#fb923c','rgba(251,146,60,0.12)','rgba(251,146,60,0.2)']];
                            $bc = $badge_colors[$event['role'] ?? ''] ?? ['#94a3b8','rgba(148,163,184,0.1)','rgba(148,163,184,0.15)'];
                    ?>
                        <div class="flex gap-3 py-2.5">
                            <div class="flex flex-col items-center">
                                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0 mt-1" style="background:<?= $dot_color ?>;box-shadow:0 0 6px <?= $dot_color ?>"></div>
                                <div class="w-px flex-1 mt-1" style="background:linear-gradient(to bottom,rgba(45,74,107,0.4),transparent)"></div>
                            </div>
                            <div class="flex-1 min-w-0 pb-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="dt-badge" style="background:<?= $bc[1] ?>;color:<?= $bc[0] ?>;border:1px solid <?= $bc[2] ?>"><?= htmlspecialchars($event['role'] ?? 'Onbekend') ?></span>
                                    <span class="text-xs dt-mono text-slate-500"><?= htmlspecialchars($event['ts'] ?? '') ?></span>
                                </div>
                                <p class="text-sm mt-1 text-slate-300 truncate"><?= htmlspecialchars($event['msg'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="dt-card p-5 dt-fade" style="animation-delay:0.45s">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
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
                            <div class="w-2 h-2 rounded-full" style="background:<?= $col['dot'] ?>"></div>
                            <span class="text-xs font-semibold uppercase tracking-wider" style="color:<?= $col['dot'] ?>"><?= $col['label'] ?></span>
                            <span class="text-xs text-slate-500"><?= count($tasks) ?></span>
                        </div>
                        <?php if (!empty($tasks)): foreach ($tasks as $task): ?>
                            <div class="ml-4 py-1.5 pl-3" style="border-left:2px solid <?= $col['dot'] ?>40">
                                <span class="text-sm text-slate-300"><?= htmlspecialchars(is_string($task) ? $task : ($task['title'] ?? json_encode($task))) ?></span>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="ml-4 py-1"><span class="text-xs text-slate-600">--</span></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach;
                if (!$has_tasks): ?>
                    <p class="text-sm text-slate-500 mt-2">Sprint board vult zich na de eerste Tech Lead stand-up.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cron Jobs -->
        <div class="dt-card mb-6 dt-fade" style="animation-delay:0.5s">
            <div class="p-5 pb-3">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Geplande Jobs
                    <span class="text-xs font-normal text-slate-500 ml-1">8 agents</span>
                </h2>
            </div>
            <div class="px-2 pb-3">
                <?php
                $cron_defs = [
                    ['id'=>'pp-devteam-pc-onboarding','name'=>'PC Setup','schedule'=>'06:00','role'=>'DevOps'],
                    ['id'=>'pp-devteam-techlead-01','name'=>'Tech Lead Stand-up','schedule'=>'07:00','role'=>'Tech Lead'],
                    ['id'=>'pp-devteam-dev-ochtend-01','name'=>'Dev Sprint Ochtend','schedule'=>'09:00','role'=>'Developer'],
                    ['id'=>'pp-devteam-review-01','name'=>'Code Review','schedule'=>'11:00','role'=>'Reviewer'],
                    ['id'=>'pp-devteam-dev-middag-01','name'=>'Dev Sprint Middag','schedule'=>'14:00','role'=>'Developer'],
                    ['id'=>'pp-devteam-dev-avond-01','name'=>'Dev Sprint Avond','schedule'=>'17:00','role'=>'Developer'],
                    ['id'=>'pp-devteam-deploy-qa-01','name'=>'Deploy & QA','schedule'=>'20:30','role'=>'DevOps'],
                    ['id'=>'pp-devteam-seo-01','name'=>'SEO Audit','schedule'=>'Ma 10:00','role'=>'SEO'],
                ];
                foreach ($cron_defs as $cron):
                    $cs = $cron_states[$cron['id']] ?? null;
                    $status = $cs['status'] ?? null;
                    $last_run = $cs['last_run'] ?? null;
                    $duration = $cs['duration_ms'] ?? null;
                ?>
                <div class="dt-cron-row">
                    <div class="truncate">
                        <span class="text-sm font-medium text-slate-200"><?= $cron['name'] ?></span>
                        <span class="text-xs ml-1.5 text-slate-500 dt-hide-md"><?= $cron['role'] ?></span>
                    </div>
                    <span class="text-xs dt-mono text-slate-500 dt-hide-md"><?= $cron['schedule'] ?></span>
                    <span class="text-xs dt-mono text-slate-500"><?= $last_run ? htmlspecialchars(substr($last_run, 11, 5)) : '--:--' ?></span>
                    <?php if ($status === 'ok'): ?>
                        <span class="dt-badge" style="background:rgba(52,211,153,0.12);color:#34d399;border:1px solid rgba(52,211,153,0.2)">OK<?= $duration ? ' ' . round($duration / 1000) . 's' : '' ?></span>
                    <?php elseif ($status === 'error'): ?>
                        <span class="dt-badge" style="background:rgba(248,113,113,0.12);color:#f87171;border:1px solid rgba(248,113,113,0.2)">FOUT</span>
                    <?php else: ?>
                        <span class="dt-badge" style="background:rgba(148,163,184,0.08);color:#64748b;border:1px solid rgba(148,163,184,0.12)">Wacht</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Health Check -->
        <div class="dt-card mb-6 p-5 dt-fade" style="animation-delay:0.55s">
            <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Site Health
                <?php if ($state['health_check']['avg_ms'] ?? null): ?>
                    <span class="text-xs font-normal text-slate-500 ml-1">gem. <?= $state['health_check']['avg_ms'] ?>ms</span>
                <?php endif; ?>
            </h2>
            <?php
            $routes = $state['health_check']['routes'] ?? [];
            $apis = $state['health_check']['apis'] ?? [];
            if (empty($routes)):
            ?>
                <p class="text-sm text-slate-500">Nog geen health check data.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <?php foreach (array_merge($routes, $apis) as $r):
                        $route_name = $r['route'] ?? $r['endpoint'] ?? '?';
                        $ms = round(($r['time_s'] ?? 0) * 1000);
                        $level = $r['level'] ?? 'FAIL';
                        $bar_pct = min(100, ($ms / 2000) * 100);
                        $bar_color = match($level) { 'OK'=>'#34d399', 'SLOW'=>'#fbbf24', default=>'#f87171' };
                    ?>
                    <div class="bg-pp-dark rounded-xl p-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm dt-mono text-slate-300"><?= htmlspecialchars($route_name) ?></span>
                            <span class="text-xs dt-mono" style="color:<?= $bar_color ?>"><?= $ms ?>ms</span>
                        </div>
                        <div class="dt-bar-track">
                            <div class="dt-bar-fill" style="width:<?= $bar_pct ?>%;background:<?= $bar_color ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Commits + Issues -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6 dt-grid-2col">
            <div class="dt-card p-5 dt-fade" style="animation-delay:0.6s">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Recent Commits
                </h2>
                <div class="space-y-1 max-h-80 overflow-y-auto dt-scroll pr-1">
                    <?php if (empty($commits)): ?>
                        <p class="text-sm text-slate-500">Kan commits niet laden.</p>
                    <?php else: foreach (array_slice($commits, 0, 10) as $c):
                        $sha = substr($c['sha'] ?? '', 0, 7);
                        $msg = $c['commit']['message'] ?? '';
                        $msg_first = strtok($msg, "\n");
                        $author = $c['commit']['author']['name'] ?? '?';
                        $date = $c['commit']['author']['date'] ?? '';
                        $date_short = $date ? date('d M H:i', strtotime($date)) : '';
                    ?>
                        <div class="flex items-start gap-3 py-2 border-b border-slate-700/20">
                            <code class="text-xs dt-mono flex-shrink-0 mt-0.5 text-amber-400"><?= $sha ?></code>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm truncate text-slate-300"><?= htmlspecialchars($msg_first) ?></p>
                                <span class="text-xs text-slate-500"><span class="dt-hide-md"><?= htmlspecialchars($author) ?> -- </span><?= $date_short ?></span>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="dt-card p-5 dt-fade" style="animation-delay:0.65s">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Open Issues
                    <span class="text-xs font-normal text-slate-500">(<?= count($real_issues) ?>)</span>
                </h2>
                <?php if (!empty($label_counts)): ?>
                <div class="flex flex-wrap gap-2 mb-3">
                    <?php foreach ($label_counts as $lbl => $cnt):
                        $lc = match(true) { str_contains($lbl,'bug')=>'#f87171', str_contains($lbl,'security')=>'#f87171', str_contains($lbl,'performance')=>'#fbbf24', str_contains($lbl,'enhancement')=>'#60a5fa', str_contains($lbl,'refactor')=>'#c084fc', default=>'#94a3b8' };
                    ?>
                        <span class="dt-badge" style="background:<?= $lc ?>15;color:<?= $lc ?>;border:1px solid <?= $lc ?>25"><?= htmlspecialchars($lbl) ?>: <?= $cnt ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="space-y-1 max-h-64 overflow-y-auto dt-scroll pr-1">
                    <?php if (empty($real_issues)): ?>
                        <p class="text-sm text-slate-500">Geen open issues.</p>
                    <?php else: foreach (array_slice(array_values($real_issues), 0, 10) as $issue): ?>
                        <div class="flex items-start gap-2 py-2 border-b border-slate-700/20">
                            <span class="text-xs dt-mono flex-shrink-0 mt-0.5 text-slate-500">#<?= $issue['number'] ?></span>
                            <div class="min-w-0 flex-1">
                                <a href="<?= htmlspecialchars($issue['html_url'] ?? '#') ?>" target="_blank" class="text-sm text-slate-300 hover:text-amber-300 transition"><?= htmlspecialchars($issue['title'] ?? '?') ?></a>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($pulls)): ?>
        <div class="dt-card p-5 mb-6 dt-fade" style="animation-delay:0.7s">
            <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Open Pull Requests
            </h2>
            <?php foreach ($pulls as $pr): ?>
            <div class="flex items-center gap-3 py-2.5 border-b border-slate-700/20">
                <span class="dt-badge flex-shrink-0" style="background:rgba(96,165,250,0.12);color:#60a5fa;border:1px solid rgba(96,165,250,0.2)">#<?= $pr['number'] ?></span>
                <div class="flex-1 min-w-0">
                    <a href="<?= htmlspecialchars($pr['html_url'] ?? '#') ?>" target="_blank" class="text-sm text-slate-300 hover:text-amber-300 transition truncate block"><?= htmlspecialchars($pr['title'] ?? '?') ?></a>
                </div>
                <span class="text-xs dt-mono text-emerald-400 dt-hide-md">+<?= $pr['additions'] ?? 0 ?></span>
                <span class="text-xs dt-mono text-red-400 dt-hide-md">-<?= $pr['deletions'] ?? 0 ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="text-center text-xs pb-6 dt-mono text-slate-600">
            PolitiekPraat DevTeam v2.0
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
function triggerAgent(id, name) {
    const fd = new FormData();
    fd.append('action', 'trigger');
    fd.append('agent_id', id);
    fd.append('agent_name', name);
    fetch('devteam-api.php?action=trigger', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.ok) alert(name + ' is getriggerd. De agent pakt de taak op binnen ~1 minuut.');
            else alert('Fout: ' + (d.msg || d.error));
        })
        .catch(() => alert('Kon de API niet bereiken.'));
}

const healthHistory = <?= json_encode($state['health_history'] ?? []) ?>;
const ctx = document.getElementById('perfChart');
if (ctx && healthHistory.length > 0) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: healthHistory.map(h => h.ts ? h.ts.substring(11, 16) : ''),
            datasets: [{
                label: 'Gem. ms',
                data: healthHistory.map(h => h.avg_ms || 0),
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245,158,11,0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#F59E0B',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(45,74,107,0.2)' } },
                y: { ticks: { color: '#64748b', font: { size: 10 }, callback: v => v + 'ms' }, grid: { color: 'rgba(45,74,107,0.2)' } }
            }
        }
    });
} else if (ctx) {
    ctx.parentElement.innerHTML = '<p class="text-sm text-slate-500 text-center" style="padding-top:60px">Grafiek verschijnt na meerdere health checks.</p>';
}

setTimeout(() => location.reload(), 60000);
</script>

<?php require_once '../views/templates/footer.php'; ?>
