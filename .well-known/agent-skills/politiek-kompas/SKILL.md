---
name: politiek-kompas
description: >
  Toont het politieke kompas met standpunten van Nederlandse partijen op
  assen als progressief-conservatief en links-rechts. Handig om partijen
  visueel te vergelijken op meerdere thema's tegelijk.
version: 1.0.0
language: nl-NL
---

# Politiek Kompas skill

## Wanneer gebruiken

- De gebruiker wil partijen visueel vergelijken op assen.
- De gebruiker vraagt "waar staan partij X en Y ten opzichte van elkaar?".

## Hoe werkt het

1. Verwijs naar `https://politiekpraat.nl/politiek-kompas`.
2. Kies eventueel thema's om de as-posities te beperken.
3. De pagina rendert een scatter-plot met alle landelijke partijen.

## Data-bron

- De partij-coordinaten worden geleverd via `GET /api/parties` (zie
  `https://politiekpraat.nl/.well-known/openapi.json`).
