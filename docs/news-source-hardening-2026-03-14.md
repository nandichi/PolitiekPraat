# Hardening rapport - Laatste Politiek Nieuws (2026-03-14)

## Doel
Stabiliteit van het rechtse/centrum-rechtse perspectief verhogen als structurele backup voor Telegraaf (HTTP 403), zonder UX/styling aan te passen.

## Gekozen extra bronnen (rechts/centrum-rechts)
1. **EW Magazine** (centrum-rechts)
   - Politieke feed: `https://www.ewmagazine.nl/politiek/feed/`
   - Fallback feed: `https://www.ewmagazine.nl/feed/`
2. **Wynia's Week** (rechts-conservatief)
   - Politieke feed: `https://www.wyniasweek.nl/category/politiek/feed/`
   - Fallback feed: `https://www.wyniasweek.nl/feed/`

## Waarom deze keuze
- Beide feeds geven direct RSS/XML responses met recente items.
- Beide bevatten consistente artikel-links (https URLs met stabiele host).
- Beide voegen inhoudelijk andere invalshoeken toe dan AD/NU.nl, waardoor de kans op lege rechterkolom kleiner wordt bij bronuitval.

## Snelle bronkwaliteitscheck (runtime test)
Uitgevoerde checks (2026-03-14): HTTP status, item count, recentheid, voorbeeldlink.

- Telegraaf politiek: `403` (bekend probleem)
- AD politiek: `200`, 30 items, recente publicatiedata
- NU.nl politiek: `200`, 30 items, recente publicatiedata
- **EW politiek**: `200`, actuele publicaties
- **Wynia's Week politiek**: `200`, actuele publicaties

## Hardening in dataflow/logica
- Multi-feed fallback per bron blijft actief (`rss_urls` lijst met primary + fallback).
- XML parsing robuuster gemaakt door malformed ampersand-fix op alle feeds (niet alleen NU.nl).
- Dedupe, URL-validatie en datum-validatie blijven actief in `NewsScraper` + `NewsModel`.
- Bronstatus backward compatible gehouden (`rss_url` + `rss_urls`) voor admin dashboards.
- Compacte monitoring toegevoegd:
  - `getHealthReport(48)` per bron + per oriëntatie
  - logging van links/rechts recency en stale sources in `auto_news_scraper.php`
  - zichtbaar in `run_news_scraper.php` output

## Impact op UX
- Geen styling of template wijzigingen.
- Alleen bronconfiguratie, feedverwerking en monitoring aangepast.

## Gewijzigde bestanden
- `includes/NewsScraper.php`
- `includes/NewsAPI.php`
- `api/endpoints/news.php`
- `scripts/run_news_scraper.php`
- `scripts/auto_news_scraper.php`
- `admin/news-scraper-beheer.php`
- `docs/news-source-hardening-2026-03-14.md`
