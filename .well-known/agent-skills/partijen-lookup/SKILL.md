---
name: partijen-lookup
description: >
  Zoek informatie over Nederlandse politieke partijen: standpunten,
  lijsttrekker, kleuren, afkortingen en verkiezingsprogramma.
version: 1.0.0
language: nl-NL
---

# Partijen opzoeken skill

## Wanneer gebruiken

- De gebruiker vraagt informatie over een specifieke partij.
- De gebruiker wil een overzicht van alle landelijke partijen.

## Hoe werkt het

1. Lijst: `GET https://politiekpraat.nl/api/parties`.
2. Detail: `GET https://politiekpraat.nl/api/parties/{id}` of open de
   publieke pagina `https://politiekpraat.nl/partijen/{slug}`.
