#!/bin/bash
#
# Installeert de cron jobs voor de Midterms 2026 sectie:
#   - Polymarket-odds: elk uur
#   - Brave-nieuws:    elke 3 uur
#
# Idempotent: bestaande midterms-cronregels worden vervangen.
# Niet-interactief; veilig om bij elke deploy opnieuw te draaien.

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

if ! command -v php >/dev/null 2>&1; then
    echo "[FOUT] PHP niet gevonden in PATH."
    exit 1
fi
PHP_PATH="$(command -v php)"

LOGS_DIR="$PROJECT_DIR/logs"
mkdir -p "$LOGS_DIR"

ODDS_SCRIPT="$PROJECT_DIR/scripts/midterms_fetch_polymarket.php"
NEWS_SCRIPT="$PROJECT_DIR/scripts/midterms_fetch_news.php"

ODDS_CRON="0 * * * * $PHP_PATH $ODDS_SCRIPT >> $LOGS_DIR/midterms_polymarket.log 2>&1"
NEWS_CRON="15 */3 * * * $PHP_PATH $NEWS_SCRIPT >> $LOGS_DIR/midterms_news.log 2>&1"

echo "Project:   $PROJECT_DIR"
echo "PHP:       $PHP_PATH"
echo "Odds cron: $ODDS_CRON"
echo "News cron: $NEWS_CRON"

# Verwijder bestaande midterms-cronregels en voeg de nieuwe toe.
CURRENT="$(crontab -l 2>/dev/null | grep -v 'midterms_fetch_polymarket.php' | grep -v 'midterms_fetch_news.php' || true)"
{
    printf '%s\n' "$CURRENT"
    printf '%s\n' "$ODDS_CRON"
    printf '%s\n' "$NEWS_CRON"
} | sed '/^$/d' | crontab -

echo "Cron jobs geinstalleerd. Huidige midterms-regels:"
crontab -l 2>/dev/null | grep 'midterms_fetch' || echo "(geen gevonden)"
