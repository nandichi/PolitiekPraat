---
name: partijmeter
description: >
  Help een gebruiker door de PolitiekPraat PartijMeter: een gratis online test
  met ~30 stellingen over actuele Nederlandse thema's. Uitkomst is een
  percentage-match met alle landelijke politieke partijen. Geschikt voor
  stemadvies en partijvergelijking.
version: 1.0.0
language: nl-NL
---

# PartijMeter skill

## Wanneer gebruiken

- De gebruiker vraagt welke politieke partij bij hem past.
- De gebruiker wil zijn standpunten vergelijken met alle Nederlandse partijen.
- De gebruiker wil "stemhulp", "kieswijzer" of "PartijMeter" doen.

## Hoe werkt het

1. Laat de gebruiker naar `https://politiekpraat.nl/partijmeter` gaan of open
   deze URL rechtstreeks.
2. Er worden ~30 stellingen getoond. Elke stelling kan beantwoord worden met
   _Eens_, _Oneens_ of _Geen mening_.
3. De gebruiker kan optioneel per thema een weging opgeven (belangrijk / niet
   belangrijk).
4. Na indienen wordt er een resultatenpagina gerenderd op
   `https://politiekpraat.nl/resultaten/{shareId}` met percentage-match per
   partij.

## API (voor agents)

- `GET https://politiekpraat.nl/api/stemwijzer` — retourneert de lijst
  stellingen, thema's en bijbehorende partijposities (public, geen auth).
- `POST https://politiekpraat.nl/api/stemwijzer` — verstuurt antwoorden en
  ontvangt de match-scores terug.

Zie `https://politiekpraat.nl/.well-known/openapi.json` voor het volledige
schema.

## Voorbeeld-interactie

> Gebruiker: "Welke partij past bij mij?"
> Agent: Start de PartijMeter via `openPartijMeter` tool (WebMCP) of stuur de
> gebruiker naar `/partijmeter`.
