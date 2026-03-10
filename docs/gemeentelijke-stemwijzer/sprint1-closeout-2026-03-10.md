# Sprint 1 close-out (NOVA-URGENT) — 2026-03-10

## Scope
Afgerond onder NOVA-URGENT voor issues **#26, #27, #29**.

## PR/Issue status
- #26 ✅ gesloten via merge van PR #35
- #27 ✅ inhoud op `main` via PR #36 (issue administratief gesloten in deze run)
- #29 ✅ inhoud op `main` via PR #37 (issue administratief gesloten in deze run)

## Productie-deploy (gecontroleerd)
- Vooraf backup gemaakt op productiehost:
  - `~/deploy_backups/politiekpraat-code-20260310-201025-a183bb4.tar.gz`
- Deploy target commit op productie: `ef794b3`
- Health checks:
  - `GET /` => **200**
  - `GET /api/` => **200**
  - `GET /api/stemwijzer.php?action=meta` => **200**
- API-verificatie:
  - Response bevat `{"success":true}` + scope lock voor `ede / gemeenteraadsverkiezingen-2026`

## Rollback-plan (gevalideerd)
Bij check failure: `git reset --hard <pre_commit>` op productiehost.
Dit pad is tijdens deploy-run afgedwongen in scriptlogica (guarded release).

## DevTeam state update
- Sprint 1 status: **Done + deployed**
- Volgende werkstroom: **Vraagset-ontwikkeling Ede 2026 (25 conceptstellingen v0.1)**
- Owner: Nova (Tech Lead) + Research/QA
