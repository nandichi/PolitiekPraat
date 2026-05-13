/**
 * PolitiekPraat - editorial app JS.
 *
 * Bevat:
 *  - Theme toggle (light/dark) met localStorage en prefers-color-scheme fallback
 *  - Mobile menu drawer
 *
 * Wordt geladen als gewone <script defer> (geen module).
 */
(function () {
    "use strict";

    /* ----------------------------------------------------------------------
     * Theme toggle
     * -------------------------------------------------------------------- */
    function initTheme() {
        // Note: het initial-theme script in <head> heeft data-theme al gezet.
        document.querySelectorAll("[data-theme-toggle]").forEach(function (btn) {
            btn.addEventListener("click", function () {
                var html = document.documentElement;
                var current = html.getAttribute("data-theme") || "light";
                var next = current === "dark" ? "light" : "dark";
                html.setAttribute("data-theme", next);
                try {
                    localStorage.setItem("pp-theme", next);
                } catch (err) {
                    /* localStorage kan disabled zijn */
                }
                // Update meta theme-color zodat browser-chrome ook switcht
                var meta = document.querySelector('meta[name="theme-color"]');
                if (meta) {
                    meta.setAttribute(
                        "content",
                        next === "dark" ? "#1B1815" : "#F7F4ED"
                    );
                }
            });
        });

        // Sync wijzigingen tussen tabbladen
        window.addEventListener("storage", function (e) {
            if (e.key === "pp-theme" && e.newValue) {
                document.documentElement.setAttribute("data-theme", e.newValue);
            }
        });
    }

    /* ----------------------------------------------------------------------
     * Mobile menu drawer
     * -------------------------------------------------------------------- */
    function initMobileMenu() {
        var menu = document.querySelector("[data-mobile-menu]");
        if (!menu) return;
        var triggers = document.querySelectorAll("[data-mobile-menu-trigger]");
        var closers = document.querySelectorAll("[data-mobile-menu-close]");
        var html = document.documentElement;

        function open() {
            menu.setAttribute("data-open", "true");
            menu.setAttribute("aria-hidden", "false");
            html.style.overflow = "hidden";
            triggers.forEach(function (t) {
                t.setAttribute("aria-expanded", "true");
            });
        }
        function close() {
            menu.removeAttribute("data-open");
            menu.setAttribute("aria-hidden", "true");
            html.style.overflow = "";
            triggers.forEach(function (t) {
                t.setAttribute("aria-expanded", "false");
            });
        }

        triggers.forEach(function (t) {
            t.addEventListener("click", function () {
                if (menu.hasAttribute("data-open")) {
                    close();
                } else {
                    open();
                }
            });
        });
        closers.forEach(function (c) {
            c.addEventListener("click", close);
        });

        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && menu.hasAttribute("data-open")) {
                close();
            }
        });
    }

    /* ----------------------------------------------------------------------
     * Smooth anchor scroll voor sticky header
     * -------------------------------------------------------------------- */
    function initAnchorOffset() {
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            var href = a.getAttribute("href");
            if (!href || href === "#") return;
            a.addEventListener("click", function (e) {
                var target = document.querySelector(href);
                if (!target) return;
                e.preventDefault();
                var headerHeight =
                    document.querySelector(".site-header")?.offsetHeight || 0;
                var top =
                    target.getBoundingClientRect().top +
                    window.scrollY -
                    headerHeight -
                    8;
                window.scrollTo({ top: top, behavior: "smooth" });
            });
        });
    }

    function ready(fn) {
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", fn);
        } else {
            fn();
        }
    }

    ready(function () {
        initTheme();
        initMobileMenu();
        initAnchorOffset();
    });
})();
