/*
 * Midterms 2026 - animatielaag (React 18 + Framer Motion via ESM/CDN).
 *
 * - Imperatieve Framer Motion (animate/inView/stagger) voor getemperde
 *   scroll-reveals, count-ups en odds-balkvullingen op de server-gerenderde DOM.
 * - Een echte React + Framer Motion "island" voor het hero control-paneel
 *   (leest JSON uit #mt-control-data; de server-gerenderde odds blijven als
 *   fallback staan wanneer React/CDN niet beschikbaar is).
 *
 * Robuust: bij elke fout blijft de (volledig zichtbare) server-content staan.
 * Respecteert prefers-reduced-motion. Importmap staat in views/templates/header.php.
 */

const EASE = [0.16, 1, 0.3, 1];
const docEl = document.documentElement;
const REDUCED = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);

function finish() {
    docEl.setAttribute('data-mt-motion-done', '1');
    if (window.__mtMotionSafety) {
        clearTimeout(window.__mtMotionSafety);
        window.__mtMotionSafety = null;
    }
}

function revealEverything() {
    // Verwijder de begin-state zodat alle content gewoon zichtbaar is.
    docEl.removeAttribute('data-mt-motion');
    finish();
}

/* -------------------------------------------------------------------------
 * Imperatieve scroll-reveals
 * ---------------------------------------------------------------------- */
/*
 * Ruim inline-styles op zodra de animatie klaar is. Daarna regelt de
 * .mt-inview CSS-klasse de eindstaat (opacity:1; transform:none), zodat
 * CSS :hover-transforms (race-/nieuws-/referenda-kaarten) blijven werken
 * en will-change geen compositielagen vasthoudt.
 */
function clearInlineAfter(controls, els) {
    const clear = () => els.forEach((el) => {
        el.style.opacity = '';
        el.style.transform = '';
        el.style.willChange = '';
    });
    if (controls && typeof controls.then === 'function') {
        controls.then(clear, clear);
    } else {
        window.setTimeout(clear, 1000);
    }
}

function runReveals(animate, inView, stagger) {
    const seen = new WeakSet();

    const fadeIn = (el, opts) => {
        if (seen.has(el)) return;
        seen.add(el);
        el.classList.add('mt-inview');
        const controls = animate(
            el,
            { opacity: [0, 1], y: [16, 0] },
            Object.assign({ duration: 0.6, ease: EASE }, opts || {})
        );
        clearInlineAfter(controls, [el]);
    };

    // Losse elementen (incl. hero-tekst); hero-odds wordt door React beheerd.
    const singleSel = [
        '.midterms-page .page-hero__eyebrow',
        '.midterms-page .page-hero__title',
        '.midterms-page .page-hero__lead',
        '.midterms-page .page-hero__meta',
        '.midterms-hero__intro',
        '.section-header',
        '.midterms-block__intro',
        '.midterms-facts',
        '.midterms-map'
    ].join(',');

    document.querySelectorAll(singleSel).forEach((el) => {
        inView(el, () => { fadeIn(el); }, { amount: 0.15, margin: '0px 0px -8% 0px' });
    });

    // Odds-kaarten buiten het hero-paneel: reveal + balkvulling + count-up.
    document.querySelectorAll('.midterms-odds-row .midterms-odds, .midterms-ref-card .midterms-odds').forEach((card) => {
        inView(card, () => {
            fadeIn(card);
            animateOddsCard(animate, card);
        }, { amount: 0.2 });
    });

    // Grids en lijsten met gespreide (stagger) reveal.
    const groups = [
        ['.midterms-race-grid', '.midterms-race'],
        ['.midterms-news-grid', '.midterms-news-card'],
        ['.midterms-ref-grid', '.midterms-ref-card'],
        ['.midterms-timeline', '.midterms-timeline__item--full'],
        ['.midterms-uitleg', '.midterms-uitleg__block'],
        ['.midterms-news-list', '.midterms-news-item']
    ];
    groups.forEach(([containerSel, itemSel]) => {
        document.querySelectorAll(containerSel).forEach((container) => {
            const items = Array.prototype.filter.call(
                container.querySelectorAll(itemSel),
                (el) => !seen.has(el)
            );
            if (!items.length) return;
            inView(container, () => {
                items.forEach((el) => { seen.add(el); el.classList.add('mt-inview'); });
                const controls = animate(
                    items,
                    { opacity: [0, 1], y: [16, 0] },
                    { duration: 0.5, delay: stagger(0.06), ease: EASE }
                );
                clearInlineAfter(controls, items);
            }, { amount: 0.05, margin: '0px 0px -6% 0px' });
        });
    });

    // Feiten-getallen tellen op zodra de feiten-balk in beeld komt.
    document.querySelectorAll('.midterms-facts').forEach((facts) => {
        inView(facts, () => {
            facts.querySelectorAll('.midterms-facts__num').forEach((el) => countUp(animate, el));
        }, { amount: 0.4 });
    });
}

/* Balkvulling + percentage-count-up binnen een odds-kaart. */
function animateOddsCard(animate, card) {
    card.querySelectorAll('.midterms-odds__seg').forEach((seg) => {
        const target = seg.style.width || getComputedStyle(seg).width;
        if (!target) return;
        seg.style.width = '0%';
        animate(seg, { width: ['0%', target] }, { duration: 0.8, ease: EASE });
    });
    card.querySelectorAll('.midterms-odds__pct').forEach((el) => countUp(animate, el));
}

/*
 * Tel een numerieke waarde op vanaf 0. Slaat waarden met letters over
 * (bv. "3 nov"), zodat alleen schone getallen/percentages animeren.
 */
function countUp(animate, el) {
    const raw = (el.textContent || '').trim();
    if (/[a-z]/i.test(raw)) return;
    const match = raw.match(/-?\d[\d.,]*/);
    if (!match) return;
    const prefix = raw.slice(0, match.index);
    const suffix = raw.slice(match.index + match[0].length);
    const target = parseFloat(match[0].replace(/\./g, '').replace(',', '.'));
    if (isNaN(target)) return;
    el.textContent = prefix + '0' + suffix;
    animate(0, target, {
        duration: 0.9,
        ease: EASE,
        onUpdate: (v) => { el.textContent = prefix + Math.round(v) + suffix; }
    });
}

/* -------------------------------------------------------------------------
 * React + Framer Motion island: hero control-paneel
 * ---------------------------------------------------------------------- */
async function mountControlPanel(fm) {
    const mount = document.getElementById('mt-control-mount');
    const dataEl = document.getElementById('mt-control-data');
    if (!mount || !dataEl) return false;

    let markets;
    try {
        markets = JSON.parse(dataEl.textContent || '[]');
    } catch (e) { return false; }
    if (!Array.isArray(markets) || !markets.length) return false;

    let React, createRoot, html;
    try {
        const [reactMod, clientMod, htmMod] = await Promise.all([
            import('react'),
            import('react-dom/client'),
            import('htm/react')
        ]);
        React = reactMod.default || reactMod;
        createRoot = clientMod.createRoot;
        html = htmMod.html;
    } catch (e) { return false; }

    const motion = fm.motion;
    const animate = fm.animate;
    if (!motion || !createRoot || !html) return false;

    const useRef = React.useRef;
    const useEffect = React.useEffect;
    const Fragment = React.Fragment;

    function Counter({ value }) {
        const ref = useRef(null);
        useEffect(() => {
            if (!ref.current) return;
            if (REDUCED) { ref.current.textContent = Math.round(value) + '%'; return; }
            const controls = animate(0, value, {
                duration: 0.9,
                ease: EASE,
                onUpdate: (v) => { if (ref.current) ref.current.textContent = Math.round(v) + '%'; }
            });
            return () => controls.stop();
        }, [value]);
        return html`<span ref=${ref} className="midterms-odds__pct">${Math.round(value)}%</span>`;
    }

    function Market({ market, index }) {
        const base = { opacity: 1, y: 0 };
        return html`
            <${motion.div}
                className="midterms-odds"
                initial=${REDUCED ? false : { opacity: 0, y: 18 }}
                animate=${base}
                transition=${{ duration: 0.55, delay: 0.08 + index * 0.1, ease: EASE }}>
                <h3 className="midterms-odds__title">${market.title}</h3>
                <div className="midterms-odds__bar" role="img" aria-label=${market.aria}>
                    ${market.outcomes.map((o, i) => html`
                        <${motion.span}
                            key=${i}
                            className=${'midterms-odds__seg mt-party--' + o.party}
                            initial=${REDUCED ? false : { width: '0%' }}
                            animate=${{ width: o.pct + '%' }}
                            transition=${{ duration: 0.8, delay: 0.2 + index * 0.1, ease: EASE }} />`)}
                </div>
                <ul className="midterms-odds__legend">
                    ${market.outcomes.map((o, i) => html`
                        <li key=${i}>
                            <span className=${'midterms-odds__dot mt-party--' + o.party}></span>
                            <span className="midterms-odds__label">${o.label}</span>
                            <${Counter} value=${o.pct} />
                        </li>`)}
                </ul>
                ${market.sourceUrl ? html`
                    <a className="midterms-odds__source" href=${market.sourceUrl} target="_blank" rel="noopener nofollow">
                        Bron: Polymarket
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M7 7h10v10"/></svg>
                    </a>` : null}
            <//>`;
    }

    function ControlPanel() {
        return html`<${Fragment}>${markets.map((m, i) => html`<${Market} key=${i} market=${m} index=${i} />`)}<//>`;
    }

    mount.classList.add('mt-react-managed');
    try {
        const reactRoot = createRoot(mount);
        reactRoot.render(html`<${ControlPanel} />`);
        return true;
    } catch (e) { return false; }
}

/* -------------------------------------------------------------------------
 * Bootstrap
 * ---------------------------------------------------------------------- */
(async function init() {
    // Bij reduced-motion heeft de head-bootstrap data-mt-motion niet gezet:
    // alle content is al zichtbaar. We doen dan niets.
    if (REDUCED || !docEl.hasAttribute('data-mt-motion')) {
        finish();
        return;
    }

    let fm;
    try {
        fm = await import('framer-motion');
    } catch (e) {
        revealEverything();
        return;
    }

    const { animate, inView, stagger } = fm;
    if (typeof inView !== 'function' || typeof animate !== 'function') {
        revealEverything();
        return;
    }

    try {
        // Hero-odds eerst aan React proberen te geven (anders imperatief reveals).
        const reactOk = await mountControlPanel(fm);
        if (!reactOk) {
            const heroOdds = document.querySelector('.midterms-hero__odds');
            if (heroOdds) {
                inView(heroOdds, () => {
                    heroOdds.classList.add('mt-inview');
                    animate(heroOdds, { opacity: [0, 1], y: [16, 0] }, { duration: 0.6, ease: EASE });
                    heroOdds.querySelectorAll('.midterms-odds').forEach((c) => animateOddsCard(animate, c));
                }, { amount: 0.2 });
            }
        }

        runReveals(animate, inView, stagger);
        finish();
    } catch (e) {
        revealEverything();
    }
})();
