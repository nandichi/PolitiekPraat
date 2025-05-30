# Stemwijzer Scripts Uitvoeren op Live Server

## Methode 1: Via Web Interface (Aanbevolen)

1. Upload het `web_import.php` bestand naar de root van je website
2. Upload de hele `scripts/` folder met alle scripts
3. Ga naar: `https://jouwwebsite.nl/web_import.php?code=stemwijzer2024`
4. Volg de stappen in de web interface

**Beveiligingsadvies**: Verander de beveiligingscode in `web_import.php` naar iets unieks!

### Voor nieuwe installatie:

1. **Database Tabellen Aanmaken** (stap 0 - alleen eerste keer)
2. Database Status Controleren (stap 1)
3. Nieuwe Data Importeren (stap 3)
4. Nogmaals controleren

### Voor updates van bestaande installatie:

1. Database Status Controleren (stap 1)
2. Database Opschonen indien nodig (stap 2)
3. Nieuwe Data Importeren (stap 3)
4. Nogmaals controleren

## Methode 2: Via SSH (Als je server toegang hebt)

```bash
# Log in via SSH
ssh jouwgebruiker@jouwserver.nl

# Ga naar de website directory
cd /pad/naar/jouw/website

# Voor nieuwe installatie - maak eerst tabellen aan
php scripts/create_stemwijzer_tables.php

# Controleer database status
php scripts/check_database.php

# Schoon op indien nodig
php scripts/cleanup_duplicates.php  # (optioneel)

# Importeer data
php scripts/run_stemwijzer_migration.php

# Controleer opnieuw
php scripts/check_database.php
```

## Methode 3: Via Hosting Panel (cPanel, Plesk, etc.)

1. Open File Manager in je hosting panel
2. Upload de scripts naar de juiste directory
3. Gebruik Terminal (als beschikbaar) om de scripts uit te voeren
4. Of gebruik de web interface methode

## Methode 4: Via FTP + Web Interface

1. Upload alle bestanden via FTP:

   - `web_import.php` → root directory
   - `scripts/` folder → root directory (inclusief het nieuwe `create_stemwijzer_tables.php`)
   - Zorg dat `includes/` folder ook aanwezig is

2. Ga naar de web interface en voer uit

## Scripts in de juiste volgorde:

### Voor nieuwe installatie:

1. `create_stemwijzer_tables.php` - Maakt de database tabellen aan
2. `check_database.php` - Controleert of tabellen correct zijn aangemaakt
3. `run_stemwijzer_migration.php` - Importeert alle partijen en vragen
4. `check_database.php` - Verifieert dat alles correct is geïmporteerd

### Voor updates:

1. `check_database.php` - Bekijk huidige status
2. `cleanup_duplicates.php` - Verwijder oude data (optioneel)
3. `run_stemwijzer_migration.php` - Importeer nieuwe data
4. `check_database.php` - Controleer resultaat

## Na het uitvoeren:

⚠️ **BELANGRIJK**: Verwijder `web_import.php` van je server na gebruik voor veiligheid!

## Troubleshooting:

- **"Table doesn't exist" error**: Voer eerst `create_stemwijzer_tables.php` uit
- **Database connectie problemen**: Controleer `includes/config.php` instellingen
- **Permission errors**: Zorg dat PHP schrijfrechten heeft
- **Script niet gevonden**: Controleer of de `scripts/` folder correct is geupload
- **Beveiligingsfout**: Controleer of je de juiste beveiligingscode gebruikt
- **Duplicaten**: Gebruik de cleanup functie om oude data te verwijderen

## Verificatie:

Na het uitvoeren kun je controleren of alles werkt door naar je stemwijzer te gaan en te testen of de vragen en partijen correct worden getoond.

## Database Tabellen:

Het script maakt de volgende tabellen aan:

- `stemwijzer_parties` - Politieke partijen
- `stemwijzer_questions` - Stemwijzer vragen
- `stemwijzer_positions` - Partij standpunten per vraag
- `stemwijzer_results` - Gebruikersresultaten (voor toekomstig gebruik)
