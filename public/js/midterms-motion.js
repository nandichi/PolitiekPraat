/* PolitiekPraat Midterms 2026: lightweight motion and filtering, no framework. */
(function () {
    'use strict';

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    document.documentElement.classList.add('js-mt-motion');

    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn, { once: true });
    }

    ready(function () {
        revealOnScroll();
        initCounters();
        initRaceFilters();
        if (!reduce) initHeroDepth();
    });

    function revealOnScroll() {
        var nodes = Array.prototype.slice.call(document.querySelectorAll(
            '[data-mt-hero-copy],[data-mt-hero-desk],[data-mt-reveal],[data-mt-market],[data-mt-race]'
        ));
        if (!nodes.length) return;

        if (reduce || !('IntersectionObserver' in window)) {
            nodes.forEach(function (node) { node.classList.add('mt-is-visible'); });
            return;
        }

        var groups = new Map();
        nodes.forEach(function (node) {
            var parent = node.parentElement;
            var index = groups.get(parent) || 0;
            groups.set(parent, index + 1);
            node.style.transitionDelay = Math.min(index * 55, 330) + 'ms';
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('mt-is-visible');
                observer.unobserve(entry.target);
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: .08 });
        nodes.forEach(function (node) { observer.observe(node); });
    }

    function initCounters() {
        var counters = document.querySelectorAll('[data-mt-count]');
        if (!counters.length || reduce) return;
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                observer.unobserve(entry.target);
                animateNumber(entry.target);
            });
        }, { threshold: .45 });
        counters.forEach(function (counter) { observer.observe(counter); });
    }

    function animateNumber(node) {
        var target = parseInt(node.getAttribute('data-mt-count'), 10);
        if (!Number.isFinite(target)) return;
        var start = performance.now();
        var duration = 760;
        var suffix = node.getAttribute('data-mt-suffix') || '';
        var prefix = node.getAttribute('data-mt-prefix') || '';
        function frame(now) {
            var t = Math.min(1, (now - start) / duration);
            var eased = 1 - Math.pow(1 - t, 4);
            node.textContent = prefix + Math.round(target * eased) + suffix;
            if (t < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    }

    function initRaceFilters() {
        document.querySelectorAll('[data-race-filters]').forEach(function (toolbar) {
            var targetSelector = toolbar.getAttribute('data-race-filters');
            var grid = document.querySelector(targetSelector);
            if (!grid) return;
            var cards = Array.prototype.slice.call(grid.querySelectorAll('[data-race-card]'));
            toolbar.querySelectorAll('[data-race-filter]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var filter = button.getAttribute('data-race-filter');
                    toolbar.querySelectorAll('[data-race-filter]').forEach(function (other) {
                        other.classList.toggle('is-active', other === button);
                        other.setAttribute('aria-pressed', other === button ? 'true' : 'false');
                    });
                    cards.forEach(function (card) {
                        var match = filter === 'all' ||
                            (filter === 'senate' && card.getAttribute('data-chamber') === 'senate') ||
                            (filter === 'house' && card.getAttribute('data-chamber') === 'house') ||
                            (filter === 'governor' && card.getAttribute('data-chamber') === 'governor') ||
                            (filter === 'tossup' && card.getAttribute('data-rating') === 'tossup') ||
                            (filter === 'open' && card.getAttribute('data-open') === '1');
                        if (match) {
                            card.hidden = false;
                            if (!reduce) {
                                card.animate([
                                    { opacity: 0, transform: 'translateY(9px)' },
                                    { opacity: 1, transform: 'translateY(0)' }
                                ], { duration: 300, easing: 'cubic-bezier(.2,.8,.2,1)' });
                            }
                        } else {
                            card.hidden = true;
                        }
                    });
                    var visible = cards.filter(function (card) { return !card.hidden; }).length;
                    grid.setAttribute('aria-label', visible + ' races zichtbaar');
                });
            });
        });
    }

    function initHeroDepth() {
        var hero = document.querySelector('.mt-hero');
        var image = hero && hero.querySelector('.mt-hero__media img');
        var desk = hero && hero.querySelector('.mt-hero__desk');
        if (!hero || !image) return;
        var raf = 0;
        hero.addEventListener('pointermove', function (event) {
            if (window.innerWidth < 820) return;
            var rect = hero.getBoundingClientRect();
            var x = (event.clientX - rect.left) / rect.width - .5;
            var y = (event.clientY - rect.top) / rect.height - .5;
            cancelAnimationFrame(raf);
            raf = requestAnimationFrame(function () {
                image.style.transform = 'scale(1.05) translate(' + (-x * 7) + 'px,' + (-y * 5) + 'px)';
                if (desk) desk.style.transform = 'perspective(900px) rotateY(' + (x * -1.7) + 'deg) rotateX(' + (y * 1.2) + 'deg)';
            });
        });
        hero.addEventListener('pointerleave', function () {
            image.style.transform = '';
            if (desk) desk.style.transform = '';
        });
    }
})();
