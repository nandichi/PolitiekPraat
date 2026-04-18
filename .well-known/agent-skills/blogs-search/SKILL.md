---
name: blogs-search
description: >
  Zoek en lees politieke blogs van PolitiekPraat. Blogs dekken actuele
  Nederlandse politiek, analyses, opinie en debat. Resultaten kunnen worden
  gefilterd op trefwoord.
version: 1.0.0
language: nl-NL
---

# Blogs zoeken skill

## Wanneer gebruiken

- De gebruiker zoekt opinie-artikelen of analyses over een politiek thema.
- De gebruiker vraagt "wat schrijft PolitiekPraat over X?".

## Hoe werkt het

1. Roep `GET https://politiekpraat.nl/api/blogs?search={query}&limit=20` aan.
2. Toon titel, summary en publicatiedatum uit de response.
3. Open een specifieke blog via `https://politiekpraat.nl/blogs/{slug}`.

## Authenticatie

- Lezen is publiek, geen auth vereist.
- Voor schrijven (create/update) is OAuth met scope `blogs.write` vereist.
  Zie `https://politiekpraat.nl/.well-known/oauth-protected-resource`.
