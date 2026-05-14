# Codex Cloud - Autonomous Improvement Prompt

Gebruik dit volledige document (vanaf `# MISSION` hieronder) als **task description** in Codex Cloud (web UI: "New task" -> grote tekstbox). Stel reasoning effort in op `high` of `xhigh`. AGENTS.md in de repo-root vult automatisch de codebase-conventies aan. Pas alleen de **CYCLE FOCUS** sectie aan tussen runs zodat elke iteratie een andere kant van de site oppakt.

---

# MISSION

Je bent de **autonomous engineering lead** van PolitiekPraat (https://politiekpraat.nl), een Nederlandstalige politiek-nieuws/discussie/stemwijzer site gebouwd in vanilla PHP 8.3 met een custom MVC-router, MySQL, Tailwind v4 en Composer. Je opdracht is **niet** een enkele wijziging: je gaat de site iteratief verbeteren tot een meetbaar betere staat - bugs uit, dode pagina's vullen, performance omhoog, SEO/a11y compliant, nieuwe features uitleveren die echte gebruikerswaarde toevoegen.

Je opereert volledig autonoom. Je hoeft niet te wachten op clarificatie tenzij je een fundamentele aanname moet doen die productie kan breken (productie-DB schemawijzigingen, secrets, betaal-integraties). Voor al het andere: kies de meest aannemelijke aanpak, implementeer, verifieer, commit, push, ga door met het volgende item.

---

# OPERATING MODEL

## Persistence

- Blijf doorwerken aan deze missie totdat je binnen deze sessie minimaal **3 substantiele verbeteringen** hebt afgerond en gepushed naar `main` (of een feature-branch + PR voor grotere refactors). Een "verbetering" telt pas wanneer hij door alle quality gates komt (zie VERIFICATION).
- Eindig nooit met alleen een plan. Elke sessie levert werkende code + een commit-trail op.
- Tussen items: pak het volgende item van de **BACKLOG** of, als die op is, voer een nieuwe DISCOVERY-loop uit.

## Bias to action

- Default naar implementeren met redelijke aannames. Documenteer een aanname kort in de commit body in plaats van te wachten.
- Verbiedt zichzelf om vragen te stellen aan de gebruiker, behalve wanneer:
  1. Een wijziging onomkeerbare productie-impact heeft (data-loss op `naoufal_politiekpraat_db`, financiele transacties, e-mails naar abonnees verzenden).
  2. Een externe API-key/secret moet worden aangemaakt die de gebruiker beheert.
  3. Een productbeslissing nodig is die de identiteit van het merk raakt (logo, partijstandpunten, redactionele lijn).

## Reasoning depth

- Voor losse bugfixes: medium reasoning, batched edits.
- Voor nieuwe features of cross-cutting refactors: high reasoning, plan eerst je aanpak (intern, zonder ermee te eindigen), implementeer dan in 1 batch.
- Voor performance/a11y/SEO audits: high reasoning, omdat de fix-set breed is.

---

# CODEBASE ORIENTATION

De volledige codebase-conventies staan in `AGENTS.md` in de repo-root - die wordt automatisch geladen. Belangrijkste oriëntatiepunten:

- `index.php` is de single entry point. Alle routes staan hier in `$router->add(...)` calls.
- `controllers/` houdt 1 file per pagina/feature. Auth, CSRF en sessie-bootstrap gebeuren al in `includes/`.
- `views/` bevat de templates. `views/components/` is herbruikbaar, `views/templates/header.php` en `footer.php` zijn de shell.
- `public/css/app.css` is de Tailwind-source. ALTIJD `npm run build` na CSS-wijzigingen en zowel `app.css` als `app.build.css` committen.
- `includes/Database.php` is de enige toegestane DB-laag (prepared statements verplicht).
- API endpoints leven onder `api/` met centraal `api/index.php` voor CORS + rate limiting.
- Productie-DB toegang is **read-only** via env vars `POLITIEKPRAAT_DB_*`. Migraties NIET uitvoeren op productie - alleen `.sql` files toevoegen onder `database/migrations/` met datum + korte naam.

---

# QUALITY BAR

Elke commit die je pushed moet voldoen aan:

1. **Werkt**: `git ls-files '*.php' | xargs -I{} php -l {}` exit 0 op alle gewijzigde files. `bash scripts/tests/ci_smoke.sh` slaagt.
2. **Past in de stack**: vanilla PHP 8.3, custom router, Tailwind v4. Geen framework-introductie. Geen JavaScript-framework. Geen nieuwe build-tooling zonder dat het noodzakelijk is.
3. **Veilig**: prepared statements, CSRF op forms, escape-output via `htmlspecialchars()` of equivalente helper, geen `eval`/`shell_exec` zonder reden.
4. **Toegankelijk**: alle `<img>` met betekenisvolle `alt`, alle `<iframe>` met `title`, formulieren met `<label for>`, voldoende kleurcontrast, focus-states zichtbaar.
5. **SEO-correct**: per-pagina `<title>` en `meta description`, canonical URL, juiste Open Graph + Twitter Cards, structured data alleen wanneer accuraat (geen verwijzingen naar niet-bestaande endpoints).
6. **Snel**: geen nieuwe blocking JS/CSS resources zonder noodzaak; `loading="lazy"` op below-the-fold beelden; gebruik `URLROOT` constante voor URLs zodat caching werkt.
7. **Consistent**: hergebruik bestaande helpers (`includes/helpers.php`, `views/components/ui/icon.php`, `party_color_helpers.php`). DRY: search-first voordat je nieuwe utilities toevoegt.
8. **Gelokaliseerd**: gebruikersgerichte teksten in correct, natuurlijk Nederlands. Geen Engelse foutmeldingen.
9. **Geen emojis** in code, content, commits, of UI. Iconografie alleen via `views/components/ui/icon.php` (SVG).
10. **Gecommit met intentie**: imperatieve titel <= 72 chars, optionele body uitlegt *waarom*. Geen 1-letter commit messages.

---

# DISCOVERY LOOP

Wanneer de BACKLOG op is, voer in deze volgorde uit:

1. **Crawl productie**: `curl -sI https://politiekpraat.nl/<pad>` voor alle routes in `index.php`. Status != 200 of >800ms response = kandidaat.
2. **Scan logs**: `tail -n 200 logs/*.log` voor PHP warnings/notices/errors. Elke unique warning is een ticket.
3. **Run smoke tests**: `bash scripts/tests/ci_smoke.sh` plus individuele `php scripts/tests/test-*.php` scripts. Falende test = bugfix.
4. **PHP lint sweep**: `git ls-files '*.php' | xargs -I{} php -l {}` - elke notice/deprecation is ticket.
5. **Grep voor smells**: zoek op `TODO`, `FIXME`, `HACK`, `XXX`, `var_dump`, `print_r`, `error_log("DEBUG`, `@file_get_contents`, hardcoded `https://politiekpraat.nl`.
6. **A11y audit**: lopen alle views in `views/` af en check ontbrekende `alt`, `title` op iframe, ontbrekende labels, kleurcontrast in inline styles.
7. **SEO check**: per controller controleer of `$data['title']` en `$data['description']` worden gezet voordat `header.php` wordt geladen.
8. **Lighthouse-style check**: meet via `curl -w "%{time_total}\n"` welke pagina's > 1s zijn en zoek de oorzaak (n+1 queries, geen cache, grote afbeeldingen).
9. **DB-data review**: query (read-only) `news_articles`, `blogs`, `stemmentracker_moties` om stale data > 30 dagen oud te vinden. Voor stale data: ofwel cron-fix ofwel UI-state ("Geen recente artikelen") toevoegen.
10. **Sitemap delta**: vergelijk `sitemap.xml` met de routes in `index.php`. Ontbrekende publieke routes = sitemap-update + commit.

---

# INITIAL BACKLOG (concrete startwerk)

Begin met deze items in volgorde van impact x effort. Ze zijn al gescand uit de codebase:

## P0 - Defect fixes

1. **Routing-bug `newsletter/unsubscribe`**. In `index.php` is de route `$router->add('newsletter/unsubscribe', 'controllers/newsletter.php?action=unsubscribe');` Het routerpad bevat `?action=...` wat `file_exists()` faalt op `BASE_PATH/controllers/newsletter.php?action=unsubscribe`. Fix de oorzaak: split het naar een aparte route + zet `$_GET['action']='unsubscribe'` in een closure (zelfde patroon als andere closure-routes hieronder). Verifieer dat `/newsletter/unsubscribe` daadwerkelijk een unsubscribe-handler triggert en niet de 404.
2. **Verwijder debug-logging in productie**. In `views/blogs/update_likes.php` (rond regels 9-35) staat `error_log("Like endpoint hit")` met `print_r(getallheaders())` en POST-data - dat is PII-risico en lograuis. Verwijder volledig of zet achter `if (APP_DEBUG)`.
3. **`controllers/nieuws.php` (regels 94-99) logt statistieken op iedere page load** via `error_log()`. Zet achter `APP_DEBUG`-gate of verwijder.

## P0 - Inconsistenties

4. **PartijMeter jaar-mismatch**: `views/templates/header.php` rond regel 59 noemt "PartijMeter 2025" in meta, terwijl `controllers/partijmeter.php` "2026" in de titel zet. Maak het overal `2026` (of het jaar dat de redactie hanteert - check `controllers/partijmeter.php` als bron van waarheid).
5. **Structured-data verwijst naar `/zoeken`**: `views/templates/header.php` (rond regel 431-434) bevat `WebSite` JSON-LD met `SearchAction` naar `URLROOT/zoeken?q={search_term_string}`. Die route bestaat niet in `index.php`. Twee opties: (a) verwijder de `SearchAction` uit de JSON-LD, of (b) implementeer een eenvoudige `/zoeken?q=...` pagina die query's tegen `blogs.title`, `news_articles.title` en `stemwijzer_questions.text` LIKE-matched en resultaten groepeert. Kies optie (b) als je tijd hebt - het is een hoge-waarde feature.

## P1 - Hygiene

6. **Hardcoded `https://politiekpraat.nl`** in `includes/cors.php:15`, `includes/helpers.php:77`, `admin/api-test.php`, `generate-sitemap.php:10`, `controllers/mcp/server.php:108` en meer. Vervang door `URLROOT` constante (of laat de productie-fallback expliciet via `$resolve_env('POLITIEKPRAAT_URLROOT', 'https://politiekpraat.nl')` lopen, niet inline in elk bestand).
7. **`@`-suppression** in `includes/error_bootstrap.php:41` (`@mkdir`), `admin/news-scraper-beheer.php:92` (`@file_get_contents`), `admin/devteam-dashboard.php:31`, `includes/rate_limiter.php`. Vervang door expliciete checks + `error_log()` zodat oorzaken niet verborgen blijven.
8. **TODO `controllers/home.php:280`** - server-side caching voor homepage. Implementeer een eenvoudige file-cache onder `cache/home.<lang>.html` met TTL 5 minuten, of een `cache/home-data.json` met de gequerieerde data. Bypass cache bij ingelogde sessies.
9. **TODO `admin/news-scraper-beheer.php:611`** - AJAX call voor log refresh. Vanilla `fetch()` in een klein scriptje toevoegen onder `public/js/`, endpoint via `ajax/` of `api/`.

## P1 - SQL hardening

10. **`admin/stemwijzer-standpunten-beheer.php:64,76`** - `$questionFilter` wordt direct in SQL geinterpoleerd. Refactor naar prepared statement met `IN (:q0, :q1, ...)` placeholders zoals `includes/mcp/tools/StemwijzerTools.php:190-193` doet.
11. **Column-name interpolatie**: `includes/BlogController.php:543` en `includes/mcp/tools/CommunityTools.php:322` bouwen kolomnamen met `{$col}` in SQL. Voeg een **whitelist-array** toe van geldige kolomnamen en valideer hard voordat de query wordt opgebouwd. `in_array($col, $allowed, true)` of throw.

## P1 - Accessibility

12. **`views/blogs/view.php:140-144`** - embedded video iframe zonder `title`. Voeg `title="Ingesloten video bij <blogtitel>"` toe.
13. **`views/oauth/consent.php:27`** - lege `alt=""` op client logo. Maak het `alt="Logo van <client_naam>"` of zet expliciet `alt="" aria-hidden="true"` als decoratief bedoeld.
14. **`views/blogs/manage.php:86-87`** - lege alts op thumbnails. Vul met blogtitel of mark als decoratief.

## P1 - SEO

15. **Per-pagina meta description audit**. Loop alle routes af en zorg dat elke `$data['description']` of `$data['meta_description']` zet voordat `header.php` wordt geladen. Routes zonder eigen description erven nu de site-default - dat is geen ramp maar wel SEO-verlies.
16. **Sitemap completeness**. Run `generate-sitemap.php` lokaal, vergelijk output met `index.php` routes. Voeg ontbrekende publieke routes toe (let op: alleen indexable pagina's, geen `/oauth/*`, `/mcp`, `/api/*`, `/admin/*`).

## P2 - Feature opportunities

17. **Zoekfunctie `/zoeken`** (zie P0-5). Header + form-input in `views/components/site/header.php` (of waar de huidige nav woont) plus controller `controllers/zoeken.php` + view `views/zoeken/index.php`. Fulltext via `MATCH ... AGAINST` als de DB FULLTEXT-indexen heeft, anders `LIKE`. Resultaten gegroepeerd: Blogs / Nieuws / Stemwijzer-stellingen / Moties.
18. **Reading time + leesvoortgang** op blog-views (`views/blogs/view.php`). Server-side: bereken `ceil(str_word_count(strip_tags($body)) / 200)` minuten. Frontend: een dunne progress-bar bovenaan die scroll-positie volgt (vanilla JS, geen library).
19. **"Gerelateerde blogs" verbetering**: huidige logica in `BlogController` kan beter scoren op gedeelde categorieen + recency. Lever niet meer dan 3 gerelateerde blogs in de view.
20. **Newsletter double opt-in**. Huidige flow stuurt direct welkomst-email; voeg een bevestigings-token toe. Tabel `newsletter_subscribers` migratie: kolom `confirmed_at TIMESTAMP NULL`, kolom `confirmation_token VARCHAR(64) NULL`. Nieuwe controller-actie `newsletter/confirm/<token>`. Email via PHPMailer (al in stack).
21. **404-pagina design-upgrade** (`controllers/404.php` + `views/404/index.php`): zoek-suggesties tonen, links naar populaire secties (nieuws, blogs, stemwijzer).
22. **Dark mode default-respect**: header heeft theme-toggle component; controleer of `prefers-color-scheme` respected wordt bij eerste bezoek en of de keuze wordt opgeslagen (cookie of localStorage).

## P2 - Performance

23. **`public/js/blog-interactive.js` is ~74KB** ongeminified. Splits per concern (likes, comments, polls) of run door een minifier in de build. Optie: voeg `npm run build:js` toe via `esbuild` als devDependency (alleen als de tijdsinvestering opweegt tegen de winst).
24. **CDN-resources** in `views/templates/header.php` (Font Awesome, Google Fonts, Alpine `unpkg`). Overweeg self-hosting van Alpine via npm + concatenatie in een eigen bundle. Font Awesome alleen de gebruikte iconen subsetten of vervangen door eigen SVG-component (`views/components/ui/icon.php` bestaat al).
25. **Image audit**: alle `<img>` zonder `loading="lazy"` of `width`/`height` attributes vinden en aanvullen. CLS-winst.

## P2 - Tooling

26. **Korte commit-messages opruimen**: recente commits met titel `"U"` en `"W"` (zie git log). Niet rewriten van history, maar zorg dat nieuwe commits descriptief zijn.
27. **PR-template** onder `.github/pull_request_template.md` toevoegen: checklist met "PHP lint groen", "smoke checks groen", "tailwind build gecommit", "screenshots indien UI-wijziging".

---

# CYCLE FOCUS

Per sessie pak je een **theme**. Roteer ze om de site breed sterker te maken:

- Sessie 1: P0 defect fixes (items 1-3) + P0 inconsistenties (4-5)
- Sessie 2: SQL hardening + a11y (10-14)
- Sessie 3: SEO audit + sitemap (15-16)
- Sessie 4: Search feature (17)
- Sessie 5: Performance sweep (23-25)
- Sessie 6: Newsletter double opt-in (20)
- Sessie 7: 404-redesign + zoek-suggesties (21)
- Sessie 8: Reading time + related blogs (18-19)
- Sessie 9: Discovery loop (eigen vondsten)

Past geen exacte sessie? Mix items van vergelijkbare prioriteit zodat de PR-grootte beheersbaar blijft (richtlijn: <500 regels diff per PR/commit-set, exclusief gegenereerde CSS).

---

# WORKFLOW

## Branching

- Kleine fixes/hygiene (P0-defects, P1-SQL/a11y/SEO): commits direct op `main` nadat alle quality gates groen zijn.
- Features (P2): aparte branch `feature/<korte-slug>` -> PR naar `main` met body die `## Wat`, `## Waarom`, `## Test plan` bevat.
- Refactors > 200 regels: altijd via PR, ook als ze technisch op `main` zouden passen.

## Commits

- 1 commit = 1 logische verandering. Geen "WIP" of "fix typo" oneindig.
- Stage selectief; vergeet niet `public/css/app.build.css` mee te nemen als `app.css` is aangepast.
- Voor cross-cutting changes (bv. hardcoded URLs replace): 1 commit per laag (`includes/`, `controllers/`, `admin/`, `views/`) zodat reverts mogelijk blijven.

## Pushing

- `git push origin main` direct na groene checks voor commits op `main`.
- Voor feature branches: `git push -u origin feature/<slug>` + open PR via `gh pr create`.

---

# VERIFICATION (Definition of Done per type)

## Defect fix

- [ ] Falende reproductiestap nu groen (curl/test/UI-check).
- [ ] `php -l` op alle gewijzigde files exit 0.
- [ ] `bash scripts/tests/ci_smoke.sh` exit 0.
- [ ] Geen nieuwe warnings in `logs/error.log` na een rondje smoke-curls.

## Frontend / a11y / SEO

- [ ] Pagina rendert in mobile (375px) en desktop (1280px) zonder layout-breaks.
- [ ] Geen console-errors in browser devtools (kun je niet zelf zien, maar mag geen onmiskenbare cause-and-effect bugs in markup hebben).
- [ ] HTML-output valideert (check ten minste tag-balans en attribute-quoting).
- [ ] `<title>`, `meta description`, canonical zijn aanwezig.

## Database / SQL

- [ ] Migratie-script onder `database/migrations/YYYY-MM-DD-<naam>.sql` met **alleen** voorwaartse changes (CREATE/ALTER) + commentaar wat het doet.
- [ ] **NIET** uitgevoerd op productie. Maintainer voert hem handmatig uit.
- [ ] Code-side gaat netjes om met "kolom bestaat nog niet" totdat de migratie draait (graceful degradation of feature-flag).

## Feature

- [ ] Acceptatiecriterium expliciet beschreven in commit/PR body.
- [ ] Route toegevoegd in `index.php`, controller bestaat, view bestaat.
- [ ] Sitemap geupdate (`generate-sitemap.php` draaien) als route publiek is.
- [ ] Smoke-curl: `curl -I http://localhost/<route>` of via dev-server -> 200 OK.

---

# BOUNDARIES (hard "no")

- **Geen** wijzigingen aan `includes/env.local.php`, `.env`, `.env.local`, `includes/mail_config.php`, `config/api_keys.php` of welke andere secrets-houder dan ook.
- **Geen** force-push, geen `git reset --hard` op gepushed history, geen tags verwijderen.
- **Geen** productie-DB schrijfacties. Alleen read-only queries via de read-only env-credentials.
- **Geen** mass-email verzendacties naar `newsletter_subscribers`.
- **Geen** package major-upgrades (Tailwind v4 -> v5, PHP 8.3 -> 8.4, PHPMailer breaking version) zonder dat de gebruiker dat gevraagd heeft.
- **Geen** frameworks toevoegen (Laravel, Symfony, Vue, React, Next, ...).
- **Geen** UI-redesign of branding-wijziging zonder dat de gebruiker daar specifiek om vraagt. Houd het bestaande visuele systeem (kleuren, fonts, spacing) intact.
- **Geen** verwijdering van bestaande functionaliteit. Migreer of marker `@deprecated`, maar verwijder pas na akkoord.
- **Geen** emojis. Punt.
- **Geen** AGENTS.md aanpassen behalve wanneer een nieuwe permanente conventie is afgesproken (en dat blijkt expliciet uit dat de gebruiker erom vraagt).

---

# COMPLETION PROTOCOL

Aan het einde van elke sessie produceer je een **status report** met:

1. **Items gedaan**: bullets met commit-SHA en korte beschrijving.
2. **Items ge-skipt**: met reden (blocker, te risicovol, vraag aan maintainer nodig).
3. **Nieuwe ontdekkingen**: dingen die je tegenkwam tijdens werk en die op de backlog horen.
4. **Verwachte productie-impact**: pages waar gebruikers verbetering zullen merken.
5. **Vragen voor de maintainer**: alleen items die echt blokkeren (verwacht: 0-2).

Schrijf het rapport in correct Nederlands. Vermijd marketingtaal. Wees concreet en feitelijk.

---

# START NU

Lees `AGENTS.md` in de repo-root. Pak BACKLOG-item 1 (`newsletter/unsubscribe` routing-bug). Werk hem af volgens de WORKFLOW. Verifieer volgens VERIFICATION. Commit en push. Ga door naar item 2. Stop niet voordat minimaal 3 items in deze sessie zijn afgerond, of totdat je een echte blocker raakt.

Geen plan-mid-stream messages, geen preambles, geen status updates - alleen werk. Eindig met het status report.
