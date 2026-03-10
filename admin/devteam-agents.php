<?php
require_once '../includes/config.php';
require_once '../includes/Database.php';
require_once '../includes/functions.php';
if (!isAdmin()) { redirect('login.php'); }

if (isset($_GET['api'])) {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache');
    $state_file = __DIR__ . '/devteam-state.json';
    $state = file_exists($state_file) ? json_decode(file_get_contents($state_file), true) : null;
    if ($_GET['api'] === 'state') echo json_encode($state ?? ['error' => 'no state']);
    elseif ($_GET['api'] === 'queue') {
        $qf = __DIR__ . '/devteam-queue.json';
        echo json_encode(file_exists($qf) ? json_decode(file_get_contents($qf), true) : ['commands' => []]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Agent Monitor -- PolitiekPraat DevTeam</title>
    <link rel="icon" href="/public/images/favicon-512x512.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config={theme:{extend:{fontFamily:{sans:['Space Grotesk','system-ui','sans-serif'],mono:['IBM Plex Mono','monospace']},colors:{pp:{deep:'#0a1e33',dark:'#0f2a44',DEFAULT:'#1a365d',light:'#2d4a6b',surface:'#112240'}}}}}
    </script>
    <style>
        body{background:#0a1e33;color:#ccd6e6}
        .scr::-webkit-scrollbar{width:3px}.scr::-webkit-scrollbar-track{background:transparent}.scr::-webkit-scrollbar-thumb{background:rgba(45,74,107,.5);border-radius:2px}
        @keyframes rp{0%,100%{opacity:.4}50%{opacity:1}}
        @keyframes lb{0%,100%{opacity:1}50%{opacity:.3}}
        @keyframes fu{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        @keyframes td{0%{opacity:.3}50%{opacity:1}100%{opacity:.3}}
        @keyframes spin{to{transform:rotate(360deg)}}
        .fade{animation:fu .4s ease-out both}
        .tdots span{animation:td 1.4s ease-in-out infinite;display:inline-block;width:5px;height:5px;border-radius:50%;background:currentColor;margin:0 2px}
        .tdots span:nth-child(2){animation-delay:.2s}.tdots span:nth-child(3){animation-delay:.4s}
        .spinner{width:14px;height:14px;border:2px solid rgba(255,255,255,.15);border-top-color:currentColor;border-radius:50%;animation:spin .8s linear infinite}
        #cp{position:fixed;top:0;right:0;width:420px;max-width:94vw;height:100vh;z-index:9999;background:#0f2a44;border-left:1px solid rgba(45,74,107,.6);display:flex;flex-direction:column;transform:translateX(100%);transition:transform .3s ease;box-shadow:-8px 0 40px rgba(0,0,0,.5)}
        #cp.open{transform:translateX(0)}
        #co{position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,.5);opacity:0;pointer-events:none;transition:opacity .25s}
        #co.open{opacity:1;pointer-events:auto}
        .cb{max-width:85%;padding:10px 14px;border-radius:14px;font-size:.85rem;line-height:1.6;word-wrap:break-word}
        .cb-u{background:#1a365d;color:#e6ecf5;border-bottom-right-radius:4px}
        .cb-a{background:#1d3461;color:#ccd6e6;border-bottom-left-radius:4px}
        .cb-w{background:rgba(245,158,11,.08);color:#fbbf24;border:1px dashed rgba(245,158,11,.2);font-size:.8rem}
        @media(max-width:640px){#cp{width:100%;max-width:100vw}}
    </style>
</head>
<body class="font-sans min-h-screen">
    <header class="relative overflow-hidden" style="background:linear-gradient(135deg,#1a365d 0%,#1e4178 50%,#254b8f 100%)">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 py-7 md:py-9 relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/10">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Live Agent Monitor</h1>
                    </div>
                    <p class="text-blue-200/50 text-sm pl-[52px]">Start, monitor en chat met de AI-agents van PolitiekPraat</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1.5 text-xs font-semibold" id="liveIndicator"><div class="w-1.5 h-1.5 rounded-full bg-slate-500"></div><span class="text-slate-500">OFFLINE</span></div>
                    <a href="devteam-dashboard.php" class="text-sm bg-white/10 hover:bg-white/15 text-white/80 px-4 py-2 rounded-xl transition border border-white/10 no-underline">Dashboard</a>
                    <a href="dashboard.php" class="text-sm bg-white/5 hover:bg-white/10 text-white/60 px-4 py-2 rounded-xl transition border border-white/5 no-underline">Admin</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-5 sm:px-8 py-8 space-y-8">
        <section id="agents" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="col-span-full text-center py-12 text-slate-500 text-sm">Laden...</div>
        </section>
        <section id="queueSection" class="hidden bg-pp-surface border border-pp-light/30 rounded-2xl p-6"></section>
        <section id="feedSection" class="bg-pp-surface border border-pp-light/30 rounded-2xl"></section>
        <footer class="text-center text-xs font-mono text-slate-600/40 pt-2 pb-2">PolitiekPraat DevTeam -- Live Agent Monitor v3.1</footer>
    </main>

    <div id="co" onclick="App.closeChat()"></div>
    <div id="cp">
        <div class="flex items-center justify-between p-5 border-b border-white/[0.06]" id="cpHead"></div>
        <div class="flex-1 overflow-y-auto p-5 space-y-3 scr" id="cpMsgs"></div>
        <div class="p-4 border-t border-white/[0.06]">
            <form onsubmit="App.sendChat(event)" class="flex gap-2.5">
                <input type="text" id="cpIn" placeholder="Typ een bericht..." autocomplete="off" class="flex-1 rounded-xl px-4 py-2.5 text-sm placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-amber-500/30" style="background:#0a1e33;border:1px solid rgba(45,74,107,.4);color:#e6ecf5">
                <button type="submit" class="px-4 py-2.5 rounded-xl transition text-sm font-semibold bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/20 cursor-pointer">Stuur</button>
            </form>
        </div>
    </div>

<script>
const AGENTS = [
    {id:'pp-devteam-pc-onboarding',name:'Atlas',role:'DevOps Engineer',desc:'Infrastructuur, SSH, tooling setup en systeemconfiguratie.',schedule:'06:00',icon:'M5 12h14M12 5l7 7-7 7',color:'#34d399'},
    {id:'pp-devteam-techlead-01',name:'Nova',role:'Tech Lead',desc:'Plant sprints, verdeelt taken, bewaakt architectuur.',schedule:'07:00',icon:'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',color:'#c084fc'},
    {id:'pp-devteam-dev-ochtend-01',name:'Pulse',role:'Backend Developer',desc:'Ochtendsprint: bugs, performance, features.',schedule:'09:00',icon:'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',color:'#60a5fa'},
    {id:'pp-devteam-review-01',name:'Sentinel',role:'Code Reviewer',desc:'Controleert PRs op kwaliteit, security, standaarden.',schedule:'11:00',icon:'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',color:'#fbbf24'},
    {id:'pp-devteam-dev-middag-01',name:'Forge',role:'Full-Stack Developer',desc:'Middagsprint: complexe features, refactoring.',schedule:'14:00',icon:'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',color:'#38bdf8'},
    {id:'pp-devteam-dev-avond-01',name:'Echo',role:'Frontend Developer',desc:'Avondsprint: UI/UX, Tailwind, afronding.',schedule:'17:00',icon:'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',color:'#818cf8'},
    {id:'pp-devteam-deploy-qa-01',name:'Guardian',role:'QA & Deploy',desc:'Deploys, health checks, rollback bij fouten.',schedule:'20:30',icon:'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',color:'#f472b6'},
    {id:'pp-devteam-seo-01',name:'Compass',role:'SEO Specialist',desc:'Wekelijkse SEO audit, meta tags, sitemap.',schedule:'Ma 10:00',icon:'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',color:'#fb923c'},
];

const App = {
    state: null, queue: null, chatAgent: null, chatName: '', chatColor: '', chatPoll: null, pollOk: false,

    async init() {
        await this.poll();
        setInterval(() => this.poll(), 4000);
    },

    async poll() {
        try {
            const r = await fetch('devteam-api.php?action=full_state&_='+Date.now());
            if (!r.ok) throw new Error(r.status);
            const d = await r.json();
            this.state = d.state || {};
            this.queue = d.queue || {commands:[]};
            this.pollOk = true;
            this.render();
        } catch(e) {
            this.pollOk = false;
            this.updateLive();
        }
    },

    render() {
        this.updateLive();
        this.renderAgents();
        this.renderQueue();
        this.renderFeed();
    },

    updateLive() {
        const el = document.getElementById('liveIndicator');
        if (this.pollOk) {
            el.innerHTML = '<div class="w-1.5 h-1.5 rounded-full bg-emerald-400" style="box-shadow:0 0 8px #34d399;animation:lb 1.5s ease-in-out infinite"></div><span class="text-emerald-400">LIVE</span>';
        } else {
            el.innerHTML = '<div class="w-1.5 h-1.5 rounded-full bg-red-400" style="box-shadow:0 0 6px #f87171"></div><span class="text-red-400">FOUT</span>';
        }
    },

    getAgentQueue(agentId) {
        const cmds = (this.queue?.commands || []).filter(c => c.agent_id === agentId && (c.status === 'pending' || c.status === 'running'));
        return cmds.length > 0 ? cmds[cmds.length - 1] : null;
    },

    getAgentDoneCommands(agentId) {
        return (this.queue?.commands || []).filter(c => c.agent_id === agentId && c.status === 'done').slice(-3).reverse();
    },

    timeAgo(ts) {
        if (!ts) return '';
        const s = Math.floor((Date.now() - new Date(ts).getTime()) / 1000);
        if (s < 60) return s + 's geleden';
        if (s < 3600) return Math.floor(s/60) + 'm geleden';
        if (s < 86400) return Math.floor(s/3600) + 'u geleden';
        return Math.floor(s/86400) + 'd geleden';
    },

    rgba(hex, a) {
        const r=parseInt(hex.slice(1,3),16), g=parseInt(hex.slice(3,5),16), b=parseInt(hex.slice(5,7),16);
        return `rgba(${r},${g},${b},${a})`;
    },

    esc(t) { const d=document.createElement('div'); d.textContent=t; return d.innerHTML; },

    renderAgents() {
        const el = document.getElementById('agents');
        const cron = this.state?.cron_states || {};
        const feed = this.state?.activity_feed || [];

        el.innerHTML = AGENTS.map((a, i) => {
            const cs = cron[a.id];
            const lastRun = cs?.last_run;
            const qCmd = this.getAgentQueue(a.id);
            const doneCmds = this.getAgentDoneCommands(a.id);
            const lastFeedEntry = feed.find(f => f.role === a.name || f.role === a.role || (a.role.split(' ')[0] && f.role?.includes(a.role.split(' ')[0])));

            let statusText, statusColor, isActive = false;
            if (qCmd?.status === 'running') { statusText = 'Bezig...'; statusColor = '#34d399'; isActive = true; }
            else if (qCmd?.status === 'pending') { statusText = 'In wachtrij'; statusColor = '#F59E0B'; isActive = true; }
            else if (cs?.status === 'ok') { statusText = 'Idle'; statusColor = '#60a5fa'; }
            else if (cs?.status === 'error') { statusText = 'Fout'; statusColor = '#f87171'; }
            else { statusText = 'Wacht'; statusColor = '#64748b'; }

            const timeInfo = qCmd ? this.timeAgo(qCmd.ts) : (lastRun ? this.timeAgo(lastRun) : a.schedule);

            let queueDetail = '';
            if (qCmd) {
                queueDetail = `<div class="mt-3 px-3 py-2.5 rounded-lg text-xs" style="background:${this.rgba(statusColor,.06)};border:1px solid ${this.rgba(statusColor,.12)}">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="spinner" style="color:${statusColor};width:12px;height:12px;border-width:1.5px"></div>
                        <span style="color:${statusColor}" class="font-semibold">${qCmd.status === 'running' ? 'Agent is bezig...' : 'Wacht op verwerking'}</span>
                    </div>
                    <span class="text-slate-500">${qCmd.type === 'chat' ? 'Chat: "'+this.esc((qCmd.message||'').substring(0,40))+'..."' : 'Trigger commando'} -- ${this.timeAgo(qCmd.ts)}</span>
                </div>`;
            }

            let doneDetail = '';
            if (!qCmd && doneCmds.length > 0) {
                const last = doneCmds[0];
                const resp = (last.response || '').substring(0, 100);
                doneDetail = `<div class="mt-3 px-3 py-2.5 rounded-lg text-xs" style="background:rgba(45,74,107,.12);border:1px solid rgba(45,74,107,.2)">
                    <div class="flex items-center gap-1.5 mb-1"><svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-emerald-400 font-semibold">Laatste resultaat</span><span class="text-slate-500 ml-auto">${this.timeAgo(last.ts)}</span></div>
                    <span class="text-slate-400 leading-relaxed">${this.esc(resp)}${resp.length >= 100 ? '...' : ''}</span>
                </div>`;
            }

            let lastActivity = '';
            if (lastFeedEntry) {
                lastActivity = `<div class="mt-3 px-3 py-2 rounded-lg text-xs" style="background:rgba(45,74,107,.08)">
                    <span class="text-slate-500">Laatste actie:</span> <span class="text-slate-400">${this.esc((lastFeedEntry.msg||'').substring(0,80))}</span>
                </div>`;
            }

            return `<div class="bg-pp-surface border border-pp-light/30 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 hover:border-pp-light/50 fade" style="animation-delay:${i*0.05}s">
                <div class="absolute top-0 left-0 right-0 h-[3px] rounded-t-2xl" style="background:${a.color};opacity:${isActive?1:.2}"></div>
                ${isActive ? `<div class="absolute inset-[-3px] rounded-[19px] border-2 pointer-events-none" style="border-color:${a.color};opacity:.3;animation:rp 2s ease-in-out infinite"></div>` : ''}
                <div class="flex items-start gap-3.5 mb-3">
                    <div class="w-12 h-12 rounded-[14px] flex items-center justify-center flex-shrink-0" style="background:${this.rgba(a.color,.08)};border:1px solid ${this.rgba(a.color,.15)}">
                        <svg class="w-[22px] h-[22px]" style="color:${a.color}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="${a.icon}"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2"><span class="font-semibold text-sm text-slate-200">${a.name}</span><div class="w-2 h-2 rounded-full" style="background:${statusColor};box-shadow:0 0 6px ${statusColor}"></div></div>
                        <span class="text-xs font-medium" style="color:${a.color}">${a.role}</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mb-3 leading-relaxed">${a.desc}</p>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold" style="background:${this.rgba(statusColor,.1)};color:${statusColor};border:1px solid ${this.rgba(statusColor,.15)}">
                        ${isActive ? '<span class="tdots"><span></span><span></span><span></span></span>' : ''}${statusText}
                    </span>
                    <span class="text-xs font-mono text-slate-500">${timeInfo}</span>
                </div>
                ${queueDetail}${doneDetail}${!qCmd && doneCmds.length === 0 ? lastActivity : ''}
                <div class="flex gap-2 pt-4 mt-4 border-t border-white/5">
                    <button onclick="App.trigger('${a.id}','${a.name}')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg text-xs font-semibold transition border cursor-pointer ${qCmd ? 'opacity-40 cursor-not-allowed text-slate-500 border-slate-600/20 bg-slate-600/5' : 'text-emerald-400 border-emerald-500/20 bg-emerald-500/10 hover:bg-emerald-500/20'}" ${qCmd ? 'disabled' : ''}>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>Start
                    </button>
                    <button onclick="App.openChat('${a.id}','${a.name}','${a.color}','${a.role}')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-lg text-xs font-semibold transition border text-blue-400 border-blue-500/20 bg-blue-500/10 hover:bg-blue-500/20 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>Chat
                    </button>
                </div>
            </div>`;
        }).join('');
    },

    renderQueue() {
        const sec = document.getElementById('queueSection');
        const pending = (this.queue?.commands || []).filter(c => c.status === 'pending' || c.status === 'running');
        if (pending.length === 0) { sec.classList.add('hidden'); return; }
        sec.classList.remove('hidden');
        sec.innerHTML = `<h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2.5 mb-4">
            <div class="spinner" style="color:#F59E0B"></div>Commando Wachtrij <span class="text-xs font-normal text-slate-500">${pending.length} wachtend</span>
        </h2>
        <div class="space-y-2">${pending.map(c => {
            const agent = AGENTS.find(a => a.id === c.agent_id);
            return `<div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:rgba(245,158,11,.04);border:1px solid rgba(245,158,11,.1)">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:${c.status==='running'?'#34d399':'#F59E0B'};box-shadow:0 0 6px ${c.status==='running'?'#34d399':'#F59E0B'}"></div>
                <span class="text-sm font-medium" style="color:${agent?.color||'#94a3b8'}">${agent?.name||c.agent_name}</span>
                <span class="text-xs text-slate-500">${c.type === 'chat' ? 'Chat' : 'Trigger'}</span>
                <span class="text-xs text-slate-500 ml-auto font-mono">${c.status === 'running' ? 'bezig' : 'wacht'} -- ${this.timeAgo(c.ts)}</span>
            </div>`;
        }).join('')}</div>
        <p class="text-[11px] text-slate-500 mt-3">De queue wordt elke ~5 minuten verwerkt door de OpenClaw agent op de CachyOS PC.</p>`;
    },

    renderFeed() {
        const sec = document.getElementById('feedSection');
        const feed = this.state?.activity_feed || [];
        sec.innerHTML = `<div class="p-6 pb-4 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2.5">
                <svg class="w-[18px] h-[18px] text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Activiteiten Feed
            </h2>
            <span class="text-xs font-mono text-slate-500">${feed.length} events</span>
        </div>
        <div class="max-h-[32rem] overflow-y-auto scr">${feed.length === 0
            ? '<div class="p-8 text-center text-sm text-slate-500">Nog geen activiteiten.</div>'
            : feed.slice(0, 25).map(ev => {
                const agent = AGENTS.find(a => a.name === ev.role || a.role === ev.role || (ev.role && a.role.split(' ')[0] && ev.role.includes(a.role.split(' ')[0])));
                const c = agent?.color || '#8892a8';
                const ok = ev.ok !== false;
                const ts = ev.ts ? new Date(ev.ts) : null;
                return `<div class="flex gap-3.5 px-6 py-3 border-b border-white/[0.04] hover:bg-white/[0.015] transition-colors">
                    <div class="flex flex-col items-center flex-shrink-0" style="min-width:40px">
                        <span class="text-[11px] font-mono text-slate-500">${ts ? ts.toTimeString().substring(0,5) : '--:--'}</span>
                        <span class="text-[10px] font-mono text-slate-600">${ts ? (ts.getDate()+'/'+(ts.getMonth()+1).toString().padStart(2,'0')) : ''}</span>
                    </div>
                    <div class="w-3 h-3 rounded-full flex-shrink-0 mt-1" style="background:${ok ? c : '#f87171'};box-shadow:0 0 6px ${this.rgba(ok?c:'#f87171',.3)}"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-0.5">
                            ${agent ? `<span class="text-xs font-semibold" style="color:${c}">${agent.name}</span>` : ''}
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold uppercase" style="background:${this.rgba(c,.08)};color:${c}">${this.esc(ev.role||'')}</span>
                            ${!ok ? '<span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-red-500/10 text-red-400">FOUT</span>' : ''}
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed">${this.esc(ev.msg||'')}</p>
                    </div>
                </div>`;
            }).join('')
        }</div>`;
    },

    async trigger(id, name) {
        const fd = new FormData();
        fd.append('agent_id', id);
        fd.append('agent_name', name);
        try {
            const r = await fetch('devteam-api.php?action=trigger', {method:'POST', body:fd});
            const d = await r.json();
            if (d.ok) { await this.poll(); }
            else { alert(d.msg || d.error || 'Fout'); }
        } catch(e) { alert('API niet bereikbaar.'); }
    },

    openChat(id, name, color, role) {
        this.chatAgent = id;
        this.chatName = name;
        this.chatColor = color;
        document.getElementById('cpHead').innerHTML = `<div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:${this.rgba(color,.1)};border:1px solid ${this.rgba(color,.15)}">
                <svg class="w-4 h-4" style="color:${color}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div><div class="text-sm font-semibold text-slate-200">${this.esc(name)}</div><div class="text-xs text-slate-500">${this.esc(role)}</div></div>
        </div>
        <button onclick="App.closeChat()" class="text-slate-400 hover:text-white transition p-1.5 rounded-lg hover:bg-white/5 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
        document.getElementById('cp').classList.add('open');
        document.getElementById('co').classList.add('open');
        document.getElementById('cpIn').focus();
        this.loadChat();
        if (this.chatPoll) clearInterval(this.chatPoll);
        this.chatPoll = setInterval(() => this.loadChat(), 3000);
    },

    closeChat() {
        document.getElementById('cp').classList.remove('open');
        document.getElementById('co').classList.remove('open');
        this.chatAgent = null;
        if (this.chatPoll) { clearInterval(this.chatPoll); this.chatPoll = null; }
    },

    async loadChat() {
        if (!this.chatAgent) return;
        try {
            const r = await fetch('devteam-api.php?action=chat_history&agent_id='+this.chatAgent+'&_='+Date.now());
            const d = await r.json();
            const msgs = d.messages || [];
            const container = document.getElementById('cpMsgs');

            const qCmd = this.getAgentQueue(this.chatAgent);
            const hasPendingChat = qCmd && qCmd.type === 'chat';

            if (msgs.length === 0 && !hasPendingChat) {
                container.innerHTML = `<div class="text-center py-10"><p class="text-sm text-slate-500 mb-2">Nog geen berichten met ${this.esc(this.chatName)}.</p><p class="text-xs text-slate-600">Stuur een bericht -- het antwoord komt binnen ~5 minuten via de agent queue.</p></div>`;
                return;
            }

            let html = msgs.map(m => {
                const isUser = m.from === 'user';
                const t = m.ts ? new Date(m.ts).toTimeString().substring(0,5) : '';
                return `<div class="flex flex-col ${isUser ? 'items-end' : 'items-start'}">
                    <div class="cb ${isUser ? 'cb-u' : 'cb-a'}">${this.esc(m.msg)}</div>
                    <span class="text-[10px] text-slate-600 mt-1 font-mono">${t}</span>
                </div>`;
            }).join('');

            if (hasPendingChat) {
                html += `<div class="flex flex-col items-start">
                    <div class="cb cb-w flex items-center gap-2">
                        <div class="spinner" style="color:#fbbf24;width:12px;height:12px;border-width:1.5px"></div>
                        Wacht op antwoord van ${this.esc(this.chatName)}... (${this.timeAgo(qCmd.ts)})
                    </div>
                </div>`;
            }

            container.innerHTML = html;
            container.scrollTop = container.scrollHeight;
        } catch(e) {}
    },

    async sendChat(e) {
        e.preventDefault();
        const input = document.getElementById('cpIn');
        const msg = input.value.trim();
        if (!msg || !this.chatAgent) return;
        input.value = '';

        const fd = new FormData();
        fd.append('agent_id', this.chatAgent);
        fd.append('agent_name', this.chatName);
        fd.append('message', msg);
        try {
            const r = await fetch('devteam-api.php?action=chat', {method:'POST', body:fd});
            const d = await r.json();
            if (d.ok) {
                await this.poll();
                await this.loadChat();
            } else {
                alert('Fout: ' + (d.error || 'onbekend'));
            }
        } catch(e) { alert('API niet bereikbaar.'); }
    },
};

App.init();
</script>
</body>
</html>
