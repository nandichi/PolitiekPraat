/**
 * PartijMeter 2026 - Alpine component.
 *
 * Leest de server-payload uit window.PARTIJMETER_DATA en (optioneel) een gedeeld
 * resultaat uit window.PARTIJMETER_SHARE. Scoring volgens methode v2 (exact = 1,
 * neutraal vs uitgesproken = 0.5, tegenovergesteld = 0) met weging.
 *
 * Globaal gedefinieerd zodat x-data="partijMeter()" werkt; dit script wordt vóór
 * Alpine geladen (zie views/templates/header.php).
 */
function partijMeter() {
    return {
        // --- data ---
        questions: [],
        parties: [],
        meta: {},
        partyMap: {},

        // --- quizstaat ---
        screen: 'welcome',
        answers: {},
        weights: {},
        currentStep: 0,
        showExplanation: false,
        hasSaved: false,

        // --- resultaten ---
        finalResults: [],
        userCompass: { x: 0, y: 0 },
        themeBreakdown: [],
        coalition: null,
        deviations: { party: null, items: [] },
        activeTab: 'ranking',
        expanded: null,

        // --- delen ---
        shareUrl: '',
        sharing: false,
        copied: false,

        STORAGE_KEY: 'pp_partijmeter_2026',

        init() {
            const data = window.PARTIJMETER_DATA || {};
            this.questions = data.questions || [];
            this.parties = data.parties || [];
            this.meta = data.meta || {};
            this.partyMap = {};
            this.parties.forEach((p) => { this.partyMap[p.key] = p; });

            if (window.PARTIJMETER_SHARE && window.PARTIJMETER_SHARE.answers) {
                this.loadShared(window.PARTIJMETER_SHARE);
            } else {
                this.restore();
            }

            this._keyHandler = (e) => this.handleKey(e);
            document.addEventListener('keydown', this._keyHandler);
        },

        // ----------------------------------------------------------------- nav
        get totalSteps() { return this.questions.length; },
        get answeredCount() { return Object.keys(this.answers).length; },
        get progressPct() {
            return this.totalSteps ? Math.round(((this.currentStep + 1) / this.totalSteps) * 100) : 0;
        },
        get isReliable() { return this.answeredCount >= Math.ceil(this.totalSteps * 0.5); },
        currentQuestion() { return this.questions[this.currentStep] || null; },
        isImportant(i) { return (this.weights[i] || 1) > 1; },
        answerFor(i) { return this.answers[i] || null; },

        themeList() {
            const seen = [];
            this.questions.forEach((q) => { if (q.theme && seen.indexOf(q.theme) === -1) seen.push(q.theme); });
            return seen;
        },

        startQuiz() {
            this.screen = 'quiz';
            if (this.answeredCount === 0) this.currentStep = 0;
            this.showExplanation = false;
            this.persist();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        resumeQuiz() {
            this.screen = 'quiz';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        answer(value) {
            this.answers = { ...this.answers, [this.currentStep]: value };
            this.persist();
            this.next();
        },

        toggleImportant() {
            const i = this.currentStep;
            this.weights = { ...this.weights, [i]: this.isImportant(i) ? 1 : 2 };
            this.persist();
        },

        next() {
            if (this.currentStep < this.totalSteps - 1) {
                this.currentStep++;
                this.showExplanation = false;
                this.persist();
            } else {
                this.finish();
            }
        },

        skip() { this.next(); },

        prev() {
            if (this.currentStep > 0) {
                this.currentStep--;
                this.showExplanation = false;
                this.persist();
            }
        },

        goToQuestion(i) {
            this.currentStep = i;
            this.screen = 'quiz';
            this.showExplanation = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        finish() {
            this.compute();
            this.screen = 'results';
            this.activeTab = 'ranking';
            this.clearStorage();
            window.scrollTo({ top: 0, behavior: 'smooth' });
            this.$nextTick(() => this.animateBars());
            this.saveResult();
        },

        // ------------------------------------------------------------- scoring
        matchValue(user, party) {
            if (user === party) return 1;
            if (user === 'neutraal' || party === 'neutraal') return 0.5;
            return 0;
        },

        compute() {
            const valMap = { eens: 1, neutraal: 0, oneens: -1 };
            const stats = {};
            this.parties.forEach((p) => { stats[p.key] = { s: 0, t: 0, m: 0, pa: 0, o: 0, c: 0 }; });

            let econS = 0, econC = 0, cultS = 0, cultC = 0;

            Object.keys(this.answers).forEach((idx) => {
                const q = this.questions[idx];
                const ua = this.answers[idx];
                if (!q || !(ua in valMap)) return;
                const w = this.weights[idx] || 1;

                const v = valMap[ua];
                if (q.axisEconomic) { econS += v * q.axisEconomic; econC++; }
                if (q.axisCultural) { cultS += v * q.axisCultural; cultC++; }

                this.parties.forEach((p) => {
                    const pos = (q.positions[p.key] || {}).p;
                    if (!(pos in valMap)) return;
                    const mv = this.matchValue(ua, pos);
                    const st = stats[p.key];
                    st.s += mv * w;
                    st.t += w;
                    st.c++;
                    if (mv === 1) st.m++;
                    else if (mv === 0.5) st.pa++;
                    else st.o++;
                });
            });

            this.userCompass = {
                x: econC ? Math.round((100 * econS) / econC) : 0,
                y: cultC ? Math.round((100 * cultS) / cultC) : 0,
            };

            this.finalResults = this.parties
                .map((p) => {
                    const st = stats[p.key];
                    return {
                        key: p.key,
                        name: p.name,
                        leader: p.leader,
                        logo: p.logo,
                        leaderPhoto: p.leaderPhoto,
                        color: p.color,
                        seats: p.seats,
                        compass: p.compass,
                        agreement: st.t > 0 ? Math.round((100 * st.s) / st.t) : 0,
                        matched: st.m,
                        partial: st.pa,
                        opposed: st.o,
                        considered: st.c,
                        display: 0,
                    };
                })
                .sort((a, b) => b.agreement - a.agreement || a.name.localeCompare(b.name));

            this.computeThemes();
            this.computeCoalition();
            this.computeDeviations();
        },

        computeThemes() {
            const valMap = { eens: 1, neutraal: 0, oneens: -1 };
            const themes = {};
            Object.keys(this.answers).forEach((idx) => {
                const q = this.questions[idx];
                const ua = this.answers[idx];
                if (!q || !(ua in valMap)) return;
                const t = q.theme;
                if (!themes[t]) themes[t] = { count: 0, parties: {} };
                themes[t].count++;
                this.parties.forEach((p) => {
                    const pos = (q.positions[p.key] || {}).p;
                    if (!(pos in valMap)) return;
                    if (!themes[t].parties[p.key]) themes[t].parties[p.key] = { s: 0, t: 0 };
                    themes[t].parties[p.key].s += this.matchValue(ua, pos);
                    themes[t].parties[p.key].t += 1;
                });
            });

            this.themeBreakdown = Object.keys(themes).map((t) => {
                const list = Object.keys(themes[t].parties).map((k) => ({
                    key: k,
                    name: this.partyMap[k] ? this.partyMap[k].name : k,
                    color: this.partyMap[k] ? this.partyMap[k].color : '#666',
                    logo: this.partyMap[k] ? this.partyMap[k].logo : null,
                    pct: themes[t].parties[k].t ? Math.round((100 * themes[t].parties[k].s) / themes[t].parties[k].t) : 0,
                }));
                list.sort((a, b) => b.pct - a.pct);
                return { theme: t, count: themes[t].count, top: list[0] || null };
            }).sort((a, b) => b.count - a.count);
        },

        computeCoalition() {
            const ranked = [...this.finalResults];
            let seats = 0;
            let scoreSum = 0;
            const members = [];
            for (const p of ranked) {
                if (seats >= 76) break;
                members.push(p);
                seats += p.seats;
                scoreSum += p.agreement;
            }
            this.coalition = {
                members,
                seats,
                avg: members.length ? Math.round(scoreSum / members.length) : 0,
                majority: seats >= 76,
            };
        },

        computeDeviations() {
            const top = this.finalResults[0];
            if (!top) { this.deviations = { party: null, items: [] }; return; }
            const items = [];
            Object.keys(this.answers).forEach((idx) => {
                const q = this.questions[idx];
                const ua = this.answers[idx];
                const pos = q.positions[top.key] || {};
                if (!pos.p) return;
                if (this.matchValue(ua, pos.p) < 1) {
                    items.push({
                        theme: q.theme,
                        title: q.title,
                        user: ua,
                        party: pos.p,
                        explanation: pos.e || '',
                        source: pos.s || '',
                    });
                }
            });
            this.deviations = { party: top, items };
        },

        // ------------------------------------------------------------- compass
        cx(x) { return (Number(x) + 100) / 2; },
        cy(y) { return (-Number(y) + 100) / 2; },

        // -------------------------------------------------------------- matrix
        posTone(questionIndex, partyKey) {
            const q = this.questions[questionIndex];
            const ua = this.answers[questionIndex];
            const pos = q && q.positions[partyKey] ? q.positions[partyKey].p : null;
            if (!pos) return 'none';
            if (!ua) return 'plain';
            const mv = this.matchValue(ua, pos);
            if (mv === 1) return 'match';
            if (mv === 0.5) return 'partial';
            return 'oppose';
        },
        positionLabel(p) {
            return { eens: 'Eens', neutraal: 'Neutraal', oneens: 'Oneens' }[p] || '-';
        },
        toggleMatrix(i) { this.expanded = this.expanded === i ? null : i; },

        // ----------------------------------------------------------- animatie
        animateBars() {
            const duration = 900;
            const start = performance.now();
            const targets = this.finalResults.map((r) => r.agreement);
            const tick = (now) => {
                const t = Math.min(1, (now - start) / duration);
                const ease = 1 - Math.pow(1 - t, 3);
                this.finalResults.forEach((r, i) => { r.display = Math.round(targets[i] * ease); });
                if (t < 1) requestAnimationFrame(tick);
                else this.finalResults.forEach((r, i) => { r.display = targets[i]; });
            };
            requestAnimationFrame(tick);
        },

        // -------------------------------------------------------------- delen
        async saveResult() {
            if (this.shareUrl) return;
            try {
                this.sharing = true;
                const body = {
                    answers: this.answers,
                    weights: this.weights,
                    results: {
                        ranking: this.finalResults.map((r) => ({ key: r.key, agreement: r.agreement })),
                        compass: this.userCompass,
                        answeredCount: this.answeredCount,
                    },
                };
                const res = await fetch('/api/partijmeter.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body),
                });
                const data = await res.json();
                if (data && data.success && data.shareUrl) {
                    this.shareUrl = data.shareUrl;
                }
            } catch (e) {
                /* stil falen: delen is optioneel */
            } finally {
                this.sharing = false;
            }
        },

        async share() {
            if (!this.shareUrl) await this.saveResult();
            if (!this.shareUrl) return;
            if (navigator.share) {
                try {
                    await navigator.share({ title: 'Mijn PartijMeter resultaat', url: this.shareUrl });
                    return;
                } catch (e) { /* gebruiker annuleerde */ }
            }
            this.copyShare();
        },

        copyShare() {
            if (!this.shareUrl) return;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(this.shareUrl).then(() => {
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2500);
                });
            }
        },

        // ------------------------------------------------------------ opslag
        persist() {
            try {
                localStorage.setItem(this.STORAGE_KEY, JSON.stringify({
                    answers: this.answers,
                    weights: this.weights,
                    currentStep: this.currentStep,
                }));
            } catch (e) { /* localStorage kan geblokkeerd zijn */ }
        },

        restore() {
            try {
                const raw = localStorage.getItem(this.STORAGE_KEY);
                if (!raw) return;
                const s = JSON.parse(raw);
                if (s && s.answers && Object.keys(s.answers).length > 0) {
                    this.answers = s.answers || {};
                    this.weights = s.weights || {};
                    this.currentStep = s.currentStep || 0;
                    this.hasSaved = true;
                }
            } catch (e) { /* corrupte opslag negeren */ }
        },

        clearStorage() {
            try { localStorage.removeItem(this.STORAGE_KEY); } catch (e) { /* noop */ }
            this.hasSaved = false;
        },

        restart() {
            this.answers = {};
            this.weights = {};
            this.currentStep = 0;
            this.finalResults = [];
            this.shareUrl = '';
            this.screen = 'welcome';
            this.clearStorage();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        loadShared(share) {
            this.answers = share.answers || {};
            this.weights = share.weights || {};
            this.compute();
            this.screen = 'results';
            this.activeTab = 'ranking';
            this.shareUrl = window.location.href;
            this.$nextTick(() => this.animateBars());
        },

        // ---------------------------------------------------------- toetsenbord
        handleKey(e) {
            if (this.screen !== 'quiz') return;
            const tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
            if (tag === 'input' || tag === 'textarea' || tag === 'select') return;
            switch (e.key) {
                case '1': this.answer('eens'); break;
                case '2': this.answer('neutraal'); break;
                case '3': this.answer('oneens'); break;
                case 'ArrowLeft': this.prev(); break;
                case 'ArrowRight': this.skip(); break;
                case 'b': case 'B': this.toggleImportant(); break;
                default: break;
            }
        },
    };
}
