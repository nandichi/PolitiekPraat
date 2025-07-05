# Blog Upload Debug Instructies

## Probleem

Blog afbeeldingen worden niet correct geüpload of weergegeven op de live server, terwijl het lokaal wel werkt.

## Mogelijke oorzaken

1. **Directory permissies**: De upload directory heeft geen schrijfrechten
2. **Bestandspad problemen**: BASE_PATH configuratie verschilt tussen lokaal en live
3. **Oude vs nieuwe upload locaties**: Verschillende blogs verwijzen naar verschillende directories
4. **PHP configuratie**: Upload limits of temp directory problemen
5. **Web server configuratie**: .htaccess of Apache/Nginx problemen

## Debug stappen

### Stap 1: Voer debug script uit

1. Upload `debug_blog_images.php` naar de root van je website
2. Ga naar `https://politiekpraat.nl/debug_blog_images.php`
3. Bekijk de output voor:
   - Directory bestaan en permissies
   - Bestaande blog image paths
   - PHP upload settings
   - Test upload functionaliteit

### Stap 2: Controleer server logs

1. Kijk in je server error logs voor berichten zoals:
   - "Blog image upload attempt"
   - "Creating directory result"
   - "Blog image uploaded successfully"
   - "Blog image upload failed"

### Stap 3: Voer path reparatie uit

1. Upload `fix_blog_image_paths.php` naar de root
2. Ga naar `https://politiekpraat.nl/fix_blog_image_paths.php`
3. Bekijk welke paths problemen hebben
4. Voer automatische fixes uit waar mogelijk

### Stap 4: Controleer directory permissies via FTP/SSH

```bash
# Via SSH (als je toegang hebt)
ls -la uploads/blogs/
ls -la uploads/blogs/images/
chmod 755 uploads/blogs/images/
```

### Stap 5: Test nieuwe upload

1. Probeer een nieuwe blog te plaatsen met afbeelding
2. Controleer of de error logs nieuwe berichten tonen
3. Controleer of het bestand daadwerkelijk is geüpload

## Veelvoorkomende fixes

### Fix 1: Directory permissies

```bash
chmod 755 uploads/blogs/images/
chown www-data:www-data uploads/blogs/images/
```

### Fix 2: Ontbrekende .htaccess

Zorg dat `uploads/blogs/images/.htaccess` bestaat met:

```apache
# Beveiligingsregels voor uploads
Options -Indexes
Options -ExecCGI
AddHandler cgi-script .php .pl .py .jsp .asp .sh .cgi

# PHP bestanden blokkeren
<FilesMatch "\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|sh|cgi)$">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Afbeeldingen toestaan
<FilesMatch "\.(jpg|jpeg|png|gif)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>
```

### Fix 3: PHP upload limits verhogen

In php.ini of .htaccess:

```
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
```

### Fix 4: Oude paths migreren

Als blogs verwijzen naar `uploads/blogs/` in plaats van `uploads/blogs/images/`:

1. Gebruik het fix script om paths bij te werken
2. Of verplaats bestanden naar de juiste directory

## Verbeterde logging

De blog upload code is bijgewerkt met uitgebreide logging. Kijk in je error logs naar:

- Upload directory status
- File existence checks
- Permission checks
- Exact error messages

## Na het fixen

1. Verwijder de debug scripts van de live server
2. Test grondig met verschillende bestandsformaten
3. Controleer of oude blogs hun afbeeldingen nog kunnen zien
4. Monitor de logs voor nieuwe uploads

## Contact

Als het probleem aanhoudt, controleer dan:

- Hosting provider documentatie
- PHP error logs
- Web server configuratie
- Beschikbare schijfruimte
