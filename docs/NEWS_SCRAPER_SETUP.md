# News Scraper Automatisering Systeem

## Overzicht

Het automatische news scraper systeem verzamelt politiek nieuws van verschillende bronnen en voegt deze toe aan de PolitiekPraat database. Het systeem ondersteunt zowel handmatige als automatische scraping met configureerbare intervallen.

## Functies

### 1. Automatische News Scraping

- **Configureerbare Intervallen**: 15 minuten tot 8 uur
- **Meerdere Nieuwsbronnen**: Progressieve en conservatieve bronnen
- **RSS Feed Monitoring**: Automatische controle van RSS feeds
- **Foutafhandeling**: Robuuste error handling en logging

### 2. Handmatige Beheer Tools

- **Directe Scraping**: Voer scraping handmatig uit
- **RSS Feed Testing**: Test alle bronnen op bereikbaarheid
- **Oude Artikelen Cleanup**: Automatisch opruimen van oude content
- **Real-time Logs**: Live monitoring van scraping activiteit

### 3. Monitoring & Analytics

- **Uitgebreide Statistieken**: Per bron en totale statistieken
- **Performance Tracking**: Memory en execution time monitoring
- **Error Reporting**: Gedetailleerde foutrapportage

## Installatie

### Automatische Setup (Aanbevolen)

```bash
# Voer het setup script uit
bash scripts/setup_news_cron.sh
```

Het setup script:

- Controleert PHP installatie
- Maakt benodigde directories aan
- Configureert cron job met gewenst interval
- Test het systeem
- Geeft gebruiksinstructies

### Handmatige Setup

1. **Maak directories aan:**

```bash
mkdir -p logs cache
chmod 755 logs cache
```

2. **Configureer cron job:**

```bash
# Bewerk crontab
crontab -e

# Voeg toe (bijvoorbeeld elke 30 minuten):
*/30 * * * * /usr/bin/php /path/to/your/project/scripts/auto_news_scraper.php >> /path/to/your/project/logs/auto_news_scraper.log 2>&1
```

3. **Test de installatie:**

```bash
php scripts/auto_news_scraper.php
```

## Configuratie

### Beheersinterface

Ga naar: `admin/news-scraper-beheer.php`

### Automatische Instellingen

- **Scraping Interval**: 15 minuten tot 8 uur
- **Automatische Cleanup**: Optioneel verwijderen van oude artikelen
- **Cleanup Periode**: 7 tot 60 dagen
- **RSS Feed Testing**: Validatie van alle bronnen

### Handmatige Configuratie

Bewerk: `cache/auto_scraper_settings.json`

```json
{
  "enabled": true,
  "interval_minutes": 30,
  "auto_cleanup": true,
  "cleanup_days": 30,
  "last_run": 1640995200,
  "last_cleanup": 1640995200
}
```

## Gebruik

### Via Beheersinterface

1. Ga naar Admin Dashboard
2. Klik op "News Scraper"
3. Configureer instellingen
4. Monitor activiteit via logs

### Via Command Line

```bash
# Handmatige scraping
php scripts/auto_news_scraper.php

# Bekijk logs
tail -f logs/auto_news_scraper.log

# Test RSS feeds
php scripts/test_rss_feeds.php
```

## Nieuwsbronnen

Het systeem scraped van verschillende politieke nieuwsbronnen:

### Progressieve Bronnen

- NOS Politiek
- NU.nl Politiek
- De Volkskrant
- Trouw

### Conservatieve Bronnen

- Telegraaf Politiek
- AD Politiek
- Nederlands Dagblad

### RSS Feed Management

Bronnen worden gedefinieerd in `includes/NewsScraper.php` met:

- RSS URL
- Politieke oriëntatie
- Scraping frequentie
- Error handling

## Monitoring

### Live Logs

- **Locatie**: `logs/auto_news_scraper.log`
- **Rotatie**: Handmatig of via logrotate
- **Format**: Timestamp + detailbericht

### Performance Metrics

- Memory usage
- Execution time
- Scraped articles count
- Error rates per source

### Foutafhandeling

- Network timeouts
- Invalid RSS feeds
- Database connection issues
- Duplicate article prevention

## Troubleshooting

### Cron Job Werkt Niet

```bash
# Controleer cron service
sudo systemctl status cron

# Bekijk cron logs
grep CRON /var/log/syslog

# Test handmatig
php scripts/auto_news_scraper.php
```

### RSS Feeds Falen

```bash
# Test specifieke feed
curl -I "https://feeds.nos.nl/nosnieuwspolitiek"

# Controleer PHP extensions
php -m | grep -E "curl|xml|dom"
```

### Database Problemen

```bash
# Test database connectie
php scripts/test_database.php

# Controleer nieuws tabellen
mysql -u user -p -e "DESCRIBE news_articles"
```

### Permission Issues

```bash
# Fix directory permissions
chmod 755 logs cache scripts
chmod +x scripts/*.sh

# Fix file permissions
chmod 644 cache/*.json logs/*.log
```

## Performance Optimizatie

### Interval Aanbevelingen

- **Hoge Frequentie**: 15-30 minuten (breaking news sites)
- **Normale Frequentie**: 1-2 uur (dagelijkse updates)
- **Lage Frequentie**: 4-8 uur (minder actieve sites)

### Resource Management

- Memory limit: minimaal 128MB
- Execution time: max 300 seconden
- Database connections: connection pooling
- HTTP timeouts: 30 seconden

### Caching Strategieën

- RSS feed caching (5-15 minuten)
- Duplicate detection (hash-based)
- Database query optimization
- Index optimization

## Backup & Recovery

### Data Backup

```bash
# Backup nieuws database
mysqldump -u user -p database_name news_articles > news_backup.sql

# Backup configuratie
tar -czf config_backup.tar.gz cache/ logs/
```

### Recovery Procedures

```bash
# Restore database
mysql -u user -p database_name < news_backup.sql

# Reset configuratie
rm cache/auto_scraper_settings.json
# Herconfigureer via beheersinterface
```

## Security

### Access Control

- Admin-only toegang tot beheersinterfaces
- Secure file permissions
- SQL injection preventie
- XSS protection

### Network Security

- HTTPS voor RSS feeds waar mogelijk
- User-Agent spoofing preventie
- Rate limiting
- IP whitelisting voor admin

## Uitbreiding

### Nieuwe Nieuwsbronnen Toevoegen

1. Edit `includes/NewsScraper.php`
2. Voeg RSS URL toe aan `$sources` array
3. Definieer politieke oriëntatie
4. Test de nieuwe bron

### Custom Intervals

```php
// In auto_news_scraper.php
$customIntervals = [
    'source1' => 15, // 15 minuten
    'source2' => 60, // 1 uur
    'default' => 30  // 30 minuten
];
```

### Webhook Integration

```php
// Webhook notificaties bij nieuwe artikelen
function sendWebhook($article) {
    $payload = json_encode([
        'title' => $article['title'],
        'source' => $article['source'],
        'url' => $article['url']
    ]);

    // Verstuur naar externe service
}
```

## Onderhoud

### Dagelijkse Taken

- Controleer error logs
- Monitor disk space
- Verificeer database groei

### Wekelijkse Taken

- Analyseer scraping statistieken
- Update RSS feed lijst
- Performance review

### Maandelijkse Taken

- Database optimalisatie
- Log rotatie
- Security updates

## API Reference

### Manual Scraping

```php
$scraper = new NewsScraper($newsModel);
$result = $scraper->scrapeAllSources();
```

### Cleanup

```php
$deletedCount = $scraper->cleanupOldArticles($days);
```

### Statistics

```php
$stats = $newsModel->getNewsStats();
$scrapingStats = $scraper->getScrapingStats();
```

## Changelog

### v2.0.0 - Automatisering Update

- Toegevoegd: Automatische cron job systeem
- Toegevoegd: Beheersinterface
- Toegevoegd: Uitgebreide logging
- Verbeterd: Error handling
- Verbeterd: Performance monitoring

### v1.0.0 - Initial Release

- Basis RSS scraping functionaliteit
- Handmatige scraping interface
- Database integratie
