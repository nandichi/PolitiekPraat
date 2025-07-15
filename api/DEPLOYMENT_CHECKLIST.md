# API Deployment Checklist

## Stap 1: Upload bestanden naar live server

Upload deze bestanden naar je live server in de `/api/` directory:

- ✅ `api/index.php` (nieuwe versie met debug functies)
- ✅ `api/.htaccess` (verbeterde versie)
- ✅ `api/debug.php` (voor troubleshooting)

## Stap 2: Test de API

Na upload, test deze endpoints:

### Basis test:

```bash
curl https://politiekpraat.nl/api/
```

Verwacht: JSON response met API info

### Debug test:

```bash
curl https://politiekpraat.nl/api/debug.php
```

Verwacht: Gedetailleerde debug informatie

### Nieuwe test endpoint:

```bash
curl https://politiekpraat.nl/api/test
```

Verwacht: Test resultaten voor database, classes, etc.

## Stap 3: Test specifieke endpoints

### Auth test:

```bash
curl -X POST https://politiekpraat.nl/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"test"}'
```

### Blogs test:

```bash
curl https://politiekpraat.nl/api/blogs
```

### News test:

```bash
curl https://politiekpraat.nl/api/news
```

## Mogelijke problemen en oplossingen

### Problem 1: "Endpoint not found"

- **Oorzaak**: Oude code nog actief
- **Oplossing**: Upload nieuwe `api/index.php`

### Problem 2: "Database connection failed"

- **Oorzaak**: Database credentials kloppen niet
- **Oplossing**: Check `includes/config.php` database settings

### Problem 3: "Class not found"

- **Oorzaak**: Include path problemen
- **Oplossing**: Check dat alle `includes/` bestanden bestaan

### Problem 4: "Permission denied"

- **Oorzaak**: File permissions
- **Oplossing**: Set correct permissions (755 voor directories, 644 voor bestanden)

## Debug commands

### Check file permissions:

```bash
ls -la api/
```

### Check PHP error log:

```bash
tail -f /path/to/php/error.log
```

### Test database connection direct:

Gebruik `api/debug.php` voor gedetailleerde informatie

## Success indicators

✅ `curl https://politiekpraat.nl/api/` geeft success response
✅ `curl https://politiekpraat.nl/api/test` geeft test results
✅ `curl https://politiekpraat.nl/api/debug.php` toont alle checks als OK
✅ Database connection = "SUCCESS"
✅ Alle classes = "OK"

Wanneer alle indicators groen zijn, is je API volledig operationeel!
