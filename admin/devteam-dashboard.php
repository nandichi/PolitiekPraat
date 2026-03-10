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
@import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@300;400;500;600;700;800&display=swap');

.dt-bg { background: #0f172a; }
.dt-card {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 12px;
    transition: all 0.2s ease;
}
.dt-card:hover { border-color: #475569; }
.dt-mono { font-family: 'JetBrains Mono', monospace; }
.dt-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 10px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}
.dt-green { background: #064e3b; color: #34d399; }
.dt-red { background: #7f1d1d; color: #f87171; }
.dt-yellow { background: #78350f; color: #fbbf24; }
.dt-blue { background: #1e3a5f; color: #60a5fa; }
.dt-purple { background: #3b0764; color: #c084fc; }
.dt-gray { background: #1f2937; color: #9ca3af; }

.dt-stat-card {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid #334155;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
}
.dt-stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }
.dt-stat-label { font-size: 0.8rem; color: #94a3b8; margin-top: 4px; }

.dt-timeline-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 5px;
}
.dt-timeline-line {
    width: 2px;
    background: #334155;
    flex-shrink: 0;
    margin-left: 4px;
}

.dt-route-bar {
    height: 6px;
    border-radius: 3px;
    background: #334155;
    overflow: hidden;
}
.dt-route-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.dt-cron-row {
    display: grid;
    grid-template-columns: 1fr auto auto auto;
    gap: 12px;
    align-items: center;
    padding: 10px 16px;
    border-bottom: 1px solid #1e293b;
}
.dt-cron-row:last-child { border-bottom: none; }

@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
.dt-pulse { animation: pulse-dot 2s ease-in-out infinite; }

@media (max-width: 1024px) {
    .dt-grid-2 { grid-template-columns: 1fr !important; }
}
@media (max-width: 640px) {
    .dt-stats-grid { grid-template-columns: 1fr 1fr !important; }
}
</style>

<main class="dt-bg min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    DevTeam Dashboard
                </h1>
                <p class="text-slate-400 text-sm mt-1">PolitiekPraat -- Virtueel ICT-Bedrijf</p>
            </div>
            <div class="flex items-center gap-3 mt-3 md:mt-0">
                <?php if ($state && $state['last_updated']): ?>
                    <span class="text-slate-500 text-xs dt-mono">Laatst bijgewerkt: <?= htmlspecialchars($state['last_updated']) ?></span>
                <?php endif; ?>
                <a href="dashboard.php" class="text-sm bg-slate-700 hover:bg-slate-600 text-slate-200 px-4 py-2 rounded-lg transition">
                    Admin Panel
                </a>
                <a href="devteam-dashboard.php" class="text-sm bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg transition">
                    Ververs
                </a>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 dt-stats-grid">
            <!-- Site status -->
            <div class="dt-stat-card">
                <?php
                $site_up = $state['site_status']['up'] ?? null;
                $site_ms = $state['site_status']['avg_ms'] ?? null;
                ?>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-3 h-3 rounded-full <?= $site_up === true ? 'bg-emerald-400 dt-pulse' : ($site_up === false ? 'bg-red-400 dt-pulse' : 'bg-slate-500') ?>"></div>
                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Site</span>
                </div>
                <div class="dt-stat-value <?= $site_up === true ? 'text-emerald-400' : ($site_up === false ? 'text-red-400' : 'text-slate-500') ?>">
                    <?= $site_up === true ? 'UP' : ($site_up === false ? 'DOWN' : '--') ?>
                </div>
                <div class="dt-stat-label"><?= $site_ms !== null ? "{$site_ms}ms gem." : 'Geen data' ?></div>
            </div>

            <!-- Open issues -->
            <div class="dt-stat-card">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><line x1="12" y1="8" x2="12" y2="12" stroke-width="2"/><circle cx="12" cy="16" r="0.5" fill="currentColor"/></svg>
                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Issues</span>
                </div>
                <div class="dt-stat-value text-amber-400"><?= count($real_issues) ?></div>
                <div class="dt-stat-label"><?= count($real_closed) ?> gesloten deze week</div>
            </div>

            <!-- Open PRs -->
            <div class="dt-stat-card">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Open PRs</span>
                </div>
                <div class="dt-stat-value text-blue-400"><?= count($pulls) ?></div>
                <div class="dt-stat-label">wachtend op review</div>
            </div>

            <!-- Last deploy -->
            <div class="dt-stat-card">
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
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Deploy</span>
                </div>
                <div class="dt-stat-value <?= $deploy_status === 'success' ? 'text-emerald-400' : ($deploy_status ? 'text-red-400' : 'text-slate-500') ?>">
                    <?= $deploy_status === 'success' ? 'OK' : ($deploy_status ?? '--') ?>
                </div>
                <div class="dt-stat-label dt-mono"><?= $deploy_ago ?: 'Nog geen deploy' ?><?= $deploy['commit'] ?? '' ? " ({$deploy['commit']})" : '' ?></div>
            </div>
        </div>

        <!-- Main grid: Activity + Sprint Board -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-6 dt-grid-2" style="grid-template-columns: 3fr 2fr;">
            <!-- Activity Feed -->
            <div class="dt-card p-5">
                <h2 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Activiteiten
                </h2>
                <div class="space-y-0 max-h-96 overflow-y-auto pr-2">
                    <?php
                    $feed = $state['activity_feed'] ?? [];
                    if (empty($feed)):
                    ?>
                        <p class="text-slate-500 text-sm">Nog geen activiteiten geregistreerd. De eerste cron-runs vullen dit automatisch.</p>
                    <?php else:
                        foreach (array_slice($feed, 0, 20) as $event):
                            $color_class = ($event['ok'] ?? false) ? 'bg-emerald-400' : 'bg-red-400';
                            $role_badge = match($event['role'] ?? '') {
                                'Tech Lead' => 'dt-purple',
                                'Developer' => 'dt-blue',
                                'Code Reviewer' => 'dt-yellow',
                                'DevOps' => 'dt-green',
                                'QA Tester' => 'dt-gray',
                                'SEO Specialist' => 'dt-red',
                                default => 'dt-gray',
                            };
                    ?>
                        <div class="flex gap-3 py-2">
                            <div class="flex flex-col items-center">
                                <div class="dt-timeline-dot <?= $color_class ?>"></div>
                                <div class="dt-timeline-line flex-1 mt-1"></div>
                            </div>
                            <div class="flex-1 min-w-0 pb-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="dt-badge <?= $role_badge ?>"><?= htmlspecialchars($event['role'] ?? 'Onbekend') ?></span>
                                    <span class="text-slate-500 text-xs dt-mono"><?= htmlspecialchars($event['ts'] ?? '') ?></span>
                                </div>
                                <p class="text-slate-300 text-sm mt-1 truncate"><?= htmlspecialchars($event['msg'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Sprint Board -->
            <div class="dt-card p-5">
                <h2 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Sprint Board
                </h2>
                <?php
                $sb = $state['sprint_board'] ?? [];
                $columns = [
                    'planned' => ['label' => 'Gepland', 'color' => 'text-slate-400', 'border' => 'border-slate-500'],
                    'in_progress' => ['label' => 'In Progress', 'color' => 'text-blue-400', 'border' => 'border-blue-500'],
                    'in_review' => ['label' => 'In Review', 'color' => 'text-amber-400', 'border' => 'border-amber-500'],
                    'merged' => ['label' => 'Gemerged', 'color' => 'text-emerald-400', 'border' => 'border-emerald-500'],
                ];
                $has_tasks = false;
                foreach ($columns as $key => $col):
                    $tasks = $sb[$key] ?? [];
                    if (!empty($tasks)) $has_tasks = true;
                ?>
                    <div class="mb-3">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-2 h-2 rounded-full <?= str_replace('text-', 'bg-', $col['color']) ?>"></div>
                            <span class="<?= $col['color'] ?> text-xs font-semibold uppercase tracking-wider"><?= $col['label'] ?></span>
                            <span class="text-slate-600 text-xs">(<?= count($tasks) ?>)</span>
                        </div>
                        <?php if (!empty($tasks)): foreach ($tasks as $task): ?>
                            <div class="ml-4 py-1">
                                <span class="text-slate-300 text-sm"><?= htmlspecialchars(is_string($task) ? $task : ($task['title'] ?? json_encode($task))) ?></span>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="ml-4 py-1"><span class="text-slate-600 text-xs">Leeg</span></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach;
                if (!$has_tasks): ?>
                    <p class="text-slate-500 text-sm mt-2">Sprint board vult zich na de eerste Tech Lead stand-up.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cron Jobs -->
        <div class="dt-card mb-6">
            <div class="p-5 pb-2">
                <h2 class="text-white font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Cron Jobs
                </h2>
            </div>
            <div class="px-2 pb-3">
                <?php
                $cron_defs = [
                    ['id' => 'pp-devteam-pc-onboarding', 'name' => 'PC Setup', 'schedule' => '06:00', 'role' => 'DevOps'],
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
                    <div>
                        <span class="text-slate-200 text-sm font-medium"><?= $cron['name'] ?></span>
                        <span class="text-slate-500 text-xs ml-2"><?= $cron['role'] ?></span>
                    </div>
                    <span class="text-slate-400 text-xs dt-mono"><?= $cron['schedule'] ?></span>
                    <span class="text-slate-500 text-xs dt-mono"><?= $last_run ? htmlspecialchars(substr($last_run, 11, 5)) : '--:--' ?></span>
                    <?php if ($status === 'ok'): ?>
                        <span class="dt-badge dt-green">OK<?= $duration ? ' ' . round($duration / 1000) . 's' : '' ?></span>
                    <?php elseif ($status === 'error'): ?>
                        <span class="dt-badge dt-red">FOUT</span>
                    <?php else: ?>
                        <span class="dt-badge dt-gray">Wacht</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Health Check Routes -->
        <div class="dt-card mb-6 p-5">
            <h2 class="text-white font-semibold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Site Health
                <?php if ($state['health_check']['avg_ms'] ?? null): ?>
                    <span class="text-slate-500 text-xs font-normal ml-2">gem. <?= $state['health_check']['avg_ms'] ?>ms</span>
                <?php endif; ?>
            </h2>
            <?php
            $routes = $state['health_check']['routes'] ?? [];
            $apis = $state['health_check']['apis'] ?? [];
            if (empty($routes)):
            ?>
                <p class="text-slate-500 text-sm">Nog geen health check data. Wordt gevuld na de eerste QA-run.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <?php foreach (array_merge($routes, $apis) as $r):
                        $route_name = $r['route'] ?? $r['endpoint'] ?? '?';
                        $ms = round(($r['time_s'] ?? 0) * 1000);
                        $level = $r['level'] ?? 'FAIL';
                        $max_ms = 2000;
                        $bar_pct = min(100, ($ms / $max_ms) * 100);
                        $bar_color = match($level) {
                            'OK' => 'bg-emerald-400',
                            'SLOW' => 'bg-amber-400',
                            default => 'bg-red-400',
                        };
                    ?>
                    <div class="bg-slate-800/50 rounded-lg p-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-slate-300 text-sm dt-mono"><?= htmlspecialchars($route_name) ?></span>
                            <span class="text-xs dt-mono <?= $level === 'OK' ? 'text-emerald-400' : ($level === 'SLOW' ? 'text-amber-400' : 'text-red-400') ?>"><?= $ms ?>ms</span>
                        </div>
                        <div class="dt-route-bar">
                            <div class="dt-route-fill <?= $bar_color ?>" style="width: <?= $bar_pct ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- GitHub: Commits + Issues -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6 dt-grid-2">
            <!-- Recent Commits -->
            <div class="dt-card p-5">
                <h2 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Recent Commits
                </h2>
                <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                    <?php if (empty($commits)): ?>
                        <p class="text-slate-500 text-sm">Kan commits niet laden van GitHub API.</p>
                    <?php else: foreach (array_slice($commits, 0, 10) as $c):
                        $sha = substr($c['sha'] ?? '', 0, 7);
                        $msg = $c['commit']['message'] ?? '';
                        $msg_first = strtok($msg, "\n");
                        $author = $c['commit']['author']['name'] ?? '?';
                        $date = $c['commit']['author']['date'] ?? '';
                        $date_short = $date ? date('d M H:i', strtotime($date)) : '';
                    ?>
                        <div class="flex items-start gap-3 py-1.5 border-b border-slate-700/50 last:border-0">
                            <code class="text-indigo-400 text-xs dt-mono flex-shrink-0 mt-0.5"><?= $sha ?></code>
                            <div class="min-w-0 flex-1">
                                <p class="text-slate-200 text-sm truncate"><?= htmlspecialchars($msg_first) ?></p>
                                <span class="text-slate-500 text-xs"><?= htmlspecialchars($author) ?> -- <?= $date_short ?></span>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Issues -->
            <div class="dt-card p-5">
                <h2 class="text-white font-semibold mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Open Issues
                    <span class="text-slate-500 text-xs font-normal">(<?= count($real_issues) ?>)</span>
                </h2>
                <?php if (!empty($label_counts)): ?>
                <div class="flex flex-wrap gap-2 mb-3">
                    <?php foreach ($label_counts as $lbl => $cnt):
                        $lbl_class = match(true) {
                            str_contains($lbl, 'bug') => 'dt-red',
                            str_contains($lbl, 'security') => 'dt-red',
                            str_contains($lbl, 'performance') => 'dt-yellow',
                            str_contains($lbl, 'enhancement') => 'dt-blue',
                            str_contains($lbl, 'refactor') => 'dt-purple',
                            default => 'dt-gray',
                        };
                    ?>
                        <span class="dt-badge <?= $lbl_class ?>"><?= htmlspecialchars($lbl) ?>: <?= $cnt ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    <?php if (empty($real_issues)): ?>
                        <p class="text-slate-500 text-sm">Geen open issues.</p>
                    <?php else: foreach (array_slice(array_values($real_issues), 0, 10) as $issue):
                        $labels_html = '';
                        foreach ($issue['labels'] ?? [] as $l) {
                            $lc = match(true) {
                                str_contains($l['name'], 'bug') => 'dt-red',
                                str_contains($l['name'], 'security') => 'dt-red',
                                str_contains($l['name'], 'performance') => 'dt-yellow',
                                default => 'dt-gray',
                            };
                            $labels_html .= "<span class='dt-badge {$lc} ml-1'>{$l['name']}</span>";
                        }
                    ?>
                        <div class="flex items-start gap-2 py-1.5 border-b border-slate-700/50 last:border-0">
                            <span class="text-slate-500 text-xs dt-mono flex-shrink-0 mt-0.5">#<?= $issue['number'] ?></span>
                            <div class="min-w-0 flex-1">
                                <p class="text-slate-200 text-sm">
                                    <a href="<?= htmlspecialchars($issue['html_url'] ?? '#') ?>" target="_blank" class="hover:text-indigo-400 transition"><?= htmlspecialchars($issue['title'] ?? '?') ?></a>
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
        <div class="dt-card p-5 mb-6">
            <h2 class="text-white font-semibold mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Open Pull Requests
            </h2>
            <div class="space-y-3">
                <?php foreach ($pulls as $pr):
                    $pr_date = $pr['created_at'] ? date('d M H:i', strtotime($pr['created_at'])) : '';
                ?>
                <div class="flex items-center gap-3 py-2 border-b border-slate-700/50 last:border-0">
                    <span class="dt-badge dt-blue">#<?= $pr['number'] ?></span>
                    <div class="flex-1 min-w-0">
                        <a href="<?= htmlspecialchars($pr['html_url'] ?? '#') ?>" target="_blank" class="text-slate-200 text-sm hover:text-indigo-400 transition truncate block">
                            <?= htmlspecialchars($pr['title'] ?? '?') ?>
                        </a>
                    </div>
                    <span class="text-slate-500 text-xs dt-mono"><?= $pr_date ?></span>
                    <span class="text-emerald-400 text-xs dt-mono">+<?= $pr['additions'] ?? 0 ?></span>
                    <span class="text-red-400 text-xs dt-mono">-<?= $pr['deletions'] ?? 0 ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="text-center text-slate-600 text-xs pb-6 dt-mono">
            PolitiekPraat DevTeam Dashboard v1.0 -- data vernieuwd via cron jobs op CachyOS PC
        </div>
    </div>
</main>

<script>
setTimeout(() => location.reload(), 60000);
</script>

<?php require_once '../views/templates/footer.php'; ?>
