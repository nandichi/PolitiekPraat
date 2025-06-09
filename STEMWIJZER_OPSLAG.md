# Stemwijzer Resultaten Opslag

## Overzicht

De stemwijzer op PolitiekPraat slaat gebruikersresultaten automatisch op in een database. Dit gebeurt anoniem en wordt gebruikt voor statistische doeleinden.

## Functionaliteit

### Wat wordt opgeslagen?

- **Session ID**: Unieke identifier voor elke gebruiker (geen persoonlijke data)
- **Antwoorden**: De antwoorden van de gebruiker op alle vragen (JSON format)
- **Resultaten**: De berekende overeenkomstpercentages per partij (JSON format)
- **Metadata**: IP-adres, user agent, tijdstip van voltooiing

### Privacy

- Geen persoonlijke identificeerbare informatie wordt opgeslagen
- Alle data is anoniem en kan niet worden herleid naar individuen
- IP-adressen worden alleen gebruikt voor technische doeleinden

## Database Schema

### Tabel: `stemwijzer_results`

```sql
CREATE TABLE stemwijzer_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL,
    user_id INT DEFAULT NULL,
    answers JSON NOT NULL,
    results JSON NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_user (user_id),
    INDEX idx_completed (completed_at)
);
```

## API Endpoints

### Resultaten Opslaan

```
POST /api/stemwijzer.php?action=save-results
Content-Type: application/json

{
    "sessionId": "session_abc123_1234567890",
    "answers": {
        "0": "eens",
        "1": "oneens",
        "2": "neutraal"
    },
    "results": {
        "PVV": {"score": 4, "total": 6, "agreement": 67},
        "VVD": {"score": 2, "total": 6, "agreement": 33}
    }
}
```

### Statistieken Ophalen

```
GET /api/stemwijzer.php?action=stats
```

### Test Functionaliteit

```
GET /api/stemwijzer.php?action=test-save
```

## Implementatie Details

### JavaScript (Frontend)

De `saveResultsToDatabase()` functie wordt automatisch aangeroepen wanneer:

1. Gebruiker alle vragen heeft beantwoord
2. Resultaten zijn berekend
3. Resultaten scherm wordt getoond

### PHP Backend (StemwijzerController)

- `saveResults()`: Slaat resultaten op in database
- `createResultsTable()`: Maakt tabel aan als deze niet bestaat
- `getStatistics()`: Haalt statistieken op

### Error Handling

- Database connectie problemen worden graceful afgehandeld
- Gebruiker kan altijd zijn resultaten zien, ook als opslaan faalt
- Uitgebreide logging voor debugging

## Admin Dashboard

Toegang tot statistieken via: `/admin/stemwijzer-stats.php`

**Login credentials:** (wijzig dit in productie!)

- Wachtwoord: `admin123`

### Functionaliteit:

- Totaal aantal inzendingen
- Inzendingen vandaag en deze week
- Recente submissions
- Systeem status
- Test functionaliteit

## Testen

### Manual Test

```bash
php test-stemwijzer-save.php
```

### Via Admin Dashboard

1. Ga naar `/admin/stemwijzer-stats.php`
2. Log in met admin wachtwoord
3. Klik op "Test Opslaan"

### Via API

```bash
curl -X GET "https://jouwdomain.com/api/stemwijzer.php?action=test-save"
```

## Monitoring

### Log Files

Alle activiteit wordt gelogd naar PHP error log:

- Succesvolle opslagen
- Database fouten
- API requests
- Schema detectie

### Statistieken

Het systeem houdt bij:

- Totaal aantal submissions
- Submissions per dag/week
- Laatste submission tijd
- Database status

## Troubleshooting

### Probleem: Tabel bestaat niet

**Oplossing**: De tabel wordt automatisch aangemaakt bij eerste gebruik

### Probleem: Database connectie faalt

**Controle**:

1. Database credentials in `includes/config.php`
2. Database server status
3. Permissions

### Probleem: JSON data niet geldig

**Controle**:

1. Browser console voor JavaScript fouten
2. Network tab voor API requests
3. Server error logs

### Probleem: Opslaan faalt maar gebruiker ziet resultaten

**Gedrag**: Dit is correct - gebruikerservaring heeft prioriteit
**Actie**: Controleer logs voor onderliggende oorzaak

## Security Overwegingen

1. **Admin Dashboard**: Wijzig standaard wachtwoord
2. **API Rate Limiting**: Overweeg implementatie voor productie
3. **IP Logging**: Voldoet aan privacy wetgeving
4. **Data Retention**: Implementeer beleid voor oude data

## Statistieken Voorbeelden

### Basis Stats

```json
{
  "total_submissions": 1234,
  "submissions_today": 45,
  "submissions_this_week": 312,
  "last_submission": "2025-01-28 14:30:00",
  "database_status": "connected",
  "schema_type": "new"
}
```

### Trend Data

- Dagelijkse submissions afgelopen week
- Piek tijden
- Populairste antwoorden (toekomstige feature)

## Toekomstige Verbeteringen

1. **Data Export**: CSV/Excel export voor analyse
2. **Advanced Analytics**: Antwoord patronen, correlaties
3. **Real-time Dashboard**: Live updates
4. **API Rate Limiting**: Spam protection
5. **Data Archiving**: Automatische cleanup oude data
