# Politiek Gesprek - Setup Instructies

## Overzicht
Politiek Gesprek is een geavanceerde AI-gestuurde stemwijzer chatbot die adaptieve gesprekken voert met gebruikers om hun beste politieke match te vinden.

## Installatie Stappen

### 1. Database Setup

Voer de database migratie uit om de benodigde tabellen aan te maken:

```bash
mysql -u [username] -p [database_name] < database/migrations/create_chat_stemwijzer_tables.sql
```

Of via phpMyAdmin:
1. Open phpMyAdmin
2. Selecteer je database
3. Ga naar het "SQL" tabblad
4. Kopieer en plak de inhoud van `database/migrations/create_chat_stemwijzer_tables.sql`
5. Klik op "Go" om uit te voeren

### 2. OpenAI API Key

Zorg dat je OpenAI API key correct is geconfigureerd. De applicatie gebruikt nu **gpt-4o** (upgraded van gpt-4o-mini).

De API key kan op meerdere manieren worden ingesteld:

**Optie 1: Environment variabele**
```bash
export OPENAI_API_KEY="sk-..."
```

**Optie 2: .env file**
Maak een `.env` bestand in de root:
```
OPENAI_API_KEY=sk-...
```

**Optie 3: Config file**
Maak `config/api_keys.php`:
```php
<?php
return [
    'openai_api_key' => 'sk-...'
];
```

### 3. Test de Setup

Bezoek: `https://jouw-domein.nl/politiek-gesprek`

## Features

### Conversatie Flow
- **Vragen 1-10**: Uit database (core topics: Immigratie, Klimaat, Economie, etc.)
- **Vragen 11-20**: AI-gegenereerd, adaptief gebaseerd op eerdere antwoorden
- **Antwoord Modes**: AI bepaalt per vraag of multiple choice of open-ended beter is

### AI Capabilities
- Genereert adaptieve follow-up vragen
- Analyseert open-ended antwoorden
- Bepaalt vraagmodus (multiple choice vs open)
- Creëert uitgebreide partij match analyses

### Rate Limiting
- Max 1 gesprek per uur per IP adres
- Voorkomt API misbruik

## Technische Details

### Nieuwe Bestanden
- `database/migrations/create_chat_stemwijzer_tables.sql` - Database schema
- `ajax/politiek-gesprek.php` - AJAX endpoint voor conversatie handling
- `controllers/politiek-gesprek.php` - Page controller
- `views/politiek-gesprek.php` - Frontend chat interface

### Gewijzigde Bestanden
- `includes/ChatGPTAPI.php` - Upgraded naar gpt-4o + nieuwe conversatie methodes
- `index.php` - Route toegevoegd voor /politiek-gesprek
- `views/templates/header.php` - Meta tags toegevoegd

### Database Tabellen
1. **politiek_gesprek_sessions** - Slaat conversatie sessies op
2. **politiek_gesprek_results** - Slaat finale resultaten en analyses op
3. **politiek_gesprek_rate_limit** - Rate limiting tracking

## API Kosten Overwegingen

**gpt-4o** is duurder dan gpt-4o-mini:
- **gpt-4o**: ~$2.50 per miljoen input tokens, ~$10 per miljoen output tokens
- **gpt-4o-mini**: ~$0.15 per miljoen input tokens, ~$0.60 per miljoen output tokens

Per conversatie (schatting):
- 20 vragen genereren/analyseren
- ~30,000 tokens totaal (input + output)
- Kosten per gesprek: ~$0.30 met gpt-4o vs ~$0.02 met gpt-4o-mini

Voor productie met veel verkeer, overweeg:
1. Caching van vaak gebruikte analyses
2. Rate limiting (al geïmplementeerd)
3. Optioneel downgraden naar gpt-4o-mini voor bepaalde functies

## Troubleshooting

### "API key niet gevonden"
- Check of OPENAI_API_KEY correct is ingesteld
- Herstart webserver na het toevoegen van environment variables

### "Sessie niet gevonden"
- Check of database tabellen correct zijn aangemaakt
- Verifieer database connectie in `includes/config.php`

### Rate limit errors
- Wacht 1 uur of pas `politiek_gesprek_rate_limit` tabel aan
- Voor development: verhoog rate limit in `ajax/politiek-gesprek.php`

### AI genereert geen vragen
- Verifieer API key
- Check error logs
- Test API connectie: bezoek `/test-openai-config.php` (indien beschikbaar)

## Toekomstige Verbeteringen

Mogelijke uitbreidingen:
1. **Streaming responses** - Real-time AI antwoorden (betere UX)
2. **Voice input** - Spraak-naar-tekst voor antwoorden
3. **Multilingual** - Ondersteuning voor Engels/andere talen
4. **Results sharing** - Deel je resultaten op social media
5. **Historical tracking** - Vergelijk resultaten over tijd voor ingelogde gebruikers
6. **Party webhooks** - Notificeer partijen van matches (opt-in)

## Support

Voor vragen of problemen:
- Check de console logs (browser + server)
- Review `ajax/politiek-gesprek.php` voor backend errors
- Test API verbinding apart

## Licentie

Onderdeel van PolitiekPraat platform.

