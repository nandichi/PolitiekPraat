# News Scraper Systeem

## Overzicht

Het News Scraper systeem haalt automatisch de laatste politieke nieuwsartikelen op van verschillende Nederlandse nieuwsbronnen en slaat deze op in de database. Het systeem voorkomt duplicate scraping en draait elke 30 minuten automatisch.

## Architectuur

### Componenten

1. **NewsScraper** (`includes/NewsScraper.php`) - Hoofdklasse voor scraping
2. **NewsModel** (`models/NewsModel.php`) - Database operaties
3. **NewsAPI** (`includes/NewsAPI.php`) - RSS feed parsing
4. **run_news_scraper.php** (`scripts/run_news_scraper.php`) - CLI script
5. **Admin Dashboard** (`admin/scraper_dashboard.php`) - Web interface

### Database Tabel

- `news_articles` - Opslag van gescrapte artikelen
- `cache/last_scraped.json` - Tracking van laatste gescrapte artikelen

## Nieuwsbronnen

### Progressieve Bronnen (links)

- **De Volkskrant**: `https://www.volkskrant.nl/nieuws-achtergrond/politiek/rss.xml`
- **NRC**: `https://www.nrc.nl/sectie/politiek/rss/`
- **Trouw**: `https://www.trouw.nl/politiek/rss.xml`

### Conservatieve Bronnen (rechts)

- **Telegraaf**: `https://www.telegraaf.nl/nieuws/politiek/rss`
- **AD**: `https://www.ad.nl/politiek/rss.xml`
- **NU.nl**: `https://www.nu.nl/rss/Politiek`

## Features

### 🔄 Slimme Duplicate Detectie

- Controleert laatste gescrapte artikel per bron
- Stopt scraping zodra een bekend artikel wordt gevonden
- Controleert database op bestaande URLs

### 📊 Automatische Categorisatie

- Bepaalt automatisch politieke oriëntatie per bron
- Classificeert bias als 'Progressief' of 'Conservatief'
- Filtert alleen politiek relevante content

### 🧹 Automatische Cleanup

- Verwijdert automatisch artikelen ouder dan 30 dagen
- Draait dagelijks om 6:00 AM via cron job

### 📈 Monitoring & Statistieken

- Real-time database statistieken
- Scraping status per bron
- Error logging en rapportage

## Installatie & Setup

### Stap 1: Database Migratie

```bash
# Voer de nieuws database migratie uit
php scripts/populate_news_database.php
```

### Stap 2: Handmatige Test

```bash
# Test de scraper handmatig
php scripts/run_news_scraper.php
```

### Stap 3: Automatische Setup (Cron Job)

```bash
# Automatische cron job setup
bash scripts/setup_cron.sh
```

**Of handmatig cron job toevoegen:**

```bash
# Open crontab
crontab -e

# Voeg deze regel toe (vervang [PROJECT_PATH])
*/30 * * * * /usr/bin/php [PROJECT_PATH]/scripts/run_news_scraper.php >> [PROJECT_PATH]/logs/news_scraper.log 2>&1
```

### Stap 4: Admin Dashboard

Ga naar: `http://yoursite.com/admin/scraper_dashboard.php`

- Bekijk statistieken
- Run scraper handmatig
- Monitor scraping status

## Gebruik

### CLI Script

```bash
# Basis uitvoering
php scripts/run_news_scraper.php

# Met logging
php scripts/run_news_scraper.php >> logs/news_scraper.log 2>&1
```

### Admin Dashboard

1. Open `admin/scraper_dashboard.php`
2. Bekijk database statistieken
3. Run scraper met "🚀 Scraper Nu Uitvoeren" knop
4. Monitor status per nieuwsbron

### Programmatisch Gebruik

```php
require_once 'includes/NewsScraper.php';
require_once 'models/NewsModel.php';

$db = new Database();
$newsModel = new NewsModel($db);
$scraper = new NewsScraper($newsModel);

// Scrape alle bronnen
$result = $scraper->scrapeAllSources();
echo "Nieuwe artikelen: " . $result['scraped_count'];

// Cleanup oude artikelen
$deleted = $scraper->cleanupOldArticles(30);
echo "Verwijderde artikelen: " . $deleted;

// Statistieken
$stats = $scraper->getScrapingStats();
foreach ($stats as $source => $sourceStats) {
    echo "$source: {$sourceStats['last_scraped']}\n";
}
```

## Configuratie

### Nieuwe Nieuwsbron Toevoegen

Bewerk `includes/NewsScraper.php`:

```php
private $newsSources = [
    'Nieuwe Bron' => [
        'rss_url' => 'https://example.com/rss',
        'orientation' => 'links', // of 'rechts'
        'bias' => 'Progressief' // of 'Conservatief'
    ]
];
```

### Scraping Interval Wijzigen

Bewerk cron job:

```bash
# Elke 15 minuten
*/15 * * * * /usr/bin/php [PROJECT_PATH]/scripts/run_news_scraper.php

# Elk uur
0 * * * * /usr/bin/php [PROJECT_PATH]/scripts/run_news_scraper.php

# Elke 2 uur
0 */2 * * * /usr/bin/php [PROJECT_PATH]/scripts/run_news_scraper.php
```

### Cleanup Interval Aanpassen

Bewerk `scripts/run_news_scraper.php`:

```php
// Verander cleanup tijd (standaard 06:00)
if ($currentHour == '06') {
    $deletedCount = $scraper->cleanupOldArticles(30); // 30 dagen
}
```

## Monitoring

### Log Files

```bash
# Volg real-time logs
tail -f logs/news_scraper.log

# Bekijk laatste 100 regels
tail -100 logs/news_scraper.log

# Zoek naar fouten
grep -i error logs/news_scraper.log
```

### Cache Bestanden

- `cache/last_scraped.json` - Laatste scrape status per bron
- `cache/news.json` - NewsAPI cache (legacy)

### Database Queries

```sql
-- Controleer recente artikelen
SELECT source, COUNT(*) as count, MAX(published_at) as laatste
FROM news_articles
WHERE published_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
GROUP BY source;

-- Bekijk scraping performance
SELECT DATE(created_at) as datum, COUNT(*) as artikelen
FROM news_articles
GROUP BY DATE(created_at)
ORDER BY datum DESC;
```

## Troubleshooting

### Veel Voorkomende Problemen

**1. RSS Feed 404 Errors**

- Controleer of RSS URLs nog geldig zijn
- Nieuwssites wijzigen soms hun RSS endpoints
- Update URLs in `NewsScraper.php`

**2. Geen Nieuwe Artikelen**

- Check `cache/last_scraped.json` voor laatste status
- RSS feeds hebben mogelijk geen nieuwe content
- Verwijder cache file om force refresh te doen

**3. Database Connectie Fouten**

- Controleer database credentials in `includes/config.php`
- Zorg dat `news_articles` tabel bestaat
- Run database migratie opnieuw

**4. Cron Job Draait Niet**

- Controleer cron service: `sudo service cron status`
- Verificeer cron job: `crontab -l`
- Check PHP pad: `which php`

### Debug Mode

Voeg debug output toe aan `NewsScraper.php`:

```php
// In scrapeSource methode
echo "DEBUG: Scraping $sourceName\n";
echo "DEBUG: Found " . count($articles) . " articles\n";
print_r($articles); // Toon alle artikelen
```

### Performance Optimalisatie

- Verhoog `max_items` voor meer artikelen per scrape
- Verlaag scraping frequentie voor minder server load
- Voeg database indexen toe voor snellere queries

## Security Overwegingen

### Productie Setup

1. **Admin Dashboard Beveiliging**

   - Implementeer echte authenticatie
   - Beperk toegang tot admin IP's
   - Gebruik HTTPS

2. **Script Beveiliging**

   - Run cron jobs onder beperkte user
   - Valideer alle input parameters
   - Log alle admin acties

3. **Database Beveiliging**
   - Gebruik prepared statements (al geïmplementeerd)
   - Beperk database user rechten
   - Backup database regelmatig

## Uitbreidingsmogelijkheden

### Geplande Features

- [ ] Email notificaties bij scraping fouten
- [ ] API endpoint voor externe integraties
- [ ] Machine learning voor betere content classificatie
- [ ] Image scraping voor artikelen
- [ ] Social media sentiment analyse

### Custom Integraties

Het systeem is ontworpen voor uitbreiding:

- Voeg nieuwe RSS bronnen toe
- Integreer met andere CMS systemen
- Export naar externe databases
- Custom filtering op basis van keywords

## Support

Voor vragen of problemen:

1. Check de logs: `logs/news_scraper.log`
2. Bekijk admin dashboard voor status
3. Test handmatig: `php scripts/run_news_scraper.php`
4. Controleer database verbinding en tabellen
