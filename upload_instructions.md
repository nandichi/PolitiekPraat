# Stemwijzer Scripts Uitvoeren op Live Server

## Methode 1: Via Web Interface (Aanbevolen)

1. Upload het `web_import.php` bestand naar de root van je website
2. Upload de `scripts/` folder met alle scripts
3. Ga naar: `https://jouwwebsite.nl/web_import.php?code=stemwijzer2024`
4. Volg de stappen in de web interface

**Beveiligingsadvies**: Verander de beveiligingscode in `web_import.php` naar iets unieks!

## Methode 2: Via SSH (Als je server toegang hebt)

```bash
# Log in via SSH
ssh jouwgebruiker@jouwserver.nl

# Ga naar de website directory
cd /pad/naar/jouw/website

# Voer de scripts uit
php scripts/check_database.php
php scripts/cleanup_duplicates.php  # (optioneel)
php scripts/run_stemwijzer_migration.php
```

## Methode 3: Via Hosting Panel (cPanel, Plesk, etc.)

1. Open File Manager in je hosting panel
2. Upload de scripts naar de juiste directory
3. Gebruik Terminal (als beschikbaar) om de scripts uit te voeren
4. Of gebruik de web interface methode

## Methode 4: Via FTP + Web Interface

1. Upload alle bestanden via FTP:

   - `web_import.php` → root directory
   - `scripts/` folder → root directory
   - Zorg dat `includes/` folder ook aanwezig is

2. Ga naar de web interface en voer uit

## Na het uitvoeren:

⚠️ **BELANGRIJK**: Verwijder `web_import.php` van je server na gebruik voor veiligheid!

## Troubleshooting:

- **Database connectie problemen**: Controleer `includes/config.php` instellingen
- **Permission errors**: Zorg dat PHP schrijfrechten heeft
- **Script niet gevonden**: Controleer of de `scripts/` folder correct is geupload
- **Beveiligingsfout**: Controleer of je de juiste beveiligingscode gebruikt

## Verificatie:

Na het uitvoeren kun je controleren of alles werkt door naar je stemwijzer te gaan en te testen of de vragen en partijen correct worden getoond.
