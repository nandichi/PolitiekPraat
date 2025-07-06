# Likes Beheer Systeem

## Overzicht

Het likes beheer systeem stelt administrators in staat om handmatig likes toe te voegen aan blogs en om automatisch likes toe te voegen op onregelmatige momenten. Dit zorgt voor natuurlijke activiteit op de website.

## Functies

### 1. Handmatige Likes Beheer

- **Willekeurige Likes Toevoegen**: Voeg een willekeurig aantal likes toe aan alle of een selectie van blogs
- **Bulk Likes Editor**: Selecteer meerdere blogs en werk ze tegelijk bij
- **Individuele Blog Likes**: Stel het exacte aantal likes in voor specifieke blogs met directe AJAX update
- **Snelle Aanpassingen**: Plus/minus knoppen voor elke blog
- **Keyboard Shortcuts**: Ctrl+A (select all), Ctrl+Enter (bulk update)
- **Statistieken**: Bekijk totaal aantal blogs en likes

### 2. Automatische Likes Systeem

- **Configureerbare Instellingen**: Stel minimum en maximum likes in
- **Flexibele Intervallen**: Kies tussen 1, 2, 4, 6, 12 of 24 uur
- **Intelligente Selectie**: Alleen blogs van de laatste 30 dagen krijgen automatisch likes
- **Natuurlijke Variatie**: 70% kans dat een blog likes krijgt per run

## Toegang

Het likes beheer systeem is toegankelijk via:

- **Dashboard**: Ga naar Admin > Stemwijzer Dashboard
- **Snelle Actie**: Klik op "Likes Beheer" in de snelle acties sectie
- **Directe URL**: `/admin/likes-beheer.php`

## Bulk Likes Editor Gebruik

### Bulk Bewerkingen

1. **Selecteer blogs**: Klik op checkboxes naast blogs die je wilt bewerken
2. **Alles selecteren**: Klik "Alles Selecteren" of gebruik Ctrl+A
3. **Bulk waarde instellen**: Voer een waarde in bij "Stel alle geselecteerde in op"
4. **Bulk update**: Klik "Bulk Update" of gebruik Ctrl+Enter

### Individuele Blog Bewerkingen

1. **Directe aanpassing**: Gebruik de +/- knoppen naast elke blog
2. **Handmatige invoer**: Typ een specifiek aantal likes
3. **Directe update**: Klik "Update" voor onmiddellijke AJAX update
4. **Visuele feedback**: Kleurveranderingen geven succes/fout aan

### Sneltoetsen

- **Ctrl/Cmd + A**: Selecteer alle blogs
- **Ctrl/Cmd + Enter**: Voer bulk update uit
- **Tab**: Navigeer tussen invoervelden
- **Enter**: Update individuele blog (in invoerveld)

## Automatische Functionaliteit Instellen

### Stap 1: Configuratie

1. Ga naar de Likes Beheer pagina
2. Schakel "Automatische likes inschakelen" in
3. Stel de gewenste waarden in:
   - **Min Likes**: Minimum aantal likes per blog (1-10)
   - **Max Likes**: Maximum aantal likes per blog (1-20)
   - **Interval**: Hoe vaak het script moet draaien
4. Klik op "Instellingen Opslaan"

### Stap 2: Cron Job Instellen

Voor automatische uitvoering moet je een cron job instellen:

```bash
# Elke 6 uur (aanbevolen)
0 */6 * * * /usr/bin/php /path/to/your/project/scripts/auto_likes_cron.php

# Elke 4 uur
0 */4 * * * /usr/bin/php /path/to/your/project/scripts/auto_likes_cron.php

# Elke 12 uur
0 */12 * * * /usr/bin/php /path/to/your/project/scripts/auto_likes_cron.php

# Dagelijks om 2:00 's nachts
0 2 * * * /usr/bin/php /path/to/your/project/scripts/auto_likes_cron.php
```

### Stap 3: Monitoring

De automatische functionaliteit logt alle activiteiten naar `/logs/auto_likes.log`. Controleer dit bestand voor:

- Succesvolle runs
- Foutmeldingen
- Aantal toegevoegde likes

## Technische Details

### Bestanden

- **`admin/likes-beheer.php`**: Hoofdinterface voor likes beheer
- **`scripts/auto_likes_cron.php`**: Cron job script voor automatische likes
- **`cache/auto_likes_settings.json`**: Configuratiebestand voor automatische instellingen
- **`logs/auto_likes.log`**: Logbestand voor automatische functionaliteit

### Database

Het systeem gebruikt de bestaande `blogs` tabel met de `likes` kolom.

### Veiligheid

- Alleen administrators kunnen het systeem gebruiken
- Likes kunnen niet negatief worden
- Automatische functionaliteit werkt alleen op blogs van de laatste 30 dagen

## Problemen Oplossen

### Automatische Likes Werken Niet

1. **Controleer cron job**: Zorg dat de cron job correct is ingesteld
2. **Bekijk logs**: Controleer `/logs/auto_likes.log` voor foutmeldingen
3. **Verificeer instellingen**: Zorg dat automatische likes is ingeschakeld
4. **Controleer bestandsrechten**: Zorg dat PHP kan schrijven naar `/cache/` en `/logs/`

### Foutmeldingen

- **"Settings file not found"**: Sla eerst de instellingen op via de interface
- **"Auto likes is disabled"**: Schakel automatische likes in via de interface
- **"Not time yet for next run"**: Wacht tot het ingestelde interval is verstreken

## Aanbevelingen

### Natuurlijke Activiteit

- Gebruik een interval van 6-12 uur voor natuurlijke activiteit
- Stel min/max likes in op 1-5 voor realistische groei
- Varieer handmatige likes toevoegingen

### Monitoring

- Controleer wekelijks de logs voor problemen
- Bekijk maandelijks de statistieken voor groei
- Pas instellingen aan op basis van website activiteit

## Onderhoud

### Cache Opruimen

Het systeem slaat instellingen op in cache bestanden. Deze kunnen veilig worden verwijderd als er problemen zijn - de instellingen moeten dan opnieuw worden geconfigureerd.

### Logs Rotatie

Overweeg om de logbestanden periodiek te archiveren of te verwijderen om schijfruimte vrij te maken.

## Uitbreiding

Het systeem kan worden uitgebreid met:

- Database opslag voor instellingen (in plaats van JSON bestanden)
- Meer geavanceerde algoritmes voor natuurlijke likes distributie
- Integratie met gebruikersactiviteit
- Rapportage en analytics dashboard
