/*
 * Midterms 2026: interactieve D3-kaart.
 *
 * Verwacht globale d3 (v7) en topojson-client (v3), via CDN geladen in de
 * header. Initialiseert elk element met [data-midterms-map]:
 *   data-chamber  senate | house | governor
 *   data-topo     URL naar het TopoJSON-bestand (Albers USA, y-up)
 *   data-object   naam van het topo-object (states | districts)
 *   data-feed     URL naar de ratings-feed (JSON)
 *
 * De data zijn voorgeprojecteerd (Albers USA, noord-boven), dus we gebruiken
 * d3.geoIdentity().reflectY(true) en schalen met fitSize naar een vaste
 * viewBox. De SVG schaalt mee via CSS (width:100%).
 */
(function () {
    'use strict';

    var VIEW_W = 975;
    var VIEW_H = 610;

    function onReady(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    // d3 en topojson worden met defer geladen; wacht kort tot ze klaar zijn.
    function whenLibsReady(cb, attempt) {
        attempt = attempt || 0;
        if (window.d3 && window.topojson) {
            cb();
            return;
        }
        if (attempt > 80) {
            return;
        }
        setTimeout(function () {
            whenLibsReady(cb, attempt + 1);
        }, 75);
    }

    onReady(function () {
        initCountdowns();
        var maps = document.querySelectorAll('[data-midterms-map]');
        if (maps.length) {
            whenLibsReady(function () {
                Array.prototype.forEach.call(maps, initMap);
            });
        }
    });

    // ---- Countdown naar Election Day -------------------------------------
    function initCountdowns() {
        var nodes = document.querySelectorAll('[data-midterms-countdown]');
        if (!nodes.length) {
            return;
        }
        function pad(n) {
            return (n < 10 ? '0' : '') + n;
        }
        function tick() {
            var now = Date.now();
            Array.prototype.forEach.call(nodes, function (node) {
                var target = new Date(node.getAttribute('data-target')).getTime();
                if (isNaN(target)) {
                    return;
                }
                var diff = Math.max(0, target - now);
                var sec = Math.floor(diff / 1000);
                var days = Math.floor(sec / 86400);
                var hours = Math.floor((sec % 86400) / 3600);
                var mins = Math.floor((sec % 3600) / 60);
                var secs = sec % 60;
                set(node, '[data-cd-days]', days);
                set(node, '[data-cd-hours]', pad(hours));
                set(node, '[data-cd-minutes]', pad(mins));
                set(node, '[data-cd-seconds]', pad(secs));
            });
        }
        function set(node, sel, value) {
            var el = node.querySelector(sel);
            if (el) {
                el.textContent = value;
            }
        }
        tick();
        setInterval(tick, 1000);
    }

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function ratingLabel(rating, meta) {
        return (meta[rating] && meta[rating].label) || 'Onbeslist';
    }

    function tooltipHtml(info, meta) {
        var parts = [];
        parts.push('<span class="mt-tip__title">' + escapeHtml(info.name) + '</span>');
        parts.push(
            '<span class="mt-tip__rating mt-rating--' + escapeHtml(info.rating || 'tossup') + '">' +
            escapeHtml(ratingLabel(info.rating, meta)) +
            '</span>'
        );
        if (info.incumbent) {
            parts.push('<span class="mt-tip__meta">Zittend: ' + escapeHtml(info.incumbent) + '</span>');
        }
        if (info.open) {
            parts.push('<span class="mt-tip__meta">Open zetel</span>');
        }
        if (info.competitive) {
            parts.push('<span class="mt-tip__meta">Competitieve race</span>');
        }
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

        if (!canvas || !topoUrl || !feedUrl) {
            return;
        }

        Promise.all([window.d3.json(topoUrl), window.d3.json(feedUrl)])
            .then(function (results) {
                render(results[0], results[1]);
            })
            .catch(function (err) {
                if (window.console) {
                    console.error('Midterms-kaart kon niet laden:', err);
                }
                if (loading) {
                    loading.innerHTML = '<span>Kaart kon niet worden geladen. Bekijk de tabel hieronder.</span>';
                }
            });

        function showTooltip(html, x, y) {
            if (!tooltip) {
                return;
            }
            tooltip.innerHTML = html;
            tooltip.hidden = false;
            var rect = canvas.getBoundingClientRect();
            tooltip.style.left = (x - rect.left) + 'px';
            tooltip.style.top = (y - rect.top) + 'px';
        }

        function hideTooltip() {
            if (tooltip) {
                tooltip.hidden = true;
            }
        }

        function render(topo, feed) {
            var d3 = window.d3;
            var topojson = window.topojson;
            var ratings = (feed && feed.ratings) || {};
            var meta = (feed && feed.ratingMeta) || {};

            var obj = topo.objects && topo.objects[objectName];
            if (!obj) {
                throw new Error('TopoJSON-object niet gevonden: ' + objectName);
            }
            var fc = topojson.feature(topo, obj);

            var projection = d3.geoIdentity().reflectY(true).fitSize([VIEW_W, VIEW_H], fc);
            var path = d3.geoPath(projection);

            var svg = d3.select(canvas).append('svg')
                .attr('class', 'midterms-map__svg')
                .attr('viewBox', '0 0 ' + VIEW_W + ' ' + VIEW_H)
                .attr('preserveAspectRatio', 'xMidYMid meet')
                .attr('role', 'img')
                .attr('aria-label', 'Interactieve kaart van de Verenigde Staten');

            var g = svg.append('g').attr('class', 'midterms-map__layer');

            g.selectAll('path.mt-feat')
                .data(fc.features)
                .join('path')
                .attr('class', function (d) {
                    var info = ratings[d.id];
                    var cls = 'mt-feat';
                    if (info && info.rating) {
                        cls += ' mt-rating--' + info.rating;
                    } else {
                        cls += ' mt-feat--empty';
                    }
                    if (info && info.competitive) {
                        cls += ' is-competitive';
                    }
                    return cls;
                })
                .attr('d', path)
                .attr('tabindex', function (d) { return ratings[d.id] ? 0 : null; })
                .attr('role', function (d) { return ratings[d.id] ? 'button' : null; })
                .attr('aria-label', function (d) {
                    var info = ratings[d.id];
                    return info ? (info.name + ': ' + ratingLabel(info.rating, meta)) : null;
                })
                .on('mousemove', function (event, d) {
                    var info = ratings[d.id];
                    if (info) {
                        showTooltip(tooltipHtml(info, meta), event.clientX, event.clientY);
                    } else {
                        hideTooltip();
                    }
                })
                .on('mouseleave', hideTooltip)
                .on('focus', function (event, d) {
                    var info = ratings[d.id];
                    if (!info) { return; }
                    var b = this.getBoundingClientRect();
                    showTooltip(tooltipHtml(info, meta), b.left + b.width / 2, b.top + b.height / 2);
                })
                .on('blur', hideTooltip)
                .on('click', function (event, d) {
                    var info = ratings[d.id];
                    if (info && info.href) {
                        window.location.href = info.href;
                    }
                })
                .on('keydown', function (event, d) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        var info = ratings[d.id];
                        if (info && info.href) {
                            event.preventDefault();
                            window.location.href = info.href;
                        }
                    }
                });

            // Voor het Huis: staatsgrenzen als overlay voor leesbaarheid.
            if (objectName === 'districts' && topo.objects.states) {
                g.append('path')
                    .attr('class', 'midterms-map__borders')
                    .attr('fill', 'none')
                    .attr('d', path(topojson.mesh(topo, topo.objects.states, function (a, b) { return a !== b; })));
            }

            // Lichte pan/zoom.
            var zoom = d3.zoom().scaleExtent([1, 8]).on('zoom', function (event) {
                g.attr('transform', event.transform);
            });
            svg.call(zoom);

            if (loading) {
                loading.remove();
            }
            canvas.removeAttribute('aria-hidden');
        }
    }
})();
