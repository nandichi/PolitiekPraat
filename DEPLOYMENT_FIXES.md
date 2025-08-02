# StemmenTracker Fixes - Deployment Instructies

## 📁 Bestanden die geüpload moeten worden naar politiekpraat.nl:

### 1. **includes/Router.php** (KRITIEK)

- **Fix**: Query parameters worden nu behouden
- **Upload naar**: `/includes/Router.php`

### 2. **includes/functions.php** (KRITIEK)

- **Fix**: isAdmin() functie gebruikt nu `== 1` in plaats van `=== true`
- **Upload naar**: `/includes/functions.php`

### 3. **includes/helpers.php** (KRITIEK)

- **Fix**: isAdmin() functie ook aangepast voor consistentie
- **Upload naar**: `/includes/helpers.php`

### 4. **index.php** (BELANGRIJK)

- **Fix**: Nieuwe route voor `/stemmentracker/detail/{id}`
- **Upload naar**: `/index.php`

### 5. **views/stemmentracker/index.php** (BELANGRIJK)

- **Fix**: Links aangepast naar nieuwe URL structuur
- **Upload naar**: `/views/stemmentracker/index.php`

## 🚀 Upload Methodes:

### Via FTP/SFTP:

```bash
# Upload deze 5 bestanden naar de juiste locaties op politiekpraat.nl
- includes/Router.php
- includes/functions.php
- includes/helpers.php
- index.php
- views/stemmentracker/index.php
```

### Via File Manager (cPanel):

1. Log in op cPanel van politiekpraat.nl
2. Open File Manager
3. Upload elk bestand naar zijn juiste locatie
4. Overschrijf de bestaande bestanden

## ✅ Test na Upload:

1. **StemmenTracker Detail Pagina's**:

   - Ga naar https://politiekpraat.nl/stemmentracker
   - Klik op "Bekijk Details" bij een motie
   - Zou moeten werken zonder 404

2. **Admin Stemgedrag Beheer**:
   - Ga naar https://politiekpraat.nl/admin/stemmentracker-stemgedrag-beheer.php
   - Zou niet meer geblokkeerd moeten worden

## 🔧 Probleem Opgelost:

- ❌ **Voor**: Router verwijderde query parameters + foutieve admin auth
- ✅ **Na**: Query parameters behouden + correcte admin authenticatie

## 📞 Als er nog problemen zijn:

1. Check browser console voor JavaScript errors
2. Check server error logs
3. Verificeer dat alle bestanden correct geüpload zijn
4. Clear browser cache en probeer opnieuw
