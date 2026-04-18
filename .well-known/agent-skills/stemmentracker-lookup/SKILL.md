---
name: stemmentracker-lookup
description: >
  Tweede-Kamer moties en stemgedrag per partij opzoeken via de PolitiekPraat
  Stemmentracker. Filter op onderwerp, thema, indiener of periode en haal
  stemverhoudingen per partij op.
version: 1.0.0
language: nl-NL
---

# Stemmentracker opzoeken

## Wanneer gebruiken

- Gebruiker vraagt wie voor/tegen een motie heeft gestemd.
- Agent wil factchecken wat een partij heeft gestemd over een thema.
- Blog of analyse over stemgedrag van de Tweede Kamer.

## Belangrijkste MCP-tools

- `list_moties` — filter op `onderwerp`, `thema_id`, `indiener`, `uitslag`
  (aangenomen/verworpen/ingetrokken), `since`, `until`.
- `get_motie` — detail incl. stemmen per partij (voor, tegen, niet_gestemd,
  afwezig) en gekoppelde thema's.
- `list_themas` — alle thema's met `id` en `name`.
- `get_thema` — detail van één thema incl. aantal moties.
- `get_partij_stemgedrag` — overzicht per partij hoe vaak voor/tegen gestemd,
  optioneel per thema en periode.

## Authenticatie

Volledig publiek (read-only), geen OAuth nodig.

## Tip

Combineer met `analyze_partij` prompt om een geautomatiseerde analyse te
genereren van hoe een partij stemt over een specifiek thema.
