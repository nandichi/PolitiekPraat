# ERD - Gemeentelijke Stemwijzer (Ede 2026 MVP)

Scope: **Ede**, **gemeenteraadsverkiezingen 2026**, **25 stellingen**, **weging aan**.

## Entiteiten

- `municipalities`
- `elections`
- `election_municipalities`
- `election_parties`
- `theses`
- `thesis_versions`
- `party_positions`
- `thesis_weights`
- `stemwijzer_result_contexts` (koppelt bestaande `stemwijzer_results` aan datasetversie/context)

## Relaties (hoog niveau)

```text
municipalities (1) ----< (N) election_municipalities (N) >---- (1) elections

 election_municipalities (1) ----< (N) election_parties
 election_municipalities (1) ----< (N) theses

 theses (1) ----< (N) thesis_versions
 thesis_versions (1) ----< (1) thesis_weights

 election_parties (1) ----< (N) party_positions (N) >---- (1) thesis_versions

 stemwijzer_results (1) ----< (1) stemwijzer_result_contexts
 election_municipalities (1) ----< (N) stemwijzer_result_contexts
 thesis_versions (1) ----< (N) stemwijzer_result_contexts
```

## Backward compatibility ontwerp

- De bestaande landelijke stemwijzer-tabellen (`stemwijzer_questions`, `stemwijzer_parties`, `stemwijzer_positions`, `stemwijzer_results`) blijven intact.
- Nieuwe gemeentelijke structuur staat volledig naast het huidige model.
- Resultaatversie-koppeling is toegevoegd via **nieuwe** tabel `stemwijzer_result_contexts` in plaats van breaking alters op `stemwijzer_results`.

## Query-paden (geïndexeerd)

- Verkiezing + gemeente context: `elections` + `election_municipalities` (unique/index)
- Stellingen volgorde binnen scope: `theses (election_municipality_id, sort_order)`
- Actieve/publiceerde versie: `thesis_versions (thesis_id, is_current)` + `status`
- Standpunten lookup: `party_positions (election_party_id, thesis_version_id)` (unique)
- Weging lookup: `thesis_weights (thesis_version_id)` (unique)
- Resultaten reproduceren: `stemwijzer_result_contexts` op `stemwijzer_result_id`, `thesis_version_id`, `election_municipality_id`
