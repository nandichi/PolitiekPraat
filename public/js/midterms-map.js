/* PolitiekPraat Midterms 2026: D3 map, filters, inspector and controls. */
(function () {
    'use strict';

    var VIEW_W = 975;
    var VIEW_H = 610;

    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn, { once: true });
    }

    function whenLibsReady(cb, attempt) {
        attempt = attempt || 0;
        if (window.d3 && window.topojson) return cb();
        if (attempt < 80) setTimeout(function () { whenLibsReady(cb, attempt + 1); }, 75);
    }

    ready(function () {
        initCountdowns();
        var maps = document.querySelectorAll('[data-midterms-map]');
        if (maps.length) whenLibsReady(function () { maps.forEach(initMap); });
    });

    function initCountdowns() {
        var nodes = document.querySelectorAll('[data-midterms-countdown]');
        if (!nodes.length) return;
        function pad(n) { return (n < 10 ? '0' : '') + n; }
        function set(node, sel, value) { var el = node.querySelector(sel); if (el) el.textContent = value; }
        function tick() {
            var now = Date.now();
            nodes.forEach(function (node) {
                var target = new Date(node.getAttribute('data-target')).getTime();
                if (isNaN(target)) return;
                var sec = Math.floor(Math.max(0, target - now) / 1000);
                set(node, '[data-cd-days]', Math.floor(sec / 86400));
                set(node, '[data-cd-hours]', pad(Math.floor((sec % 86400) / 3600)));
                set(node, '[data-cd-minutes]', pad(Math.floor((sec % 3600) / 60)));
                set(node, '[data-cd-seconds]', pad(sec % 60));
            });
        }
        tick();
        window.setInterval(tick, 1000);
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function lookup(ratings, id, objectName) {
        var raw = String(id);
        return ratings[raw] || ratings[raw.padStart(objectName === 'districts' ? 4 : 2, '0')] || null;
    }

    function ratingLabel(rating, meta) {
        return meta[rating] && meta[rating].label ? meta[rating].label : 'Geen rating';
    }

    function tooltipHtml(info, meta) {
        var parts = ['<span class="mt-tip__title">' + escapeHtml(info.name) + '</span>'];
        parts.push('<span class="mt-tip__rating">' + escapeHtml(ratingLabel(info.rating, meta)) + '</span>');
        if (info.incumbent) parts.push('<span class="mt-tip__meta">Zittend: ' + escapeHtml(info.incumbent) + '</span>');
        if (info.open) parts.push('<span class="mt-tip__meta">Open zetel</span>');
        if (info.competitive) parts.push('<span class="mt-tip__meta">Competitieve race</span>');
        return parts.join('');
    }

    function initMap(root) {
        var chamber = root.getAttribute('data-chamber') || 'senate';
        var topoUrl = root.getAttribute('data-topo');
        var feedUrl = root.getAttribute('data-feed');
        var objectName = root.getAttribute('data-object') || (chamber === 'house' ? 'districts' : 'states');
        var canvas = root.querySelector('[data-map-canvas]');
        var loading = root.querySelector('[data-map-loading]');
        var tooltip = root.querySelector('[data-map-tooltip]');
        var inspector = root.querySelector('[data-map-inspector]');
        if (!canvas || !topoUrl || !feedUrl) return;

        Promise.all([window.d3.json(topoUrl), window.d3.json(feedUrl)])
            .then(function (results) { render(results[0], results[1]); })
            .catch(function (err) {
                console.error('Midterms-kaart kon niet laden:', err);
                if (loading) loading.innerHTML = '<span>Kaart kon niet worden geladen. Bekijk de tabel hieronder.</span>';
            });

        function showTooltip(html, x, y) {
            if (!tooltip) return;
            tooltip.innerHTML = html;
            tooltip.hidden = false;
            var rect = canvas.getBoundingClientRect();
            tooltip.style.left = Math.max(110, Math.min(rect.width - 110, x - rect.left)) + 'px';
            tooltip.style.top = Math.max(90, y - rect.top) + 'px';
        }
        function hideTooltip() { if (tooltip) tooltip.hidden = true; }

        function updateInspector(info, meta) {
            if (!inspector || !info) return;
            inspector.innerHTML =
                '<span class="mt-panel-label">Geselecteerde race</span>' +
                '<strong>' + escapeHtml(info.name) + '</strong>' +
                '<p>' + (info.incumbent ? 'Zittend: ' + escapeHtml(info.incumbent) + '. ' : '') +
                (info.open ? 'Dit is een open zetel. ' : '') +
                (info.competitive ? 'Deze race staat op het competitieve battleboard.' : 'Deze race geldt momenteel niet als competitief.') + '</p>' +
                '<span class="mt-inspector-rating mt-rating--' + escapeHtml(info.rating || 'tossup') + '">' +
                escapeHtml(ratingLabel(info.rating, meta)) + '</span>';
        }

        function render(topo, feed) {
            var d3 = window.d3;
            var topojson = window.topojson;
            var ratings = feed && feed.ratings || {};
            var meta = feed && feed.ratingMeta || {};
            var obj = topo.objects && topo.objects[objectName];
            if (!obj) throw new Error('TopoJSON-object niet gevonden: ' + objectName);
            var fc = topojson.feature(topo, obj);
            var projection = d3.geoIdentity().reflectY(true).fitSize([VIEW_W, VIEW_H], fc);
            var path = d3.geoPath(projection);

            var svg = d3.select(canvas).append('svg')
                .attr('class', 'midterms-map__svg')
                .attr('viewBox', '0 0 ' + VIEW_W + ' ' + VIEW_H)
                .attr('preserveAspectRatio', 'xMidYMid meet')
                .attr('role', 'img')
                .attr('aria-label', 'Interactieve verkiezingskaart voor ' + chamber);
            var g = svg.append('g').attr('class', 'midterms-map__layer');

            var features = g.selectAll('path.mt-feat').data(fc.features).join('path')
                .attr('class', function (d) {
                    var info = lookup(ratings, d.id, objectName);
                    var cls = 'mt-feat ' + (info && info.rating ? 'mt-rating--' + info.rating : 'mt-feat--empty');
                    if (info && info.competitive) cls += ' is-competitive';
                    return cls;
                })
                .attr('d', path)
                .attr('tabindex', function (d) { return lookup(ratings, d.id, objectName) ? 0 : null; })
                .attr('role', function (d) { return lookup(ratings, d.id, objectName) ? 'button' : null; })
                .attr('aria-label', function (d) {
                    var info = lookup(ratings, d.id, objectName);
                    return info ? info.name + ': ' + ratingLabel(info.rating, meta) : null;
                })
                .on('mousemove', function (event, d) {
                    var info = lookup(ratings, d.id, objectName);
                    if (info) showTooltip(tooltipHtml(info, meta), event.clientX, event.clientY);
                    else hideTooltip();
                })
                .on('mouseleave', hideTooltip)
                .on('focus', function (event, d) {
                    var info = lookup(ratings, d.id, objectName); if (!info) return;
                    var b = this.getBoundingClientRect();
                    showTooltip(tooltipHtml(info, meta), b.left + b.width / 2, b.top + b.height / 2);
                })
                .on('blur', hideTooltip)
                .on('click', function (event, d) {
                    var info = lookup(ratings, d.id, objectName); if (!info) return;
                    features.classed('is-selected', false);
                    d3.select(this).classed('is-selected', true);
                    updateInspector(info, meta);
                })
                .on('keydown', function (event, d) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        var info = lookup(ratings, d.id, objectName); if (!info) return;
                        features.classed('is-selected', false);
                        d3.select(this).classed('is-selected', true);
                        updateInspector(info, meta);
                    }
                });

            if (objectName === 'districts' && topo.objects.states) {
                g.append('path').attr('class', 'midterms-map__borders').attr('fill', 'none')
                    .attr('d', path(topojson.mesh(topo, topo.objects.states, function (a, b) { return a !== b; })));
            }

            var zoom = d3.zoom().scaleExtent([1, 8]).on('zoom', function (event) { g.attr('transform', event.transform); });
            svg.call(zoom);
            root.querySelectorAll('[data-map-zoom]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var command = button.getAttribute('data-map-zoom');
                    if (command === 'in') svg.transition().duration(260).call(zoom.scaleBy, 1.5);
                    else if (command === 'out') svg.transition().duration(260).call(zoom.scaleBy, 0.67);
                    else svg.transition().duration(260).call(zoom.transform, d3.zoomIdentity);
                });
            });

            root.querySelectorAll('[data-map-filter]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var filter = button.getAttribute('data-map-filter');
                    root.querySelectorAll('[data-map-filter]').forEach(function (b) { b.classList.toggle('is-active', b === button); });
                    features.classed('is-dimmed', function (d) {
                        var info = lookup(ratings, d.id, objectName);
                        if (!info) return filter !== 'all';
                        if (filter === 'all') return false;
                        if (filter === 'competitive') return !info.competitive;
                        if (filter === 'tossup') return info.rating !== 'tossup';
                        if (filter === 'open') return !info.open;
                        return false;
                    });
                });
            });

            if (loading) loading.remove();
            canvas.removeAttribute('aria-hidden');
        }
    }
})();
