---
name: nieuws-search
description: >
  Recent Nederlands politiek nieuws doorzoeken uit de aggregator van
  PolitiekPraat. Ondersteunt filters op zoekterm, bron, bias en oriëntatie,
  plus een detail-view per artikel.
version: 1.0.0
language: nl-NL
---

# Politiek nieuws zoeken

## Wanneer gebruiken

- Gebruiker vraagt naar recent politiek nieuws in Nederland.
- Gebruiker wil een nieuwsoverzicht per partij, thema of bron.
- Agent wil bronnen verzamelen voor een blog via `summarize_news_day`.

## Belangrijkste MCP-tools

- `list_nieuws` — lijst met filters `search`, `source`, `bias`
  (links/rechts/midden), `orientation` (progressief/conservatief/centrum),
  `since`, `until`, `limit`, `offset`.
- `get_nieuws` — detail per `id`.
- `list_news_sources` — alle unieke bronnen met aantallen en bias/oriëntatie.

## Authenticatie

Volledig publiek (read-only), geen OAuth nodig.

## Workflow voor een dagoverzicht

1. Roep `list_nieuws` aan met `since=YYYY-MM-DD` (vandaag min 1 dag).
2. Groepeer op `source` of `bias` voor balans.
3. Combineer met prompt `summarize_news_day` voor een afgeronde samenvatting,
   eventueel met `publish_as_blog=true` om direct een draft aan te maken.
