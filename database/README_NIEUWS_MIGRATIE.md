# Nieuws Database Migratie

## Overzicht

De nieuwsartikelen zijn gemigreerd van hardcoded arrays naar een database-gebaseerde architectuur.

## Database Tabel: `news_articles`

### Schema

```sql
CREATE TABLE IF NOT EXISTS news_articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    description TEXT,
    url VARCHAR(1000) NOT NULL,
    source VARCHAR(100) NOT NULL,
    bias VARCHAR(50) NOT NULL DEFAULT 'Neutraal',
    orientation VARCHAR(20) NOT NULL DEFAULT 'neutraal',
    published_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_source (source),
    INDEX idx_bias (bias),
    INDEX idx_orientation (orientation),
    INDEX idx_published_at (published_at)
);
```

### Velden

- `id`: Unieke identifier (auto-increment)
- `title`: Titel van het nieuwsartikel (max 500 karakters)
- `description`: Korte beschrijving van het artikel
- `url`: Volledige URL naar het originele artikel (max 1000 karakters)
- `source`: Nieuwsbron (bijv. "De Volkskrant", "Telegraaf")
- `bias`: Politieke leaning ("Progressief", "Conservatief", "Neutraal")
- `orientation`: Vereenvoudigde oriëntatie ("links", "rechts", "neutraal")
- `published_at`: Datum en tijd van publicatie
- `created_at`: Datum/tijd wanneer record is toegevoegd
- `updated_at`: Datum/tijd van laatste wijziging

## Bestanden

### Migratie Bestanden

- `database/migrations/create_news_articles_table.sql` - SQL script voor tabel aanmaak
- `scripts/populate_news_database.php` - Script om initiële data in te laden

### Model en Controller

- `models/NewsModel.php` - Database model voor nieuws operaties
- `controllers/nieuws.php` - Controller aangepast om NewsModel te gebruiken

### Test en Hulp Bestanden

- `test_news_database.php` - Test script om database functionaliteit te controleren

## Gebruik

### Data Populeren

```bash
php scripts/populate_news_database.php
```

### NewsModel Gebruiken

```php
require_once 'models/NewsModel.php';
require_once 'includes/Database.php';

$db = new Database();
$newsModel = new NewsModel($db);

// Alle nieuws
$all_news = $newsModel->getAllNews();

// Gefilterd nieuws
$progressive_news = $newsModel->getFilteredNews('progressief');
$conservative_news = $newsModel->getFilteredNews('conservatief');

// Statistieken
$stats = $newsModel->getNewsStats();
```

### Filter Opties

- `alle` - Alle artikelen
- `progressief` - Alleen progressieve/linkse artikelen (orientation = 'links')
- `conservatief` - Alleen conservatieve/rechtse artikelen (orientation = 'rechts')

## Voordelen van Database Benadering

1. **Schaalbaarheid**: Eenvoudig nieuwe artikelen toevoegen zonder code wijzigingen
2. **Performance**: Database indexen zorgen voor snelle queries
3. **Flexibiliteit**: Eenvoudig filters en queries toevoegen
4. **Onderhoud**: Centraal beheer van nieuwsdata
5. **Uitbreidbaarheid**: Eenvoudig nieuwe velden toevoegen voor toekomstige features

## Database Onderhoud

### Nieuwe Artikelen Toevoegen

```php
$newsModel->addNewsArticle(
    $title,
    $description,
    $url,
    $source,
    $bias,
    $orientation,
    $publishedAt
);
```

### Oude Artikelen Opschonen

```sql
-- Verwijder artikelen ouder dan 30 dagen
DELETE FROM news_articles WHERE published_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

### Performance Monitoring

```sql
-- Check tabel grootte
SELECT COUNT(*) FROM news_articles;

-- Check index gebruik
EXPLAIN SELECT * FROM news_articles WHERE orientation = 'links' ORDER BY published_at DESC;
```

## Fallback Systeem

De controller heeft een fallback systeem dat hardcoded voorbeelddata toont als de database leeg is, zodat de site altijd functioneel blijft.

## Migratie Voltooid ✅

- [x] Database tabel aangemaakt
- [x] Initiële data gemigreerd
- [x] NewsModel klasse gemaakt
- [x] Controller aangepast
- [x] Test script gemaakt
- [x] Documentatie geschreven
