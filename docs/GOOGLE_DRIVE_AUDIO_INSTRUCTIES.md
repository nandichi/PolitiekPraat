# Audio Features - Handleiding (Google Drive & SoundCloud)

## 🎵 Overzicht

Het audio systeem ondersteunt nu **drie verschillende manieren** om audio toe te voegen aan blogs:

1. **🎯 SoundCloud (AANBEVOLEN)** - Betrouwbare streaming zonder technische problemen
2. **📁 Google Drive** - Voor wie al audio op Google Drive heeft staan
3. **💾 Lokale Upload** - Direct upload naar de website (snelst en meest betrouwbaar)

## 🏆 Audio Opties Vergelijking

| Feature                     | SoundCloud    | Google Drive     | Lokale Upload |
| --------------------------- | ------------- | ---------------- | ------------- |
| **Betrouwbaarheid**         | ✅ Uitstekend | ⚠️ Matig         | ✅ Uitstekend |
| **Snelheid**                | ✅ Snel       | ❌ Langzaam      | ✅ Zeer snel  |
| **Professionele Player**    | ✅ Ja         | ❌ Nee           | ⚡ Basis      |
| **Maximale grootte**        | ✅ Groot      | ✅ Groot         | ⚠️ 50MB       |
| **Externe afhankelijkheid** | ✅ Stabiel    | ❌ Onbetrouwbaar | ✅ Geen       |

**💡 Aanbeveling:** Gebruik SoundCloud voor de beste gebruikerservaring!

## ⚠️ Belangrijke Beperkingen

**Google Drive heeft strikte CORS (Cross-Origin Resource Sharing) beperkingen** die directe audio streaming kunnen blokkeren. Dit betekent dat:

- Audio mogelijk niet direct afspeelbaar is in alle browsers
- Gebruikers mogelijk worden doorverwezen naar Google Drive zelf
- Download opties beschikbaar zijn als fallback

## 📋 Stap-voor-Stap Instructies

## 🎯 Optie 1: SoundCloud (Aanbevolen)

### 1. Audio uploaden naar SoundCloud

1. Ga naar [SoundCloud](https://soundcloud.com) en log in
2. Klik op "Upload"
3. Sleep je audio bestand naar SoundCloud
4. Voeg titel, beschrijving toe
5. Zet privacy op **"Public"** (anders werkt het niet!)
6. Klik "Publish"

### 2. Link kopiëren en toevoegen

1. Ga naar je geüploade track
2. Klik op "Share" onder de track
3. Kopieer de URL (bijv. `https://soundcloud.com/user/track-name`)
4. Plak deze in het **"SoundCloud Audio URL"** veld in je blog

✅ **Klaar!** SoundCloud werkt direct zonder verdere configuratie.

---

## 📁 Optie 2: Google Drive

### 1. Audio Bestand Uploaden naar Google Drive

1. Ga naar [Google Drive](https://drive.google.com)
2. Sleep je MP3/WAV/OGG bestand naar Google Drive
3. Wacht tot de upload voltooid is

### 2. Bestand Openbaar Maken

1. **Rechtsklik** op het geüploade audio bestand
2. Selecteer **"Delen"** uit het menu
3. Klik op **"Wijzig toegang"** (lock icoon)
4. Selecteer **"Iedereen met de link"**
5. Zorg dat de rechten op **"Bekijker"** staan
6. Klik **"Kopiëren link"**

### 3. Link Toevoegen in Blog

1. Ga naar de blog create/edit pagina
2. Plak de Google Drive link in het **"Google Drive Audio URL"** veld
3. De link zou er ongeveer zo uit moeten zien:
   ```
   https://drive.google.com/file/d/1ABC123xyz.../view?usp=sharing
   ```

## 🔧 Hoe het Werkt

### Frontend Verwerking

1. **URL Extractie**: Het systeem extraheert de file ID uit de Google Drive URL
2. **Meerdere Pogingen**: Probeert verschillende Google Drive API endpoints
3. **Fallback Opties**: Als directe streaming faalt, toont alternatieve opties

### Ondersteunde URL Formaten

- `https://drive.google.com/file/d/FILE_ID/view`
- `https://drive.google.com/file/d/FILE_ID/view?usp=sharing`
- `https://drive.google.com/open?id=FILE_ID`

## 🎯 Best Practices

### ✅ Aanbevolen

- **Kleine bestanden**: Upload direct naar de website (sneller, betrouwbaarder)
- **Grote bestanden**: Gebruik Google Drive als fallback
- **Test altijd**: Controleer of de audio werkt voordat je publiceert
- **Backup plan**: Vermeld altijd download opties voor gebruikers

### ❌ Vermijd

- Privé Google Drive links (werken niet)
- Zeer lange audio bestanden zonder waarschuwing
- Alleen Google Drive zonder lokale fallback

## 🔍 Troubleshooting

### Probleem: Audio speelt niet af

**Mogelijke oorzaken:**

1. Bestand is niet openbaar gedeeld
2. Google Drive CORS beperkingen
3. Browser compatibiliteit

**Oplossingen:**

1. Controleer sharing instellingen
2. Gebruik "Open in Google Drive" knop
3. Download het bestand lokaal

### Probleem: Foutmelding "Kan Google Drive audio niet laden"

**Oorzaak:** Ongeldige URL of file ID
**Oplossing:** Controleer of de link correct is gekopieerd

### Probleem: Audio laadt heel langaagaam

**Oorzaak:** Groot bestand of langzame Google Drive servers
**Oplossing:** Overweeg lokale upload voor betere performance

## 🔄 Alternatieve Oplossingen

### 1. Lokale Upload (Aanbevolen)

- Upload direct naar `uploads/blogs/audio/`
- Maximaal 50MB per bestand
- Geen externe afhankelijkheden
- Betere performance en betrouwbaarheid

### 2. Externe Audio Hosting

- SoundCloud embed
- YouTube audio
- Dedicated audio hosting platforms

## 📊 Technische Details

### Database Schema

```sql
ALTER TABLE blogs ADD audio_url VARCHAR(500) NULL;
```

### Supported Audio Formats

- MP3 (aanbevolen)
- WAV
- OGG

### Browser Compatibility

- Chrome: ✅ Volledig ondersteund
- Firefox: ✅ Volledig ondersteund
- Safari: ⚠️ Mogelijk beperkingen
- Edge: ✅ Volledig ondersteund

## 📝 Changelog

### Versie 1.0 (2024-01-XX)

- Initiële implementatie Google Drive audio support
- Fallback mechanisme voor gefaalde streams
- Uitgebreide error handling
- Alternatieve download opties

---

## 🆘 Support

Bij problemen met Google Drive audio, neem contact op met de website beheerder of gebruik de lokale upload optie als alternatief.
