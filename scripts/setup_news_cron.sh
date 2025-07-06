#!/bin/bash

# Setup script voor automatische news scraper cron job
# Dit script installeert en configureert de cron job voor nieuwsscraping

echo "🔧 PolitiekPraat News Scraper Cron Setup"
echo "========================================="

# Kleurcodes voor output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functie voor gekleurde output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${BLUE}$1${NC}"
}

# Haal huidige directory op
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

print_header "📍 Project Directory: $PROJECT_DIR"

# Controleer PHP installatie
print_status "Checking PHP installation..."
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed or not in PATH"
    exit 1
fi

PHP_PATH=$(which php)
print_status "PHP found at: $PHP_PATH"

# Controleer of de benodigde bestanden bestaan
SCRAPER_SCRIPT="$PROJECT_DIR/scripts/auto_news_scraper.php"
if [ ! -f "$SCRAPER_SCRIPT" ]; then
    print_error "Auto news scraper script not found at: $SCRAPER_SCRIPT"
    exit 1
fi

print_status "Auto scraper script found: $SCRAPER_SCRIPT"

# Maak logs directory aan als deze niet bestaat
LOGS_DIR="$PROJECT_DIR/logs"
if [ ! -d "$LOGS_DIR" ]; then
    print_status "Creating logs directory..."
    mkdir -p "$LOGS_DIR"
    chmod 755 "$LOGS_DIR"
fi

# Maak cache directory aan als deze niet bestaat
CACHE_DIR="$PROJECT_DIR/cache"
if [ ! -d "$CACHE_DIR" ]; then
    print_status "Creating cache directory..."
    mkdir -p "$CACHE_DIR"
    chmod 755 "$CACHE_DIR"
fi

# Vraag gebruiker om interval
print_header "⏰ Cron Job Interval Configuration"
echo "Kies het interval voor automatische news scraping:"
echo "1) Elke 15 minuten (intensief)"
echo "2) Elke 30 minuten (aanbevolen)"
echo "3) Elk uur"
echo "4) Elke 2 uur"
echo "5) Elke 4 uur"
echo "6) Elke 8 uur"
echo "7) Custom interval"

read -p "Voer je keuze in (1-7): " choice

case $choice in
    1)
        CRON_SCHEDULE="*/15 * * * *"
        INTERVAL_DESC="15 minuten"
        ;;
    2)
        CRON_SCHEDULE="*/30 * * * *"
        INTERVAL_DESC="30 minuten"
        ;;
    3)
        CRON_SCHEDULE="0 * * * *"
        INTERVAL_DESC="1 uur"
        ;;
    4)
        CRON_SCHEDULE="0 */2 * * *"
        INTERVAL_DESC="2 uur"
        ;;
    5)
        CRON_SCHEDULE="0 */4 * * *"
        INTERVAL_DESC="4 uur"
        ;;
    6)
        CRON_SCHEDULE="0 */8 * * *"
        INTERVAL_DESC="8 uur"
        ;;
    7)
        read -p "Voer je eigen cron schedule in (bijv. '*/30 * * * *'): " CRON_SCHEDULE
        INTERVAL_DESC="custom"
        ;;
    *)
        print_warning "Ongeldige keuze, gebruik standaard 30 minuten"
        CRON_SCHEDULE="*/30 * * * *"
        INTERVAL_DESC="30 minuten"
        ;;
esac

print_status "Interval ingesteld op: $INTERVAL_DESC ($CRON_SCHEDULE)"

# Maak de cron job regel
LOG_FILE="$LOGS_DIR/auto_news_scraper.log"
CRON_JOB="$CRON_SCHEDULE $PHP_PATH $SCRAPER_SCRIPT >> $LOG_FILE 2>&1"

print_header "🔧 Cron Job Installation"
print_status "Cron job regel: $CRON_JOB"

# Controleer of de cron job al bestaat
if crontab -l 2>/dev/null | grep -q "auto_news_scraper.php"; then
    print_warning "Er bestaat al een cron job voor auto_news_scraper.php"
    read -p "Wil je deze vervangen? (y/n): " replace
    if [ "$replace" = "y" ] || [ "$replace" = "Y" ]; then
        # Verwijder bestaande cron job
        crontab -l 2>/dev/null | grep -v "auto_news_scraper.php" | crontab -
        print_status "Bestaande cron job verwijderd"
    else
        print_status "Installatie geannuleerd"
        exit 0
    fi
fi

# Voeg nieuwe cron job toe
(crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -

if [ $? -eq 0 ]; then
    print_status "✅ Cron job succesvol toegevoegd!"
else
    print_error "❌ Fout bij toevoegen van cron job"
    exit 1
fi

# Test het script handmatig
print_header "🧪 Testing Script"
print_status "Testing auto news scraper script..."

# Voer het script uit voor test
cd "$PROJECT_DIR"
TEST_OUTPUT=$($PHP_PATH "$SCRAPER_SCRIPT" 2>&1)
TEST_EXIT_CODE=$?

if [ $TEST_EXIT_CODE -eq 0 ]; then
    print_status "✅ Test succesvol voltooid"
    echo "Test output (laatste 5 regels):"
    echo "$TEST_OUTPUT" | tail -5
else
    print_warning "⚠️ Test gaf een waarschuwing of fout"
    echo "Test output:"
    echo "$TEST_OUTPUT"
fi

# Toon huidige crontab
print_header "📋 Current Crontab"
print_status "Huidige cron jobs:"
crontab -l 2>/dev/null | grep -v "^#" | grep -v "^$" || echo "Geen actieve cron jobs"

# Geef gebruiksinstructies
print_header "📖 Usage Instructions"
echo "Cron job is geïnstalleerd en draait elke $INTERVAL_DESC"
echo
echo "📁 Belangrijke bestanden:"
echo "  Script:     $SCRAPER_SCRIPT"
echo "  Logs:       $LOG_FILE"
echo "  Settings:   $CACHE_DIR/auto_scraper_settings.json"
echo
echo "🔧 Beheer:"
echo "  Beheersinterface: admin/news-scraper-beheer.php"
echo "  Dashboard:        admin/scraper_dashboard.php"
echo
echo "📋 Handmatige commando's:"
echo "  Test script:      $PHP_PATH $SCRAPER_SCRIPT"
echo "  Bekijk logs:      tail -f $LOG_FILE"
echo "  Edit crontab:     crontab -e"
echo "  List crontab:     crontab -l"
echo
echo "🚫 Cron job verwijderen:"
echo "  crontab -l | grep -v 'auto_news_scraper.php' | crontab -"
echo

print_status "✅ Setup voltooid!"
print_warning "Vergeet niet om de automatische scraping in te schakelen via de beheersinterface"

exit 0 