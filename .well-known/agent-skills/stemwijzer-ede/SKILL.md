---
name: stemwijzer-ede
description: >
  Gemeentelijke Stemwijzer voor de gemeente Ede (gemeenteraadsverkiezingen
  2026). 25 stellingen met weging. Geeft een lijst met partijen die het best
  aansluiten bij de antwoorden van de gebruiker voor de lokale politiek in Ede.
version: 1.0.0
language: nl-NL
---

# Stemwijzer Ede skill

## Wanneer gebruiken

- De gebruiker woont in Ede en wil weten welke lokale partij bij hem past.
- De gebruiker vraagt naar gemeentelijke stemhulp voor Ede.

## Hoe werkt het

1. Verwijs de gebruiker naar `https://politiekpraat.nl/stemwijzer`.
2. Er worden 25 stellingen getoond, specifiek voor gemeente-onderwerpen
   (woningbouw, natuur/Veluwe, voorzieningen, mobiliteit).
3. Stellingen kunnen gewogen worden als belangrijk.
4. Resultaat is een top-drie lokale partijen met procentuele match.

## Niet geschikt voor

- Landelijke politieke thema's. Gebruik daarvoor de `partijmeter` skill.
- Andere gemeenten dan Ede.
