#!/bin/bash

# Setup News Scraper Cron Job
# Dit script stelt een cron job in die elke 30 minuten de news scraper uitvoert

# Haal het absolute pad naar het project op
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SCRAPER_SCRIPT="$PROJECT_DIR/scripts/run_news_scraper.php"
LOG_FILE="$PROJECT_DIR/logs/news_scraper.log"

# Maak logs directory aan als deze niet bestaat
mkdir -p "$PROJECT_DIR/logs"

# Voeg cron job toe die elke 30 minuten draait
# */30 betekent elke 30 minuten
CRON_JOB="*/30 * * * * /usr/bin/php $SCRAPER_SCRIPT >> $LOG_FILE 2>&1"

echo "=== News Scraper Cron Job Setup ==="
echo "Project directory: $PROJECT_DIR"
echo "Scraper script: $SCRAPER_SCRIPT"
echo "Log file: $LOG_FILE"
echo ""

# Check of het script bestaat
if [ ! -f "$SCRAPER_SCRIPT" ]; then
    echo "ERROR: Scraper script niet gevonden: $SCRAPER_SCRIPT"
    exit 1
fi

# Check of PHP beschikbaar is
if ! command -v php &> /dev/null; then
    echo "ERROR: PHP is niet geïnstalleerd of niet gevonden in PATH"
    exit 1
fi

# Test het scraper script eerst
echo "Testing scraper script..."
if php "$SCRAPER_SCRIPT" > /dev/null 2>&1; then
    echo "✓ Scraper script test succesvol"
else
    echo "✗ Scraper script test mislukt"
    echo "Voer handmatig uit om fouten te zien: php $SCRAPER_SCRIPT"
    exit 1
fi

# Voeg cron job toe
echo "Huidige cron jobs:"
crontab -l 2>/dev/null || echo "Geen bestaande cron jobs"

echo ""
echo "Toevoegen van nieuwe cron job..."

# Check of de cron job al bestaat
if crontab -l 2>/dev/null | grep -q "$SCRAPER_SCRIPT"; then
    echo "⚠️  Een vergelijkbare cron job bestaat al voor deze scraper"
    echo "Huidige cron jobs die verwijzen naar de scraper:"
    crontab -l 2>/dev/null | grep "$SCRAPER_SCRIPT"
    echo ""
    echo "Wil je doorgaan en mogelijk een duplicaat toevoegen? (y/n)"
    read -r response
    if [[ ! "$response" =~ ^[Yy]$ ]]; then
        echo "Geannuleerd."
        exit 0
    fi
fi

# Voeg cron job toe
(crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -

if [ $? -eq 0 ]; then
    echo "✓ Cron job succesvol toegevoegd!"
    echo ""
    echo "De news scraper zal nu elke 30 minuten automatisch draaien."
    echo "Logs worden geschreven naar: $LOG_FILE"
    echo ""
    echo "Huidige cron jobs:"
    crontab -l
    echo ""
    echo "Om de cron job te verwijderen, gebruik: crontab -e"
    echo "En verwijder de regel met: $SCRAPER_SCRIPT"
else
    echo "✗ Fout bij toevoegen van cron job"
    exit 1
fi

echo ""
echo "=== Setup Voltooid ==="
echo "Je kunt de logs volgen met: tail -f $LOG_FILE"
echo "Om de cron job handmatig te testen: php $SCRAPER_SCRIPT" 