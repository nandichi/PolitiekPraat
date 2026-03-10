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
        *{scrollbar-width:thin;scrollbar-color:rgba(45,74,107,.5) transparent}
        ::-webkit-scrollbar{width:4px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:rgba(45,74,107,.5);border-radius:2px}
        @keyframes rp{0%,100%{opacity:.4}50%{opacity:1}}
        @keyframes lb{0%,100%{opacity:1}50%{opacity:.3}}
        @keyframes fu{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        @keyframes td{0%{opacity:.3}50%{opacity:1}100%{opacity:.3}}
        @keyframes spin{to{transform:rotate(360deg)}}
        @keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(12px)}to{opacity:1;transform:scale(1) translateY(0)}}
        @keyframes overlayIn{from{opacity:0}to{opacity:1}}
        @keyframes stepIn{from{opacity:0;transform:translateX(-8px)}to{opacity:1;transform:translateX(0)}}
        .fade{animation:fu .35s ease-out both}
        .step-in{animation:stepIn .3s ease-out both}
        .tdots span{animation:td 1.4s ease-in-out infinite;display:inline-block;width:5px;height:5px;border-radius:50%;background:currentColor;margin:0 2px}
        .tdots span:nth-child(2){animation-delay:.2s}.tdots span:nth-child(3){animation-delay:.4s}
        .spinner{width:14px;height:14px;border:2px solid rgba(255,255,255,.15);border-top-color:currentColor;border-radius:50%;animation:spin .8s linear infinite}
        #chatOverlay{position:fixed;inset:0;z-index:9998;background:rgba(5,12,24,.7);backdrop-filter:blur(4px);display:none;animation:overlayIn .2s ease-out}
        #chatOverlay.open{display:flex;align-items:center;justify-content:center}
        #chatModal{width:560px;max-width:92vw;height:600px;max-height:85vh;background:#0f2a44;border:1px solid rgba(45,74,107,.5);border-radius:20px;display:flex;flex-direction:column;box-shadow:0 25px 80px rgba(0,0,0,.6),0 0 0 1px rgba(45,74,107,.2);animation:modalIn .25s ease-out;overflow:hidden}
        .cb{max-width:82%;padding:10px 14px;border-radius:16px;font-size:.84rem;line-height:1.7;word-wrap:break-word}
        .cb-u{background:rgba(26,54,93,.7);color:#e6ecf5;border-bottom-right-radius:4px}
        .cb-a{background:rgba(17,34,64,.8);color:#ccd6e6;border-bottom-left-radius:4px;border:1px solid rgba(45,74,107,.2)}
        .cb-w{background:rgba(245,158,11,.06);color:#fbbf24;border:1px dashed rgba(245,158,11,.15);font-size:.8rem}
        .cb-a strong,.cmd-msg strong{color:#94b8db;font-weight:600}
        .cb-a ul,.cb-a ol,.cmd-msg ul,.cmd-msg ol{margin:6px 0 6px 16px;padding:0}
        .cb-a li,.cmd-msg li{margin:2px 0}
        .cb-a ul,.cmd-msg ul{list-style:disc}.cb-a ol,.cmd-msg ol{list-style:decimal}
        .cb-a code,.cmd-msg code{background:rgba(255,255,255,.06);padding:1px 5px;border-radius:4px;font-size:.78rem;font-family:'IBM Plex Mono',monospace}
        .agent-card{display:flex;flex-direction:column}
        .agent-card-body{flex:1}
        .wf-line{position:absolute;left:15px;top:32px;bottom:0;width:2px;background:linear-gradient(to bottom,rgba(245,158,11,.3),rgba(45,74,107,.15))}
        @media(max-width:640px){#chatModal{width:100%;max-width:100vw;height:100vh;max-height:100vh;border-radius:0}}
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

        <!-- Commander Section -->
        <section class="bg-pp-surface border border-amber-500/15 rounded-2xl overflow-hidden fade">
            <div class="px-6 py-5 border-b border-white/[0.04] flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-[13px] flex items-center justify-center flex-shrink-0" style="background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.12)">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-amber-300">Commander</span>
                        <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded" style="background:rgba(245,158,11,.1);color:#F59E0B;border:1px solid rgba(245,158,11,.15)">Orchestrator</span>
                    </div>
                    <span class="text-xs text-slate-500">Geef een opdracht -- Commander verdeelt het werk over de juiste agents</span>
                </div>
            </div>
            <div id="cmdMsgs" class="max-h-[20rem] overflow-y-auto px-6 py-4 space-y-3"></div>
            <div class="px-5 py-4 border-t border-white/[0.04]" style="background:rgba(10,30,51,.3)">
                <form onsubmit="App.sendCommander(event)" class="flex gap-2.5">
                    <input type="text" id="cmdIn" placeholder="Bijv. 'Voeg dark mode toe' of 'Optimaliseer de laadtijd van /blogs'" autocomplete="off" class="flex-1 rounded-xl px-4 py-3 text-sm placeholder-slate-500/60 focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition" style="background:rgba(10,30,51,.5);border:1px solid rgba(245,158,11,.12);color:#e6ecf5">
                    <button type="submit" id="cmdBtn" class="px-6 py-3 rounded-xl transition text-sm font-semibold bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/20 cursor-pointer flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>Stuur
                    </button>
                </form>
            </div>
        </section>

        <!-- Workflow Panel -->
        <section id="wfSection" class="hidden"></section>

        <!-- Subagents Grid -->
        <section>
            <div class="flex items-center gap-2.5 mb-4">
                <h2 class="text-sm font-semibold text-slate-300">Subagents</h2>
                <span class="text-[10px] text-slate-600 font-mono">8 agents</span>
            </div>
            <div id="agents" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                <div class="col-span-full text-center py-8 text-slate-500 text-sm">Laden...</div>
            </div>
        </section>

        <section id="queueSection" class="hidden bg-pp-surface border border-pp-light/30 rounded-2xl p-6"></section>
        <section id="feedSection" class="bg-pp-surface border border-pp-light/30 rounded-2xl"></section>
        <footer class="text-center text-xs font-mono text-slate-600/40 pt-2 pb-2">PolitiekPraat DevTeam -- Live Agent Monitor v4.0</footer>
    </main>

    <div id="chatOverlay" onclick="if(event.target===this)App.closeChat()">
        <div id="chatModal">
            <div class="flex items-center justify-between px-6 py-4 border-b border-white/[0.06]" id="cpHead"></div>
            <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4" id="cpMsgs"></div>
            <div class="px-5 py-4 border-t border-white/[0.06]" style="background:rgba(10,30,51,.4)">
                <form onsubmit="App.sendChat(event)" class="flex gap-2.5">
                    <input type="text" id="cpIn" placeholder="Typ een bericht..." autocomplete="off" class="flex-1 rounded-xl px-4 py-2.5 text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition" style="background:rgba(10,30,51,.6);border:1px solid rgba(45,74,107,.35);color:#e6ecf5">
                    <button type="submit" class="px-5 py-2.5 rounded-xl transition text-sm font-semibold bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/20 cursor-pointer">Stuur</button>
                </form>
            </div>
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
const AGENT_MAP = Object.fromEntries(AGENTS.map(a => [a.id, a]));

const App = {
    state: null, queue: null, chatAgent: null, chatName: '', chatColor: '', chatPoll: null, pollOk: false,
    _prevHash: '', _prevFeedHash: '', _prevQueueHash: '', _prevChatHash: '', _prevCmdHash: '', _prevWfHash: '',

    async init() {
        await this.poll();
        setInterval(() => this.poll(), 5000);
    },

    async poll() {
        try {
            const r = await fetch('devteam-api.php?action=full_state&_='+Date.now());
            if (!r.ok) throw new Error(r.status);
            const d = await r.json();
            this.state = d.state || {};
            this.queue = d.queue || {commands:[]};
            this.pollOk = true;
            this.smartRender();
        } catch(e) {
            this.pollOk = false;
            this.updateLive();
        }
    },

    hash(o) { return JSON.stringify(o); },

    smartRender() {
        this.updateLive();
        const ah = this.hash({c:this.state?.cron_states,f:(this.state?.activity_feed||[]).slice(0,8).map(f=>f.ts+f.role),q:(this.queue?.commands||[]).map(c=>c.id+c.status)});
        if (ah !== this._prevHash) { this._prevHash = ah; this.renderAgents(); }
        const qh = this.hash((this.queue?.commands||[]).filter(c=>c.status==='pending'||c.status==='running'));
        if (qh !== this._prevQueueHash) { this._prevQueueHash = qh; this.renderQueue(); }
        const fh = this.hash((this.state?.activity_feed||[]).slice(0,25).map(f=>f.ts));
        if (fh !== this._prevFeedHash) { this._prevFeedHash = fh; this.renderFeed(); }
        const ch = this.hash(this.state?.commander_chats||[]);
        if (ch !== this._prevCmdHash) { this._prevCmdHash = ch; this.renderCommander(); }
        const wh = this.hash(this.state?.workflows||[]);
        if (wh !== this._prevWfHash) { this._prevWfHash = wh; this.renderWorkflows(); }
        if (this.chatAgent) this.loadChat();
    },

    updateLive() {
        const el = document.getElementById('liveIndicator');
        el.innerHTML = this.pollOk
            ? '<div class="w-1.5 h-1.5 rounded-full bg-emerald-400" style="box-shadow:0 0 8px #34d399;animation:lb 1.5s ease-in-out infinite"></div><span class="text-emerald-400">LIVE</span>'
            : '<div class="w-1.5 h-1.5 rounded-full bg-red-400" style="box-shadow:0 0 6px #f87171"></div><span class="text-red-400">FOUT</span>';
    },

    getAgentQueue(id) { const c=(this.queue?.commands||[]).filter(c=>c.agent_id===id&&(c.status==='pending'||c.status==='running')); return c.length?c[c.length-1]:null; },
    getAgentDoneCommands(id) { return (this.queue?.commands||[]).filter(c=>c.agent_id===id&&c.status==='done').slice(-3).reverse(); },
    timeAgo(ts) { if(!ts)return''; const s=Math.floor((Date.now()-new Date(ts).getTime())/1000); if(s<0)return'nu'; if(s<60)return s+'s'; if(s<3600)return Math.floor(s/60)+'m'; if(s<86400)return Math.floor(s/3600)+'u'; return Math.floor(s/86400)+'d'; },
    rgba(h,a) { return `rgba(${parseInt(h.slice(1,3),16)},${parseInt(h.slice(3,5),16)},${parseInt(h.slice(5,7),16)},${a})`; },
    esc(t) { if(!t)return''; const d=document.createElement('div'); d.textContent=t; return d.innerHTML; },
    md(t) {
        if(!t)return'';
        let h=this.esc(t);
        h=h.replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>');
        h=h.replace(/(?<!\*)\*([^*]+?)\*(?!\*)/g,'<em>$1</em>');
        h=h.replace(/`([^`]+?)`/g,'<code>$1</code>');
        const lines=h.split('\n'); let out=[],inL=false;
        for(const l of lines){const m=l.match(/^\s*[-] (.+)/);if(m){if(!inL){out.push('<ul>');inL=true;}out.push('<li>'+m[1]+'</li>');}else{if(inL){out.push('</ul>');inL=false;}out.push(l.trim()===''?'<br>':l);}}
        if(inL)out.push('</ul>');
        return out.join('\n');
    },

    // ─── COMMANDER ──────────────────────────────────────
    renderCommander() {
        const msgs = this.state?.commander_chats || [];
        const el = document.getElementById('cmdMsgs');
        const cmdPending = this.getAgentQueue('commander');

        if (msgs.length === 0 && !cmdPending) {
            el.innerHTML = '<div class="text-center py-6"><p class="text-sm text-slate-500">Nog geen opdrachten. Geef Commander een instructie hierboven.</p></div>';
            return;
        }

        let html = msgs.map(m => {
            const isUser = m.from === 'user';
            const t = m.ts ? new Date(m.ts).toTimeString().substring(0,5) : '';
            return `<div class="flex flex-col ${isUser ? 'items-end' : 'items-start'}">
                <div class="${isUser ? 'cb cb-u' : 'cmd-msg text-sm text-slate-300 leading-relaxed max-w-[90%] px-4 py-3 rounded-2xl border-l-2 border-amber-500/30'}" style="${isUser ? '' : 'background:rgba(245,158,11,.03)'}">${isUser ? this.esc(m.msg) : this.md(m.msg)}</div>
                <span class="text-[10px] text-slate-600 mt-1 px-1 font-mono">${isUser ? 'Jij' : 'Commander'} -- ${t}</span>
            </div>`;
        }).join('');

        if (cmdPending) {
            html += `<div class="flex flex-col items-start"><div class="cb cb-w flex items-center gap-2.5"><div class="spinner" style="color:#fbbf24;width:12px;height:12px;border-width:1.5px"></div><span>Commander verwerkt je opdracht... (${this.timeAgo(cmdPending.ts)})</span></div></div>`;
        }

        el.innerHTML = html;
        el.scrollTop = el.scrollHeight;
    },

    async sendCommander(e) {
        e.preventDefault();
        const input = document.getElementById('cmdIn');
        const msg = input.value.trim();
        if (!msg) return;
        input.value = '';

        const el = document.getElementById('cmdMsgs');
        const t = new Date().toTimeString().substring(0,5);
        el.insertAdjacentHTML('beforeend', `<div class="flex flex-col items-end"><div class="cb cb-u">${this.esc(msg)}</div><span class="text-[10px] text-slate-600 mt-1 px-1 font-mono">Jij -- ${t}</span></div>`);
        el.scrollTop = el.scrollHeight;

        const fd = new FormData();
        fd.append('message', msg);
        try {
            const r = await fetch('devteam-api.php?action=orchestrate', {method:'POST', body:fd});
            const d = await r.json();
            if (!d.ok) {
                el.insertAdjacentHTML('beforeend', `<div class="text-xs text-red-400 text-center py-1">${this.esc(d.msg||d.error)}</div>`);
            } else {
                this._prevCmdHash = '';
                await this.poll();
            }
        } catch(e) {
            el.insertAdjacentHTML('beforeend', '<div class="text-xs text-red-400 text-center py-1">Verbinding mislukt.</div>');
        }
    },

    // ─── WORKFLOWS ──────────────────────────────────────
    renderWorkflows() {
        const sec = document.getElementById('wfSection');
        const wfs = (this.state?.workflows || []).slice(-3).reverse();
        if (wfs.length === 0) { sec.classList.add('hidden'); return; }
        sec.classList.remove('hidden');

        sec.innerHTML = wfs.map(wf => {
            const isRunning = wf.status === 'running';
            const doneCount = (wf.steps||[]).filter(s=>s.status==='done').length;
            const total = (wf.steps||[]).length;
            const pct = total > 0 ? Math.round((doneCount/total)*100) : 0;

            let stepsHtml = (wf.steps||[]).map((step, i) => {
                const agent = AGENT_MAP[step.agent_id];
                const c = agent?.color || '#94a3b8';
                let icon, statusLabel;
                if (step.status === 'done') { icon = `<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`; statusLabel = 'Afgerond'; }
                else if (step.status === 'running') { icon = `<div class="spinner" style="color:${c};width:16px;height:16px;border-width:2px"></div>`; statusLabel = 'Bezig...'; }
                else if (step.status === 'failed') { icon = `<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`; statusLabel = 'Mislukt'; }
                else { icon = `<div class="w-4 h-4 rounded-full border-2" style="border-color:${this.rgba(c,.3)}"></div>`; statusLabel = 'Wacht'; }

                const resultHtml = step.result ? `<div class="mt-2 hidden" id="wfr_${wf.id}_${i}"><div class="cmd-msg text-xs text-slate-400 leading-relaxed p-3 rounded-lg" style="background:rgba(10,30,51,.4)">${this.md(step.result)}</div></div>` : '';
                const toggleBtn = step.result ? `<button onclick="document.getElementById('wfr_${wf.id}_${i}').classList.toggle('hidden');this.querySelector('svg').classList.toggle('rotate-180')" class="text-slate-500 hover:text-slate-300 transition p-0.5 cursor-pointer"><svg class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg></button>` : '';

                return `<div class="relative pl-10 pb-5 step-in" style="animation-delay:${i*0.08}s">
                    ${i < total-1 ? '<div class="wf-line"></div>' : ''}
                    <div class="absolute left-0 top-0 w-[30px] h-[30px] rounded-full flex items-center justify-center" style="background:${this.rgba(c,.08)};border:1px solid ${this.rgba(c,.15)}">${icon}</div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold" style="color:${c}">${step.agent_name}</span>
                            <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded" style="background:${this.rgba(c,.08)};color:${c}">${statusLabel}</span>
                            ${step.finished_at ? `<span class="text-[10px] font-mono text-slate-600">${this.timeAgo(step.finished_at)}</span>` : ''}
                            ${toggleBtn}
                        </div>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">${this.esc(step.instruction?.substring(0,120))}${(step.instruction||'').length>120?'...':''}</p>
                        ${resultHtml}
                    </div>
                </div>`;
            }).join('');

            return `<div class="bg-pp-surface border ${isRunning ? 'border-amber-500/20' : 'border-pp-light/30'} rounded-2xl overflow-hidden fade">
                <div class="px-6 py-4 border-b border-white/[0.04]">
                    <div class="flex items-center gap-3 mb-2">
                        ${isRunning ? '<div class="spinner" style="color:#F59E0B"></div>' : '<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'}
                        <span class="text-sm font-semibold ${isRunning ? 'text-amber-300' : 'text-emerald-400'}">${isRunning ? 'Actieve Workflow' : 'Workflow Afgerond'}</span>
                        <span class="text-xs font-mono text-slate-600 ml-auto">${this.timeAgo(wf.ts)}</span>
                    </div>
                    <p class="text-xs text-slate-400">${this.esc(wf.request?.substring(0,150))}</p>
                    ${total > 0 ? `<div class="mt-3 flex items-center gap-3"><div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:rgba(45,74,107,.2)"><div class="h-full rounded-full transition-all duration-700" style="width:${pct}%;background:${isRunning?'#F59E0B':'#34d399'}"></div></div><span class="text-[11px] font-mono" style="color:${isRunning?'#F59E0B':'#34d399'}">${doneCount}/${total}</span></div>` : ''}
                </div>
                ${wf.plan ? `<div class="px-6 py-3 border-b border-white/[0.03]"><p class="text-xs text-slate-400/80 leading-relaxed cmd-msg">${this.md(wf.plan)}</p></div>` : ''}
                <div class="px-6 py-4">${stepsHtml || '<p class="text-xs text-slate-500">Geen stappen gedefinieerd.</p>'}</div>
            </div>`;
        }).join('');
    },

    // ─── AGENTS ─────────────────────────────────────────
    renderAgents() {
        const el = document.getElementById('agents');
        const cron = this.state?.cron_states || {};
        const feed = this.state?.activity_feed || [];
        const activeWf = (this.state?.workflows||[]).find(w=>w.status==='running');
        const busyAgentIds = new Set((activeWf?.steps||[]).filter(s=>s.status==='running').map(s=>s.agent_id));

        el.innerHTML = AGENTS.map((a, i) => {
            const cs = cron[a.id]; const lastRun = cs?.last_run;
            const qCmd = this.getAgentQueue(a.id);
            const doneCmds = this.getAgentDoneCommands(a.id);
            const lastFE = feed.find(f => f.role===a.name||f.role===a.role||(a.role.split(' ')[0]&&f.role?.includes(a.role.split(' ')[0])));
            const isBusyForWf = busyAgentIds.has(a.id);

            let sT,sC,isA=false;
            if (isBusyForWf) { sT='Commander'; sC='#F59E0B'; isA=true; }
            else if (qCmd?.status==='running') { sT='Bezig...'; sC='#34d399'; isA=true; }
            else if (qCmd?.status==='pending') { sT='In wachtrij'; sC='#F59E0B'; isA=true; }
            else if (cs?.status==='ok') { sT='Idle'; sC='#60a5fa'; }
            else if (cs?.status==='error') { sT='Fout'; sC='#f87171'; }
            else { sT='Wacht'; sC='#64748b'; }
            const tI = qCmd?this.timeAgo(qCmd.ts):(lastRun?this.timeAgo(lastRun):a.schedule);

            let info='';
            if(qCmd){info=`<div class="px-3 py-2.5 rounded-lg text-xs" style="background:${this.rgba(sC,.06)};border:1px solid ${this.rgba(sC,.12)}"><div class="flex items-center gap-2 mb-1"><div class="spinner" style="color:${sC};width:11px;height:11px;border-width:1.5px"></div><span style="color:${sC}" class="font-semibold">${qCmd.status==='running'?'Bezig...':'Wacht op verwerking'}</span></div><span class="text-slate-500">${qCmd.type==='chat'?'Chat: "'+this.esc((qCmd.message||'').substring(0,35))+'..."':'Trigger'} -- ${this.timeAgo(qCmd.ts)}</span></div>`;}
            else if(doneCmds.length>0){const l=doneCmds[0],r=(l.response||'').substring(0,80);info=`<div class="px-3 py-2.5 rounded-lg text-xs" style="background:rgba(45,74,107,.1);border:1px solid rgba(45,74,107,.18)"><div class="flex items-center gap-1.5 mb-1"><svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-emerald-400/80 font-semibold">Resultaat</span><span class="text-slate-600 ml-auto">${this.timeAgo(l.ts)}</span></div><span class="text-slate-400/80">${this.esc(r)}${r.length>=80?'...':''}</span></div>`;}
            else if(lastFE){info=`<div class="px-3 py-2 rounded-lg text-xs" style="background:rgba(45,74,107,.06)"><span class="text-slate-500">Laatste actie:</span> <span class="text-slate-400/80">${this.esc((lastFE.msg||'').substring(0,70))}</span></div>`;}
            else{info=`<div class="px-3 py-2 rounded-lg text-xs" style="background:rgba(45,74,107,.04)"><span class="text-slate-500/60">Geen recente activiteit.</span></div>`;}

            return `<div class="agent-card bg-pp-surface border ${isBusyForWf?'border-amber-500/25':'border-pp-light/30'} rounded-2xl p-5 relative overflow-hidden transition-all duration-300 hover:border-pp-light/50 fade" style="animation-delay:${i*0.04}s">
                <div class="absolute top-0 left-0 right-0 h-[3px] rounded-t-2xl" style="background:${a.color};opacity:${isA?1:.15}"></div>
                ${isA?`<div class="absolute inset-[-3px] rounded-[19px] border-2 pointer-events-none" style="border-color:${isBusyForWf?'#F59E0B':a.color};opacity:.25;animation:rp 2s ease-in-out infinite"></div>`:''}
                <div class="agent-card-body">
                    <div class="flex items-start gap-3.5 mb-3"><div class="w-11 h-11 rounded-[13px] flex items-center justify-center flex-shrink-0" style="background:${this.rgba(a.color,.07)};border:1px solid ${this.rgba(a.color,.12)}"><svg class="w-5 h-5" style="color:${a.color}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="${a.icon}"/></svg></div>
                    <div class="flex-1 min-w-0"><div class="flex items-center gap-2"><span class="font-semibold text-sm text-slate-200">${a.name}</span><div class="w-2 h-2 rounded-full flex-shrink-0" style="background:${sC};box-shadow:0 0 6px ${sC}"></div></div><span class="text-xs font-medium" style="color:${a.color};opacity:.8">${a.role}</span></div></div>
                    <p class="text-xs text-slate-500 mb-3 leading-relaxed">${a.desc}</p>
                    <div class="flex items-center justify-between mb-3"><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-semibold" style="background:${this.rgba(sC,.08)};color:${sC};border:1px solid ${this.rgba(sC,.12)}">${isA?'<span class="tdots"><span></span><span></span><span></span></span>':''}${sT}</span><span class="text-[11px] font-mono text-slate-600">${tI}</span></div>
                    ${info}
                </div>
                <div class="flex gap-2 pt-4 mt-4 border-t border-white/[0.04]">
                    <button onclick="App.trigger('${a.id}','${a.name}')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition border cursor-pointer ${qCmd?'opacity-30 cursor-not-allowed text-slate-500 border-slate-700/20 bg-slate-700/5':'text-emerald-400 border-emerald-500/15 bg-emerald-500/8 hover:bg-emerald-500/15'}" ${qCmd?'disabled':''}>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>Start</button>
                    <button onclick="App.openChat('${a.id}','${a.name}','${a.color}','${a.role}')" class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold transition border text-blue-400 border-blue-500/15 bg-blue-500/8 hover:bg-blue-500/15 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>Chat</button>
                </div>
            </div>`;
        }).join('');
    },

    renderQueue() {
        const sec=document.getElementById('queueSection');
        const pending=(this.queue?.commands||[]).filter(c=>c.status==='pending'||c.status==='running');
        if(!pending.length){sec.classList.add('hidden');return;}
        sec.classList.remove('hidden');
        sec.innerHTML=`<h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2.5 mb-4"><div class="spinner" style="color:#F59E0B"></div>Commando Wachtrij <span class="text-xs font-normal text-slate-500">${pending.length} wachtend</span></h2>
        <div class="space-y-2">${pending.map(c=>{const ag=AGENT_MAP[c.agent_id]||{name:c.agent_name,color:'#94a3b8'};return`<div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:rgba(245,158,11,.04);border:1px solid rgba(245,158,11,.1)"><div class="w-2 h-2 rounded-full flex-shrink-0" style="background:${c.status==='running'?'#34d399':'#F59E0B'};box-shadow:0 0 6px ${c.status==='running'?'#34d399':'#F59E0B'}"></div><span class="text-sm font-medium" style="color:${ag.color}">${ag.name||c.agent_name}</span><span class="text-xs text-slate-500">${c.type}</span><span class="text-xs text-slate-500 ml-auto font-mono">${c.status==='running'?'bezig':'wacht'} -- ${this.timeAgo(c.ts)}</span></div>`;}).join('')}</div>
        <p class="text-[11px] text-slate-600 mt-3">Queue wordt elke ~5 min verwerkt door de OpenClaw agent.</p>`;
    },

    renderFeed() {
        const sec=document.getElementById('feedSection');const feed=this.state?.activity_feed||[];
        sec.innerHTML=`<div class="p-6 pb-4 flex items-center justify-between"><h2 class="text-sm font-semibold text-slate-200 flex items-center gap-2.5"><svg class="w-[18px] h-[18px] text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Activiteiten Feed</h2><span class="text-xs font-mono text-slate-500">${feed.length} events</span></div>
        <div class="max-h-[32rem] overflow-y-auto">${!feed.length?'<div class="p-8 text-center text-sm text-slate-500">Nog geen activiteiten.</div>':feed.slice(0,25).map(ev=>{
            const ag=AGENTS.find(a=>a.name===ev.role||a.role===ev.role||(ev.role&&a.role.split(' ')[0]&&ev.role.includes(a.role.split(' ')[0])));
            const c=ev.role==='Commander'?'#F59E0B':(ag?.color||'#8892a8');const ok=ev.ok!==false;const ts=ev.ts?new Date(ev.ts):null;
            return`<div class="flex gap-3.5 px-6 py-3 border-b border-white/[0.04] hover:bg-white/[0.015] transition-colors"><div class="flex flex-col items-center flex-shrink-0" style="min-width:40px"><span class="text-[11px] font-mono text-slate-500">${ts?ts.toTimeString().substring(0,5):'--:--'}</span><span class="text-[10px] font-mono text-slate-600">${ts?(ts.getDate()+'/'+(ts.getMonth()+1).toString().padStart(2,'0')):''}</span></div><div class="w-3 h-3 rounded-full flex-shrink-0 mt-1" style="background:${ok?c:'#f87171'};box-shadow:0 0 6px ${this.rgba(ok?c:'#f87171',.3)}"></div><div class="flex-1 min-w-0"><div class="flex items-center gap-2 flex-wrap mb-0.5">${ag?`<span class="text-xs font-semibold" style="color:${c}">${ag.name}</span>`:(ev.role==='Commander'?'<span class="text-xs font-semibold text-amber-400">Commander</span>':'')}<span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold uppercase" style="background:${this.rgba(c,.08)};color:${c}">${this.esc(ev.role||'')}</span>${!ok?'<span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-red-500/10 text-red-400">FOUT</span>':''}</div><p class="text-sm text-slate-300/80 leading-relaxed">${this.esc(ev.msg||'')}</p></div></div>`;
        }).join('')}</div>`;
    },

    async trigger(id,name) {
        const btn=event?.target?.closest('button');if(btn){btn.disabled=true;btn.classList.add('opacity-30');}
        const fd=new FormData();fd.append('agent_id',id);fd.append('agent_name',name);
        try{const r=await fetch('devteam-api.php?action=trigger',{method:'POST',body:fd});const d=await r.json();if(d.ok){await this.poll();}else{alert(d.msg||d.error||'Fout');if(btn){btn.disabled=false;btn.classList.remove('opacity-30');}}}catch(e){alert('API niet bereikbaar.');if(btn){btn.disabled=false;btn.classList.remove('opacity-30');}}
    },

    openChat(id,name,color,role) {
        this.chatAgent=id;this.chatName=name;this.chatColor=color;this._prevChatHash='';
        document.getElementById('cpHead').innerHTML=`<div class="flex items-center gap-3"><div class="w-10 h-10 rounded-[13px] flex items-center justify-center" style="background:${this.rgba(color,.08)};border:1px solid ${this.rgba(color,.12)}"><svg class="w-5 h-5" style="color:${color}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div><div><div class="font-semibold text-slate-200">${this.esc(name)}</div><div class="text-xs text-slate-500">${this.esc(role)}</div></div></div><button onclick="App.closeChat()" class="text-slate-400 hover:text-white transition p-2 rounded-lg hover:bg-white/5 cursor-pointer" title="Sluiten"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
        document.getElementById('chatOverlay').classList.add('open');
        document.getElementById('cpIn').focus();
        this.loadChat(true);
        if(this.chatPoll)clearInterval(this.chatPoll);
        this.chatPoll=setInterval(()=>this.loadChat(),3000);
    },

    closeChat() {
        document.getElementById('chatOverlay').classList.remove('open');
        this.chatAgent=null;this._prevChatHash='';
        if(this.chatPoll){clearInterval(this.chatPoll);this.chatPoll=null;}
    },

    async loadChat(force) {
        if(!this.chatAgent)return;
        try{
            const r=await fetch('devteam-api.php?action=chat_history&agent_id='+this.chatAgent+'&_='+Date.now());
            const d=await r.json();const msgs=d.messages||[];
            const qCmd=this.getAgentQueue(this.chatAgent);const hasPending=qCmd&&qCmd.type==='chat';
            const ch=JSON.stringify(msgs)+(hasPending?qCmd.id:'');
            if(!force&&ch===this._prevChatHash)return;this._prevChatHash=ch;
            const container=document.getElementById('cpMsgs');
            if(!msgs.length&&!hasPending){container.innerHTML=`<div class="flex flex-col items-center justify-center py-12 text-center"><p class="text-sm text-slate-400 mb-1.5">Nog geen berichten met ${this.esc(this.chatName)}</p><p class="text-xs text-slate-600">Stel een vraag -- antwoord komt binnen ~5 min.</p></div>`;return;}
            let html=msgs.map(m=>{const isU=m.from==='user';const t=m.ts?new Date(m.ts).toTimeString().substring(0,5):'';return`<div class="flex flex-col ${isU?'items-end':'items-start'}"><div class="cb ${isU?'cb-u':'cb-a'}">${isU?this.esc(m.msg):this.md(m.msg)}</div><span class="text-[10px] text-slate-600 mt-1 px-1 font-mono">${isU?'Jij':this.esc(this.chatName)} -- ${t}</span></div>`;}).join('');
            if(hasPending){html+=`<div class="flex flex-col items-start"><div class="cb cb-w flex items-center gap-2.5"><div class="spinner" style="color:#fbbf24;width:12px;height:12px;border-width:1.5px"></div><span>${this.esc(this.chatName)} denkt na... (${this.timeAgo(qCmd.ts)})</span></div></div>`;}
            container.innerHTML=html;container.scrollTop=container.scrollHeight;
        }catch(e){}
    },

    async sendChat(e) {
        e.preventDefault();const input=document.getElementById('cpIn');const msg=input.value.trim();if(!msg||!this.chatAgent)return;input.value='';
        const container=document.getElementById('cpMsgs');const t=new Date().toTimeString().substring(0,5);
        container.insertAdjacentHTML('beforeend',`<div class="flex flex-col items-end"><div class="cb cb-u">${this.esc(msg)}</div><span class="text-[10px] text-slate-600 mt-1 px-1 font-mono">Jij -- ${t}</span></div>`);container.scrollTop=container.scrollHeight;
        const fd=new FormData();fd.append('agent_id',this.chatAgent);fd.append('agent_name',this.chatName);fd.append('message',msg);
        try{const r=await fetch('devteam-api.php?action=chat',{method:'POST',body:fd});const d=await r.json();if(d.ok){this._prevChatHash='';await this.poll();await this.loadChat(true);}else{container.insertAdjacentHTML('beforeend',`<div class="text-xs text-red-400 text-center py-1">${this.esc(d.error||'onbekend')}</div>`);}}catch(e){container.insertAdjacentHTML('beforeend','<div class="text-xs text-red-400 text-center py-1">Verbinding mislukt.</div>');}
    },
};

App.init();
</script>
</body>
</html>
