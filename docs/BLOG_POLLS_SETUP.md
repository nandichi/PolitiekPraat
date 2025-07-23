# Blog Polls Functionaliteit

Deze documentatie beschrijft de nieuwe poll functionaliteit voor blogs op de PolitiekPraat website.

## 📋 Overzicht

De blog polls functionaliteit stelt auteurs in staat om interactieve polls toe te voegen aan hun blogs. Lezers kunnen stemmen en resultaten bekijken, wat de engagement en interactiviteit van de content verhoogt.

## 🚀 Installatie

### Stap 1: Database Migratie Uitvoeren

Voer het migratie script uit om de benodigde database tabellen aan te maken:

```bash
cd /path/to/your/project
php scripts/run_poll_migration.php
```

Dit script maakt de volgende tabellen aan:

- `blog_polls` - Bevat poll informatie per blog
- `poll_options` - Bevat de antwoordopties per poll
- `poll_votes` - Bevat alle stemmen van gebruikers
- Voegt `has_poll` kolom toe aan `blogs` tabel

### Stap 2: Verificatie

Na het uitvoeren van de migratie kun je verifiëren dat alles correct is geïnstalleerd door te controleren of de tabellen bestaan in je database.

## 🎯 Functionaliteiten

### Voor Blog Auteurs

**Poll Aanmaken:**

- Ga naar de blog aanmaak pagina (`/blogs/create`)
- Schakel de "Poll inschakelen" toggle in
- Vul de poll vraag in
- Voeg minimaal 2 poll opties toe (maximaal 10)
- Kies het stem type:
  - **Eén keuze**: Gebruikers kunnen maar één optie selecteren
  - **Meerdere keuzes**: Gebruikers kunnen meerdere opties selecteren (toekomstige feature)
- Stel in wanneer resultaten getoond worden:
  - **Na stemmen**: Resultaten zijn alleen zichtbaar nadat gebruiker heeft gestemd
  - **Altijd zichtbaar**: Resultaten zijn altijd zichtbaar
  - **Nooit zichtbaar**: Alleen admin kan resultaten zien (toekomstige feature)

**Poll Opties Beheren:**

- Gebruik de "+" knop om extra opties toe te voegen
- Gebruik de prullenbak knop om opties te verwijderen (minimum 2 opties vereist)
- Opties worden automatisch genummerd

### Voor Lezers

**Stemmen:**

- Polls verschijnen automatisch onder de blog content
- Selecteer een optie en klik "Stem Uitbrengen"
- Na stemmen worden de resultaten getoond (afhankelijk van poll instellingen)
- Eén stem per gebruiker/sessie per poll

**Resultaten Bekijken:**

- Visuele voortgangsbalken tonen percentages
- Totaal aantal stemmen wordt weergegeven
- Jouw eigen stem wordt gemarkeerd met "Jouw keuze"

## 🗄️ Database Schema

### blog_polls

```sql
- id (PRIMARY KEY)
- blog_id (FOREIGN KEY → blogs.id)
- question (VARCHAR 500) - Poll vraag
- description (TEXT) - Optionele beschrijving
- poll_type (ENUM: 'single', 'multiple') - Type stemming
- is_active (BOOLEAN) - Of poll actief is
- show_results (ENUM: 'after_vote', 'always', 'never') - Wanneer resultaten tonen
- created_at, updated_at
```

### poll_options

```sql
- id (PRIMARY KEY)
- poll_id (FOREIGN KEY → blog_polls.id)
- option_text (VARCHAR 255) - Tekst van de optie
- option_order (INT) - Volgorde van de optie
- created_at
```

### poll_votes

```sql
- id (PRIMARY KEY)
- poll_id (FOREIGN KEY → blog_polls.id)
- option_id (FOREIGN KEY → poll_options.id)
- user_id (FOREIGN KEY → users.id, NULL voor anonieme gebruikers)
- session_id (VARCHAR 255) - Voor anonieme gebruikers
- ip_address, user_agent - Voor spam preventie
- voted_at
```

## 🔧 API Endpoints

### POST /ajax/poll-vote.php

Gebruikt voor het uitbrengen van stemmen.

**Request Body:**

```json
{
  "poll_id": 123,
  "option_id": 456
}
```

**Response (Success):**

```json
{
  "success": true,
  "message": "Stem succesvol opgeslagen",
  "results": [
    {
      "id": 456,
      "text": "Optie A",
      "votes": 15,
      "percentage": 60.0
    }
  ],
  "total_votes": 25,
  "user_voted": true,
  "voted_option_id": 456
}
```

**Response (Error):**

```json
{
  "success": false,
  "error": "Je hebt al gestemd op deze poll"
}
```

## 🎨 Styling & UI

De poll functionaliteit gebruikt het bestaande design system van PolitiekPraat:

- **Kleuren**: Primary en secondary kleuren uit het thema
- **Responsive**: Volledig responsive design voor desktop, tablet en mobile
- **Animaties**: Smooth transitions en loading states
- **Toegankelijkheid**: Proper ARIA labels en keyboard navigation

## 🔐 Beveiliging

**Spam Preventie:**

- Eén stem per gebruiker per poll (voor ingelogde gebruikers)
- Eén stem per sessie per poll (voor anonieme gebruikers)
- IP adres en user agent logging voor analyse
- Input sanitization en SQL injection preventie

**Data Validatie:**

- Server-side validatie van alle poll data
- Client-side validatie voor betere UX
- Sanitization van user input

## 🎯 Toekomstige Uitbreidingen

**Geplande Features:**

- [ ] Multiple choice polls (meerdere opties selecteren)
- [ ] Poll statistieken dashboard voor admins
- [ ] Export functionaliteit voor poll resultaten
- [ ] Time-limited polls (deadline instellen)
- [ ] Poll templates voor hergebruik
- [ ] Advanced analytics (demographics, trends)

**Mogelijke Verbeteringen:**

- [ ] Real-time updates via WebSockets
- [ ] Poll categories/tags
- [ ] Social sharing van poll resultaten
- [ ] Comment integratie met polls
- [ ] Notification system voor nieuwe polls

## 🐛 Troubleshooting

### Veelvoorkomende Problemen

**Poll verschijnt niet:**

- Controleer of de poll is aangemaakt in de database
- Verificeer dat `is_active = 1` in `blog_polls` tabel
- Check browser console voor JavaScript errors

**Stemmen werkt niet:**

- Controleer of `/ajax/poll-vote.php` bereikbaar is
- Verificeer database connectie
- Check browser console voor network errors

**Resultaten tonen niet:**

- Controleer `show_results` setting van de poll
- Verificeer of gebruiker heeft gestemd (als `show_results = 'after_vote'`)

### Debug Informatie

Bij problemen, controleer:

1. Browser developer tools (Console, Network tabs)
2. PHP error logs
3. Database queries in development mode
4. AJAX responses voor error messages

## 👥 Support

Voor vragen of problemen:

- Check deze documentatie eerst
- Bekijk de code comments in de betreffende bestanden
- Test de functionaliteit in development mode eerst

## 📄 Bestanden Overzicht

**Database:**

- `database/migrations/add_blog_polls.sql` - Database migratie
- `scripts/run_poll_migration.php` - Migratie uitvoer script

**Backend:**

- `controllers/blogs/create.php` - Poll opslaan bij blog aanmaken
- `controllers/blogs/view.php` - Poll data ophalen voor weergave
- `ajax/poll-vote.php` - API endpoint voor stemmen

**Frontend:**

- `views/blogs/create.php` - Poll aanmaak interface
- `views/blogs/view.php` - Poll weergave en stem interface

**Documentatie:**

- `docs/BLOG_POLLS_SETUP.md` - Deze documentatie
