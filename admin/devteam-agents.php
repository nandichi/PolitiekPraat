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

if (isset($_GET['api']) && $_GET['api'] === 'state') {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache');
    echo json_encode($state ?? ['error' => 'no state']);
    exit;
}

$agents = [
    [
        'id'       => 'pp-devteam-pc-onboarding',
        'name'     => 'Atlas',
        'role'     => 'DevOps Engineer',
        'desc'     => 'Infrastructuur, SSH-verbindingen, tooling setup en systeemconfiguratie.',
        'schedule' => 'Dagelijks 06:00',
        'icon'     => 'M5 12h14M12 5l7 7-7 7',
        'color'    => '#34d399',
    ],
    [
        'id'       => 'pp-devteam-techlead-01',
        'name'     => 'Nova',
        'role'     => 'Tech Lead',
        'desc'     => 'Plant sprints, verdeelt taken, bewaakt architectuur en kwaliteit.',
        'schedule' => 'Dagelijks 07:00',
        'icon'     => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
        'color'    => '#c084fc',
    ],
    [
        'id'       => 'pp-devteam-dev-ochtend-01',
        'name'     => 'Pulse',
        'role'     => 'Backend Developer',
        'desc'     => 'Ochtendsprint: bug fixes, performance optimalisaties, nieuwe features.',
        'schedule' => 'Dagelijks 09:00',
        'icon'     => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
        'color'    => '#60a5fa',
    ],
    [
        'id'       => 'pp-devteam-review-01',
        'name'     => 'Sentinel',
        'role'     => 'Code Reviewer',
        'desc'     => 'Bekijkt open PRs, controleert codekwaliteit, security en standaarden.',
        'schedule' => 'Dagelijks 11:00',
        'icon'     => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
        'color'    => '#fbbf24',
    ],
    [
        'id'       => 'pp-devteam-dev-middag-01',
        'name'     => 'Forge',
        'role'     => 'Full-Stack Developer',
        'desc'     => 'Middagsprint: complexe features, frontend verbeteringen, refactoring.',
        'schedule' => 'Dagelijks 14:00',
        'icon'     => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
        'color'    => '#38bdf8',
    ],
    [
        'id'       => 'pp-devteam-dev-avond-01',
        'name'     => 'Echo',
        'role'     => 'Frontend Developer',
        'desc'     => 'Avondsprint: UI/UX verbeteringen, Tailwind updates, afronding.',
        'schedule' => 'Dagelijks 17:00',
        'icon'     => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
        'color'    => '#818cf8',
    ],
    [
        'id'       => 'pp-devteam-deploy-qa-01',
        'name'     => 'Guardian',
        'role'     => 'QA & Deploy',
        'desc'     => 'Voert deploys uit, draait health checks, rollback bij fouten.',
        'schedule' => 'Dagelijks 20:30',
        'icon'     => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'color'    => '#f472b6',
    ],
    [
        'id'       => 'pp-devteam-seo-01',
        'name'     => 'Compass',
        'role'     => 'SEO Specialist',
        'desc'     => 'Wekelijkse SEO audit, meta tags, sitemap, structured data.',
        'schedule' => 'Maandag 10:00',
        'icon'     => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'color'    => '#fb923c',
    ],
];

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
    --pp-accent: #F59E0B;
    --pp-accent-light: #FCD34D;
    --pp-surface: #112240;
    --pp-border: rgba(45, 74, 107, 0.5);
    --pp-text: #ccd6e6;
    --pp-text-muted: #8892a8;
    --pp-text-bright: #e6ecf5;
}

.ag-page {
    font-family: 'Space Grotesk', sans-serif;
    background: var(--pp-primary-deep);
    min-height: 100vh;
}
.ag-mono { font-family: 'IBM Plex Mono', monospace; }

.ag-hero {
    background: linear-gradient(135deg, var(--pp-primary) 0%, #1e4178 50%, #254b8f 100%);
    position: relative;
    overflow: hidden;
}
.ag-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 600px 300px at 20% 50%, rgba(245,158,11,0.06), transparent),
        radial-gradient(ellipse 400px 400px at 80% 30%, rgba(196,30,58,0.04), transparent);
    pointer-events: none;
}

.ag-card {
    background: var(--pp-surface);
    border: 1px solid var(--pp-border);
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.ag-card:hover {
    border-color: var(--pp-primary-light);
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.25);
}
.ag-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    border-radius: 16px 16px 0 0;
}

.ag-avatar {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
}
.ag-avatar svg { width: 22px; height: 22px; }
.ag-avatar-ring {
    position: absolute;
    inset: -3px;
    border-radius: 17px;
    border: 2px solid transparent;
    opacity: 0;
    transition: opacity 0.3s;
}
.ag-active .ag-avatar-ring {
    opacity: 1;
    animation: ring-pulse 2s ease-in-out infinite;
}

.ag-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.ag-log-entry {
    padding: 10px 16px;
    border-bottom: 1px solid rgba(45,74,107,0.15);
    transition: background 0.15s;
    display: flex;
    gap: 12px;
    align-items: flex-start;
}
.ag-log-entry:hover { background: rgba(45,74,107,0.1); }

.ag-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.ag-scroll::-webkit-scrollbar { width: 3px; }
.ag-scroll::-webkit-scrollbar-track { background: transparent; }
.ag-scroll::-webkit-scrollbar-thumb { background: var(--pp-border); border-radius: 2px; }

.ag-live-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}
.ag-live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #f87171;
    box-shadow: 0 0 8px #f87171;
    animation: live-blink 1.5s ease-in-out infinite;
}

@keyframes ring-pulse {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}
@keyframes live-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
@keyframes fade-up {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes typing {
    0% { opacity: 0.3; }
    50% { opacity: 1; }
    100% { opacity: 0.3; }
}
.ag-fade { animation: fade-up 0.5s ease-out both; }
.ag-typing span {
    animation: typing 1.4s ease-in-out infinite;
    display: inline-block;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: currentColor;
    margin: 0 2px;
}
.ag-typing span:nth-child(2) { animation-delay: 0.2s; }
.ag-typing span:nth-child(3) { animation-delay: 0.4s; }

@media (max-width: 1024px) {
    .ag-agents-grid { grid-template-columns: repeat(2, 1fr) !important; }
}
@media (max-width: 640px) {
    .ag-agents-grid { grid-template-columns: 1fr !important; }
    .ag-hero-inner { padding: 1.5rem 1rem; }
    .ag-card { border-radius: 12px; }
}
</style>

<main class="ag-page">
    <!-- Hero -->
    <div class="ag-hero">
        <div class="max-w-7xl mx-auto px-4 py-8 md:py-10 relative z-10 ag-hero-inner">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Live Agent Monitor</h1>
                        </div>
                    </div>
                    <p class="text-blue-200/70 text-sm ml-13">Bekijk in realtime wat de AI-agents doen voor PolitiekPraat</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="ag-live-indicator text-red-300">
                        <div class="ag-live-dot"></div>
                        <span id="live-label">LIVE</span>
                    </div>
                    <a href="devteam-dashboard.php" class="text-sm bg-white/10 hover:bg-white/15 text-white/80 px-4 py-2 rounded-xl transition border border-white/10">
                        Dashboard
                    </a>
                    <a href="dashboard.php" class="text-sm bg-white/5 hover:bg-white/10 text-white/60 px-4 py-2 rounded-xl transition border border-white/5">
                        Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-5 relative z-10 pb-10">
        <!-- Agent Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8 ag-agents-grid">
            <?php foreach ($agents as $i => $agent):
                $cs = ($state['cron_states'] ?? [])[$agent['id']] ?? null;
                $status = $cs['status'] ?? null;
                $last_run = $cs['last_run'] ?? null;
                $duration = $cs['duration_ms'] ?? null;
                $is_active = false;
                if ($last_run) {
                    $since = time() - strtotime($last_run);
                    if ($duration) {
                        $is_active = $since < ($duration / 1000) + 120;
                    } else {
                        $is_active = $since < 300;
                    }
                }
                $status_text = $is_active ? 'Actief' : ($status === 'ok' ? 'Idle' : ($status === 'error' ? 'Fout' : 'Wacht'));
                $status_color = $is_active ? '#34d399' : ($status === 'ok' ? '#60a5fa' : ($status === 'error' ? '#f87171' : '#64748b'));
            ?>
            <div class="ag-card p-4 ag-fade <?= $is_active ? 'ag-active' : '' ?>" style="animation-delay: <?= $i * 0.06 ?>s; --agent-color: <?= $agent['color'] ?>">
                <div style="position:absolute;top:0;left:0;right:0;height:3px;background:<?= $agent['color'] ?>;opacity:<?= $is_active ? '1' : '0.3' ?>"></div>
                <div class="flex items-start gap-3 mb-3">
                    <div class="ag-avatar" style="background: <?= $agent['color'] ?>15; border: 1px solid <?= $agent['color'] ?>30">
                        <div class="ag-avatar-ring" style="border-color: <?= $agent['color'] ?>"></div>
                        <svg style="color: <?= $agent['color'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $agent['icon'] ?>"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-sm" style="color: var(--pp-text-bright)"><?= $agent['name'] ?></span>
                            <div class="ag-status-dot" style="background: <?= $status_color ?>; box-shadow: 0 0 6px <?= $status_color ?>"></div>
                        </div>
                        <span class="text-xs font-medium" style="color: <?= $agent['color'] ?>"><?= $agent['role'] ?></span>
                    </div>
                </div>
                <p class="text-xs mb-3" style="color: var(--pp-text-muted); line-height: 1.5"><?= $agent['desc'] ?></p>
                <div class="flex items-center justify-between">
                    <span class="ag-badge" style="background: <?= $status_color ?>15; color: <?= $status_color ?>; border: 1px solid <?= $status_color ?>25">
                        <?php if ($is_active): ?>
                            <span class="ag-typing mr-1.5"><span></span><span></span><span></span></span>
                        <?php endif; ?>
                        <?= $status_text ?>
                    </span>
                    <span class="text-xs ag-mono" style="color: var(--pp-text-muted)">
                        <?= $last_run ? date('H:i', strtotime($last_run)) : $agent['schedule'] ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Live Feed -->
        <div class="ag-card ag-fade" style="animation-delay: 0.5s">
            <div class="p-5 pb-3 flex items-center justify-between">
                <h2 class="font-semibold flex items-center gap-2" style="color: var(--pp-text-bright)">
                    <svg class="w-5 h-5" style="color: var(--pp-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Live Activiteiten Feed
                </h2>
                <span class="text-xs ag-mono" style="color: var(--pp-text-muted)" id="feed-count">--</span>
            </div>
            <div class="max-h-[32rem] overflow-y-auto ag-scroll" id="feed-container">
                <?php
                $feed = $state['activity_feed'] ?? [];
                if (empty($feed)):
                ?>
                    <div class="p-8 text-center">
                        <p class="text-sm" style="color: var(--pp-text-muted)">Nog geen activiteiten geregistreerd.</p>
                        <p class="text-xs mt-2" style="color: var(--pp-text-muted); opacity: 0.6">De feed vult zich automatisch zodra agents hun eerste taken uitvoeren.</p>
                    </div>
                <?php else:
                    $agent_lookup = [];
                    foreach ($agents as $a) $agent_lookup[$a['role']] = $a;
                    foreach ($feed as $fi => $event):
                        $role = $event['role'] ?? '';
                        $agent_info = null;
                        foreach ($agents as $a) {
                            if (stripos($a['role'], $role) !== false || stripos($role, explode(' ', $a['role'])[0]) !== false) {
                                $agent_info = $a;
                                break;
                            }
                        }
                        $is_ok = $event['ok'] ?? false;
                        $ev_color = $agent_info['color'] ?? '#8892a8';
                ?>
                    <div class="ag-log-entry">
                        <div class="flex flex-col items-center flex-shrink-0" style="min-width: 44px">
                            <span class="text-xs ag-mono" style="color: var(--pp-text-muted)"><?= $event['ts'] ? date('H:i', strtotime($event['ts'])) : '--:--' ?></span>
                            <span class="text-[0.6rem] ag-mono" style="color: var(--pp-text-muted); opacity: 0.5"><?= $event['ts'] ? date('d/m', strtotime($event['ts'])) : '' ?></span>
                        </div>
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-3 h-3 rounded-full" style="background: <?= $is_ok ? $ev_color : '#f87171' ?>; box-shadow: 0 0 6px <?= $is_ok ? $ev_color : '#f87171' ?>40"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                <?php if ($agent_info): ?>
                                    <span class="font-semibold text-xs" style="color: <?= $ev_color ?>"><?= $agent_info['name'] ?></span>
                                <?php endif; ?>
                                <span class="ag-badge" style="background: <?= $ev_color ?>12; color: <?= $ev_color ?>; border: 1px solid <?= $ev_color ?>20; font-size: 0.6rem"><?= htmlspecialchars($role) ?></span>
                                <?php if (!$is_ok): ?>
                                    <span class="ag-badge" style="background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.2); font-size: 0.6rem">FOUT</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm" style="color: var(--pp-text); line-height: 1.5"><?= htmlspecialchars($event['msg'] ?? '') ?></p>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div class="text-center text-xs pb-6 mt-6 ag-mono" style="color: var(--pp-text-muted); opacity: 0.4">
            PolitiekPraat DevTeam -- Live Agent Monitor v1.0
        </div>
    </div>
</main>

<script>
(function() {
    const POLL_INTERVAL = 10000;
    let lastFeed = <?= json_encode(count($state['activity_feed'] ?? [])) ?>;

    const feedCount = document.getElementById('feed-count');
    if (feedCount) feedCount.textContent = lastFeed + ' events';

    async function poll() {
        try {
            const res = await fetch('devteam-agents.php?api=state&_=' + Date.now());
            if (!res.ok) return;
            const data = await res.json();
            if (!data || data.error) return;

            const newFeedLen = (data.activity_feed || []).length;
            if (newFeedLen !== lastFeed) {
                lastFeed = newFeedLen;
                location.reload();
            }

            if (feedCount) feedCount.textContent = newFeedLen + ' events';
        } catch (e) {}
    }

    setInterval(poll, POLL_INTERVAL);

    const label = document.getElementById('live-label');
    if (label) {
        setInterval(() => {
            label.textContent = label.textContent === 'LIVE' ? 'LIVE' : 'LIVE';
        }, 3000);
    }
})();
</script>

<?php require_once '../views/templates/footer.php'; ?>
