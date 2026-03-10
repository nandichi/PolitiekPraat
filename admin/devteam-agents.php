<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('login.php');
}

function hex2rgba(string $hex, float $alpha): string {
    $r = hexdec(substr($hex, 1, 2));
    $g = hexdec(substr($hex, 3, 2));
    $b = hexdec(substr($hex, 5, 2));
    return "rgba({$r},{$g},{$b},{$alpha})";
}

$state_file = __DIR__ . '/devteam-state.json';
$state = null;
if (file_exists($state_file)) {
    $state = json_decode(file_get_contents($state_file), true);
}

if (isset($_GET['api'])) {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache');
    if ($_GET['api'] === 'state') {
        echo json_encode($state ?? ['error' => 'no state']);
    } elseif ($_GET['api'] === 'queue') {
        $qf = __DIR__ . '/devteam-queue.json';
        echo json_encode(file_exists($qf) ? json_decode(file_get_contents($qf), true) : ['commands' => []]);
    }
    exit;
}

$agents = [
    ['id'=>'pp-devteam-pc-onboarding','name'=>'Atlas','role'=>'DevOps Engineer','desc'=>'Infrastructuur, SSH-verbindingen, tooling setup en systeemconfiguratie.','schedule'=>'Dagelijks 06:00','icon'=>'M5 12h14M12 5l7 7-7 7','color'=>'#34d399'],
    ['id'=>'pp-devteam-techlead-01','name'=>'Nova','role'=>'Tech Lead','desc'=>'Plant sprints, verdeelt taken, bewaakt architectuur en kwaliteit.','schedule'=>'Dagelijks 07:00','icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','color'=>'#c084fc'],
    ['id'=>'pp-devteam-dev-ochtend-01','name'=>'Pulse','role'=>'Backend Developer','desc'=>'Ochtendsprint: bug fixes, performance optimalisaties, nieuwe features.','schedule'=>'Dagelijks 09:00','icon'=>'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4','color'=>'#60a5fa'],
    ['id'=>'pp-devteam-review-01','name'=>'Sentinel','role'=>'Code Reviewer','desc'=>'Bekijkt open PRs, controleert codekwaliteit, security en standaarden.','schedule'=>'Dagelijks 11:00','icon'=>'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z','color'=>'#fbbf24'],
    ['id'=>'pp-devteam-dev-middag-01','name'=>'Forge','role'=>'Full-Stack Developer','desc'=>'Middagsprint: complexe features, frontend verbeteringen, refactoring.','schedule'=>'Dagelijks 14:00','icon'=>'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z','color'=>'#38bdf8'],
    ['id'=>'pp-devteam-dev-avond-01','name'=>'Echo','role'=>'Frontend Developer','desc'=>'Avondsprint: UI/UX verbeteringen, Tailwind updates, afronding.','schedule'=>'Dagelijks 17:00','icon'=>'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01','color'=>'#818cf8'],
    ['id'=>'pp-devteam-deploy-qa-01','name'=>'Guardian','role'=>'QA & Deploy','desc'=>'Voert deploys uit, draait health checks, rollback bij fouten.','schedule'=>'Dagelijks 20:30','icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','color'=>'#f472b6'],
    ['id'=>'pp-devteam-seo-01','name'=>'Compass','role'=>'SEO Specialist','desc'=>'Wekelijkse SEO audit, meta tags, sitemap, structured data.','schedule'=>'Maandag 10:00','icon'=>'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z','color'=>'#fb923c'],
];

$queue_file = __DIR__ . '/devteam-queue.json';
$queue = file_exists($queue_file) ? json_decode(file_get_contents($queue_file), true) : ['commands' => []];
$pending_agents = [];
foreach ($queue['commands'] ?? [] as $cmd) {
    if (in_array($cmd['status'], ['pending', 'running'])) {
        $pending_agents[$cmd['agent_id']] = $cmd['status'];
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Agent Monitor -- PolitiekPraat</title>
    <link rel="icon" href="/public/images/favicon-512x512.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Space Grotesk', 'system-ui', 'sans-serif'], mono: ['IBM Plex Mono', 'monospace'] },
                colors: {
                    pp: { deep: '#0a1e33', dark: '#0f2a44', DEFAULT: '#1a365d', light: '#2d4a6b', surface: '#112240' },
                    ppred: { DEFAULT: '#c41e3a' },
                    ppgold: { DEFAULT: '#F59E0B', light: '#FCD34D' },
                }
            }
        }
    }
    </script>
    <style>
        body { background: #0a1e33; color: #ccd6e6; }
        .ag-scroll::-webkit-scrollbar { width: 3px; }
        .ag-scroll::-webkit-scrollbar-track { background: transparent; }
        .ag-scroll::-webkit-scrollbar-thumb { background: rgba(45,74,107,0.5); border-radius: 2px; }
        @keyframes ring-pulse { 0%,100%{opacity:0.4} 50%{opacity:1} }
        @keyframes live-blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
        @keyframes fade-up { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        @keyframes typing-dot { 0%{opacity:0.3} 50%{opacity:1} 100%{opacity:0.3} }
        .ag-fade { animation: fade-up 0.5s ease-out both; }
        .typing-dots span {
            animation: typing-dot 1.4s ease-in-out infinite;
            display: inline-block; width: 5px; height: 5px;
            border-radius: 50%; background: currentColor; margin: 0 2px;
        }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        #chatPanel {
            position: fixed; top: 0; right: 0; width: 400px; max-width: 92vw;
            height: 100vh; z-index: 9999;
            background: #0f2a44; border-left: 1px solid rgba(45,74,107,0.6);
            display: flex; flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -8px 0 40px rgba(0,0,0,0.5);
        }
        #chatPanel.open { transform: translateX(0); }
        #chatOverlay {
            position: fixed; inset: 0; z-index: 9998;
            background: rgba(0,0,0,0.5); opacity: 0;
            pointer-events: none; transition: opacity 0.3s;
        }
        #chatOverlay.open { opacity: 1; pointer-events: auto; }
        .chat-bubble { max-width: 85%; padding: 10px 14px; border-radius: 14px; font-size: 0.85rem; line-height: 1.6; word-wrap: break-word; }
        .chat-user { background: #1a365d; color: #e6ecf5; border-bottom-right-radius: 4px; }
        .chat-agent { background: #1d3461; color: #ccd6e6; border-bottom-left-radius: 4px; }

        @media (max-width: 640px) {
            #chatPanel { width: 100%; max-width: 100vw; }
        }
    </style>
</head>
<body class="font-sans min-h-screen">

    <!-- Hero -->
    <header class="relative overflow-hidden" style="background:linear-gradient(135deg,#1a365d 0%,#1e4178 50%,#254b8f 100%)">
        <div class="absolute inset-0 pointer-events-none" style="background:radial-gradient(ellipse 600px 300px at 20% 50%,rgba(245,158,11,0.06),transparent),radial-gradient(ellipse 400px 400px at 80% 30%,rgba(196,30,58,0.04),transparent)"></div>
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-8 md:py-10 relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1.5">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Live Agent Monitor</h1>
                    </div>
                    <p class="text-blue-200/50 text-sm pl-[52px]">Bekijk, start en chat met de AI-agents van PolitiekPraat</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-1.5 text-red-300 text-xs font-semibold">
                        <div class="w-1.5 h-1.5 rounded-full bg-red-400" style="box-shadow:0 0 8px #f87171;animation:live-blink 1.5s ease-in-out infinite"></div>
                        LIVE
                    </div>
                    <a href="devteam-dashboard.php" class="text-sm bg-white/10 hover:bg-white/15 text-white/80 px-4 py-2 rounded-xl transition border border-white/10 no-underline">Dashboard</a>
                    <a href="dashboard.php" class="text-sm bg-white/5 hover:bg-white/10 text-white/60 px-4 py-2 rounded-xl transition border border-white/5 no-underline">Admin</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-5 sm:px-8 py-8 space-y-8">
        <!-- Agent Cards -->
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <?php foreach ($agents as $i => $agent):
                $cs = ($state['cron_states'] ?? [])[$agent['id']] ?? null;
                $status = $cs['status'] ?? null;
                $last_run = $cs['last_run'] ?? null;
                $duration = $cs['duration_ms'] ?? null;
                $is_active = false;
                if ($last_run) {
                    $since = time() - strtotime($last_run);
                    $is_active = $since < ($duration ? ($duration / 1000) + 120 : 300);
                }
                $queue_status = $pending_agents[$agent['id']] ?? null;
                if ($queue_status) $is_active = true;

                $status_text = $queue_status === 'running' ? 'Bezig...' : ($queue_status === 'pending' ? 'In wachtrij' : ($is_active ? 'Actief' : ($status === 'ok' ? 'Idle' : ($status === 'error' ? 'Fout' : 'Wacht'))));
                $status_color = $is_active ? '#34d399' : ($status === 'ok' ? '#60a5fa' : ($status === 'error' ? '#f87171' : '#64748b'));
                $ac = $agent['color'];
            ?>
            <div class="bg-pp-surface border border-pp-light/30 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 hover:border-pp-light/60 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/20 ag-fade" style="animation-delay:<?= $i * 0.06 ?>s" id="agent-<?= $agent['id'] ?>">
                <div class="absolute top-0 left-0 right-0 h-[3px] rounded-t-2xl" style="background:<?= $ac ?>;opacity:<?= $is_active ? '1' : '0.25' ?>"></div>

                <?php if ($is_active): ?>
                <div class="absolute inset-[-3px] rounded-[19px] border-2 pointer-events-none" style="border-color:<?= $ac ?>;opacity:0.4;animation:ring-pulse 2s ease-in-out infinite"></div>
                <?php endif; ?>

                <div class="flex items-start gap-3.5 mb-4">
                    <div class="w-12 h-12 rounded-[14px] flex items-center justify-center flex-shrink-0" style="background:<?= hex2rgba($ac, 0.08) ?>;border:1px solid <?= hex2rgba($ac, 0.15) ?>">
                        <svg class="w-[22px] h-[22px]" style="color:<?= $ac ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $agent['icon'] ?>"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-sm text-slate-200"><?= $agent['name'] ?></span>
                            <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:<?= $status_color ?>;box-shadow:0 0 6px <?= $status_color ?>"></div>
                        </div>
                        <span class="text-xs font-medium" style="color:<?= $ac ?>"><?= $agent['role'] ?></span>
                    </div>
                </div>

                <p class="text-xs text-slate-400 mb-4 leading-relaxed"><?= $agent['desc'] ?></p>

                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-semibold" style="background:<?= hex2rgba($status_color, 0.1) ?>;color:<?= $status_color ?>;border:1px solid <?= hex2rgba($status_color, 0.15) ?>">
                        <?php if ($is_active): ?><span class="typing-dots mr-1.5"><span></span><span></span><span></span></span><?php endif; ?>
                        <?= $status_text ?>
                    </span>
                    <span class="text-xs font-mono text-slate-500"><?= $last_run ? date('H:i', strtotime($last_run)) : $agent['schedule'] ?></span>
                </div>

                <div class="flex gap-2 pt-4 border-t border-white/5">
                    <button onclick="triggerAgent('<?= $agent['id'] ?>','<?= $agent['name'] ?>')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition border text-emerald-400 border-emerald-500/20 bg-emerald-500/10 hover:bg-emerald-500/20 cursor-pointer" <?= $queue_status ? 'disabled style="opacity:0.4;cursor:not-allowed"' : '' ?>>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        Start
                    </button>
                    <button onclick="openChat('<?= $agent['id'] ?>','<?= $agent['name'] ?>','<?= $ac ?>','<?= $agent['role'] ?>')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition border text-blue-400 border-blue-500/20 bg-blue-500/10 hover:bg-blue-500/20 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Chat
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </section>

        <!-- Live Feed -->
        <section class="bg-pp-surface border border-pp-light/30 rounded-2xl ag-fade" style="animation-delay:0.5s">
            <div class="p-6 pb-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2.5">
                    <svg class="w-[18px] h-[18px] text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Live Activiteiten Feed
                </h2>
                <span class="text-xs font-mono text-slate-500" id="feed-count">--</span>
            </div>
            <div class="max-h-[32rem] overflow-y-auto ag-scroll" id="feed-container">
                <?php
                $feed = $state['activity_feed'] ?? [];
                if (empty($feed)):
                ?>
                    <div class="p-8 text-center">
                        <p class="text-sm text-slate-500">Nog geen activiteiten geregistreerd.</p>
                        <p class="text-xs mt-2 text-slate-600">De feed vult zich automatisch zodra agents hun eerste taken uitvoeren.</p>
                    </div>
                <?php else:
                    foreach ($feed as $event):
                        $role = $event['role'] ?? '';
                        $agent_info = null;
                        foreach ($agents as $a) {
                            if (stripos($a['role'], $role) !== false || stripos($role, explode(' ', $a['role'])[0]) !== false || $a['name'] === $role) {
                                $agent_info = $a;
                                break;
                            }
                        }
                        $is_ok = $event['ok'] ?? false;
                        $ev_color = $agent_info['color'] ?? '#8892a8';
                ?>
                    <div class="flex gap-3.5 px-6 py-3.5 border-b border-white/[0.04] hover:bg-white/[0.02] transition-colors">
                        <div class="flex flex-col items-center flex-shrink-0" style="min-width:44px">
                            <span class="text-xs font-mono text-slate-500"><?= $event['ts'] ? date('H:i', strtotime($event['ts'])) : '--:--' ?></span>
                            <span class="text-[10px] font-mono text-slate-600"><?= $event['ts'] ? date('d/m', strtotime($event['ts'])) : '' ?></span>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-3 h-3 rounded-full" style="background:<?= $is_ok ? $ev_color : '#f87171' ?>;box-shadow:0 0 6px <?= hex2rgba($is_ok ? $ev_color : '#f87171', 0.3) ?>"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <?php if ($agent_info): ?>
                                    <span class="text-xs font-semibold" style="color:<?= $ev_color ?>"><?= $agent_info['name'] ?></span>
                                <?php endif; ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wide" style="background:<?= hex2rgba($ev_color, 0.1) ?>;color:<?= $ev_color ?>;border:1px solid <?= hex2rgba($ev_color, 0.15) ?>"><?= htmlspecialchars($role) ?></span>
                                <?php if (!$is_ok): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-red-500/10 text-red-400 border border-red-500/20">FOUT</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-slate-300 leading-relaxed"><?= htmlspecialchars($event['msg'] ?? '') ?></p>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </section>

        <footer class="text-center text-xs font-mono text-slate-600/40 pt-4 pb-2">
            PolitiekPraat DevTeam -- Live Agent Monitor v3.0
        </footer>
    </main>

    <!-- Chat Panel -->
    <div id="chatOverlay" onclick="closeChat()"></div>
    <div id="chatPanel">
        <div class="flex items-center justify-between p-5 border-b border-white/[0.06]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" id="chatAvatar" style="background:rgba(96,165,250,0.1);border:1px solid rgba(96,165,250,0.15)">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div>
                    <div class="text-sm font-semibold text-slate-200" id="chatAgentName">Agent</div>
                    <div class="text-xs text-slate-500" id="chatAgentRole">Rol</div>
                </div>
            </div>
            <button onclick="closeChat()" class="text-slate-400 hover:text-white transition p-1.5 rounded-lg hover:bg-white/5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5 space-y-4 ag-scroll" id="chatMessages">
            <div class="text-center text-xs text-slate-500 py-8">Begin een gesprek met de agent.</div>
        </div>
        <div class="p-4 border-t border-white/[0.06]">
            <form onsubmit="sendChat(event)" class="flex gap-2.5">
                <input type="text" id="chatInput" placeholder="Typ een bericht..." class="flex-1 rounded-xl px-4 py-2.5 text-sm placeholder-slate-500 focus:outline-none" style="background:#0a1e33;border:1px solid rgba(45,74,107,0.4);color:#e6ecf5" autocomplete="off">
                <button type="submit" class="px-4 py-2.5 rounded-xl transition text-sm font-semibold bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>

    <script>
    let currentChatAgent = null;
    let currentChatName = '';
    let chatPollTimer = null;

    function triggerAgent(id, name) {
        const fd = new FormData();
        fd.append('agent_id', id);
        fd.append('agent_name', name);
        fetch('devteam-api.php?action=trigger', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.ok) {
                    alert(name + ' is getriggerd. De agent start binnen enkele minuten.');
                    location.reload();
                } else {
                    alert(d.msg || d.error || 'Onbekende fout');
                }
            })
            .catch(() => alert('API niet bereikbaar.'));
    }

    function openChat(agentId, name, color, role) {
        currentChatAgent = agentId;
        currentChatName = name;
        document.getElementById('chatAgentName').textContent = name;
        document.getElementById('chatAgentRole').textContent = role;
        const avatar = document.getElementById('chatAvatar');
        avatar.style.background = hexToRgba(color, 0.1);
        avatar.style.border = '1px solid ' + hexToRgba(color, 0.15);
        avatar.querySelector('svg').style.color = color;
        document.getElementById('chatPanel').classList.add('open');
        document.getElementById('chatOverlay').classList.add('open');
        document.getElementById('chatInput').focus();
        loadChatHistory(agentId);
        if (chatPollTimer) clearInterval(chatPollTimer);
        chatPollTimer = setInterval(() => loadChatHistory(agentId), 5000);
    }

    function closeChat() {
        document.getElementById('chatPanel').classList.remove('open');
        document.getElementById('chatOverlay').classList.remove('open');
        currentChatAgent = null;
        if (chatPollTimer) { clearInterval(chatPollTimer); chatPollTimer = null; }
    }

    function loadChatHistory(agentId) {
        fetch('devteam-api.php?action=chat_history&agent_id=' + agentId + '&_=' + Date.now())
            .then(r => r.json())
            .then(d => {
                const container = document.getElementById('chatMessages');
                const msgs = d.messages || [];
                if (msgs.length === 0) {
                    container.innerHTML = '<div class="text-center text-xs text-slate-500 py-8">Begin een gesprek met ' + esc(currentChatName) + '. Het antwoord komt binnen enkele minuten.</div>';
                    return;
                }
                let html = '';
                for (const m of msgs) {
                    const isUser = m.from === 'user';
                    const time = m.ts ? m.ts.substring(11, 16) : '';
                    html += '<div class="flex flex-col ' + (isUser ? 'items-end' : 'items-start') + '">' +
                        '<div class="chat-bubble ' + (isUser ? 'chat-user' : 'chat-agent') + '">' + esc(m.msg) + '</div>' +
                        '<span class="text-[10px] text-slate-600 mt-1 font-mono">' + time + '</span></div>';
                }
                container.innerHTML = html;
                container.scrollTop = container.scrollHeight;
            })
            .catch(() => {});
    }

    function sendChat(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const msg = input.value.trim();
        if (!msg || !currentChatAgent) return;
        input.value = '';

        const container = document.getElementById('chatMessages');
        container.innerHTML += '<div class="flex flex-col items-end"><div class="chat-bubble chat-user">' + esc(msg) + '</div><span class="text-[10px] text-slate-600 mt-1 font-mono">nu</span></div>';
        container.innerHTML += '<div class="flex flex-col items-start" id="typing-indicator"><div class="chat-bubble chat-agent"><span class="typing-dots"><span></span><span></span><span></span></span></div></div>';
        container.scrollTop = container.scrollHeight;

        const fd = new FormData();
        fd.append('agent_id', currentChatAgent);
        fd.append('agent_name', currentChatName);
        fd.append('message', msg);
        fetch('devteam-api.php?action=chat', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (!d.ok) {
                    const ti = document.getElementById('typing-indicator');
                    if (ti) ti.innerHTML = '<div class="chat-bubble chat-agent text-red-400">Fout: ' + esc(d.error || 'onbekend') + '</div>';
                }
            })
            .catch(() => {
                const ti = document.getElementById('typing-indicator');
                if (ti) ti.innerHTML = '<div class="chat-bubble chat-agent text-red-400">API niet bereikbaar.</div>';
            });
    }

    function esc(t) { const d = document.createElement('div'); d.textContent = t; return d.innerHTML; }

    function hexToRgba(hex, a) {
        const r = parseInt(hex.slice(1,3),16), g = parseInt(hex.slice(3,5),16), b = parseInt(hex.slice(5,7),16);
        return 'rgba('+r+','+g+','+b+','+a+')';
    }

    (function() {
        let lastFeed = <?= json_encode(count($state['activity_feed'] ?? [])) ?>;
        const fc = document.getElementById('feed-count');
        if (fc) fc.textContent = lastFeed + ' events';

        setInterval(async () => {
            try {
                const res = await fetch('devteam-agents.php?api=state&_=' + Date.now());
                if (!res.ok) return;
                const data = await res.json();
                if (!data || data.error) return;
                const nl = (data.activity_feed || []).length;
                if (fc) fc.textContent = nl + ' events';
                if (nl !== lastFeed && !document.getElementById('chatPanel').classList.contains('open')) {
                    lastFeed = nl;
                    location.reload();
                }
            } catch(e) {}
        }, 8000);
    })();
    </script>
</body>
</html>
