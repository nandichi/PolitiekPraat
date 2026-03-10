# Migratie-notes - Slice #26 (datamodel/migratie-skelet)

## Doel
Niet-brekend migratie-skelet voor gemeentelijke stemwijzercontext met versiebeheer en weging.

## Toegevoegde bestanden

- `database/migrations/create_gemeentelijke_stemwijzer_tables.sql`
- `database/migrations/seed_gemeente_ede_2026_example.sql`
- `docs/gemeentelijke-stemwijzer/erd.md`

## Uitvoervolgorde

1. Draai `create_gemeentelijke_stemwijzer_tables.sql`
2. (Optioneel, test/stage) Draai `seed_gemeente_ede_2026_example.sql`

## Waarom dit backward compatible is

- Bestaande landelijke stemwijzer-tabellen en API-routes blijven ongemoeid.
- Geen destructieve wijzigingen (`DROP`, `TRUNCATE`, hernoemen) op bestaande tabellen.
- Resultaatversie-koppeling gebeurt via nieuwe tabel `stemwijzer_result_contexts`.

## Rollback-notes (veilig)

Alleen uitvoeren als de nieuwe gemeentelijke feature nog niet in productie gebruikt wordt.

Aanbevolen volgorde i.v.m. FK's:

```sql
DROP TABLE IF EXISTS stemwijzer_result_contexts;
DROP TABLE IF EXISTS thesis_weights;
DROP TABLE IF EXISTS party_positions;
DROP TABLE IF EXISTS thesis_versions;
DROP TABLE IF EXISTS theses;
DROP TABLE IF EXISTS election_parties;
DROP TABLE IF EXISTS election_municipalities;
DROP TABLE IF EXISTS elections;
DROP TABLE IF EXISTS municipalities;
```

## Risico's

- FK naar `stemwijzer_parties(id)` in `election_parties.legacy_party_id` vereist dat `stemwijzer_parties` bestaat.
- Seed is bewust minimaal en markeert data als `draft`; geen live-impact zonder expliciete activatie/publicatie.
