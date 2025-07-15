# PolitiekPraat REST API Documentatie

De PolitiekPraat REST API biedt programmatische toegang tot alle functionaliteiten van het platform, inclusief authenticatie, blogs, nieuws en gebruikersbeheer.

## Base URL

- **Development**: `http://localhost:8000/api`
- **Production**: `https://politiekpraat.nl/api`

## Authenticatie

De API gebruikt JWT (JSON Web Tokens) voor authenticatie. Include de token in de `Authorization` header:

```
Authorization: Bearer {your-jwt-token}
```

## Response Format

Alle API responses volgen een consistente structuur:

### Succes Response

```json
{
  "success": true,
  "data": {
    // Response data hier
  },
  "timestamp": "2024-01-15T10:30:00+01:00"
}
```

### Error Response

```json
{
  "success": false,
  "error": "Error message hier",
  "timestamp": "2024-01-15T10:30:00+01:00"
}
```

## API Endpoints

### 1. Authenticatie

#### POST /api/auth/login

Inloggen van gebruiker.

**Request Body:**

```json
{
  "email": "user@example.com",
  "password": "wachtwoord123"
}
```

**Response:**

```json
{
  "success": true,
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
      "id": 1,
      "username": "gebruiker",
      "email": "user@example.com",
      "is_admin": false,
      "profile_photo": null,
      "created_at": "2024-01-01 12:00:00"
    }
  }
}
```

#### POST /api/auth/register

Registreren van nieuwe gebruiker.

**Request Body:**

```json
{
  "username": "nieuwe_gebruiker",
  "email": "nieuw@example.com",
  "password": "wachtwoord123",
  "confirm_password": "wachtwoord123"
}
```

**Response:** (201 Created)

```json
{
  "success": true,
  "data": {
    "message": "Account succesvol aangemaakt",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
      "id": 2,
      "username": "nieuwe_gebruiker",
      "email": "nieuw@example.com",
      "is_admin": false,
      "profile_photo": null,
      "created_at": "2024-01-15 10:30:00"
    }
  }
}
```

#### POST /api/auth/logout

Uitloggen (client-side token verwijdering).

#### GET /api/auth/me

Haal huidige gebruikersgegevens op.

**Requires Authentication**

#### POST /api/auth/refresh

Ververs JWT token.

**Requires Authentication**

#### GET /api/auth/verify

Verifieer token geldigheid.

### 2. Blogs

#### GET /api/blogs

Haal alle blogs op met paginering.

**Query Parameters:**

- `page` (integer): Pagina nummer (default: 1)
- `limit` (integer): Aantal blogs per pagina (max: 50, default: 10)
- `author_id` (integer): Filter op auteur ID
- `search` (string): Zoek in titel en content

**Response:**

```json
{
  "success": true,
  "data": {
    "blogs": [
      {
        "id": 1,
        "title": "Blog Titel",
        "slug": "blog-titel",
        "summary": "Blog samenvatting...",
        "author": {
          "id": 1,
          "name": "Auteur Naam",
          "profile_photo": "https://example.com/photo.jpg"
        },
        "published_at": "2024-01-15 10:00:00",
        "views": 150,
        "likes": 25,
        "media": {
          "image_url": "https://example.com/image.jpg",
          "video_url": null,
          "audio_url": null
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "limit": 10,
      "total": 45,
      "total_pages": 5,
      "has_next": true,
      "has_prev": false
    }
  }
}
```

#### GET /api/blogs/{id}

Haal specifieke blog op (via ID of slug).

**Response:**

```json
{
  "success": true,
  "data": {
    "blog": {
      "id": 1,
      "title": "Blog Titel",
      "slug": "blog-titel",
      "summary": "Blog samenvatting...",
      "content": "Volledige blog content in Markdown...",
      "author": {
        "id": 1,
        "name": "Auteur Naam",
        "profile_photo": "https://example.com/photo.jpg"
      },
      "published_at": "2024-01-15 10:00:00",
      "views": 151,
      "likes": 25,
      "media": {
        "image_url": "https://example.com/image.jpg",
        "video_url": null,
        "audio_url": null
      }
    }
  }
}
```

#### POST /api/blogs

Maak nieuwe blog aan.

**Requires Authentication**

**Request Body:**

```json
{
  "title": "Nieuwe Blog",
  "content": "Blog content in Markdown",
  "summary": "Optionele samenvatting",
  "image_url": "https://example.com/image.jpg",
  "video_url": "https://youtube.com/watch?v=...",
  "audio_url": "https://soundcloud.com/..."
}
```

#### PUT /api/blogs/{id}

Bijwerken van bestaande blog.

**Requires Authentication + Ownership/Admin**

#### DELETE /api/blogs/{id}

Verwijderen van blog.

**Requires Authentication + Ownership/Admin**

### 3. Nieuws

#### GET /api/news

Haal nieuws op met filtering en paginering.

**Query Parameters:**

- `page` (integer): Pagina nummer (default: 1)
- `limit` (integer): Aantal artikelen per pagina (max: 50, default: 20)
- `filter` (string): Filter op oriëntatie (`alle`, `progressief`/`links`, `conservatief`/`rechts`, `midden`)
- `search` (string): Zoek in titel en beschrijving

**Response:**

```json
{
  "success": true,
  "data": {
    "news": [
      {
        "id": 1,
        "title": "Nieuws Titel",
        "description": "Artikel beschrijving...",
        "url": "https://nieuwsbron.nl/artikel",
        "source": {
          "name": "De Volkskrant",
          "orientation": "links",
          "bias": "Progressief"
        },
        "published_at": "2024-01-15 08:00:00",
        "created_at": "2024-01-15 08:15:00",
        "reading_time": 3,
        "orientation_color": "#EF4444"
      }
    ],
    "pagination": {
      "current_page": 1,
      "limit": 20,
      "total": 150,
      "total_pages": 8,
      "has_next": true,
      "has_prev": false
    },
    "filter": "alle"
  }
}
```

#### GET /api/news/stats

Haal nieuws statistieken op.

**Response:**

```json
{
  "success": true,
  "data": {
    "statistics": {
      "total_articles": 1250,
      "articles_today": 45,
      "articles_this_week": 320,
      "by_orientation": {
        "links": 400,
        "rechts": 450,
        "midden": 400
      },
      "last_updated": "2024-01-15 10:30:00"
    }
  }
}
```

#### GET /api/news/sources

Haal lijst van nieuwsbronnen op.

#### GET /api/news/recent

Haal recent nieuws op.

**Query Parameters:**

- `limit` (integer): Aantal artikelen (max: 20, default: 10)
- `hours` (integer): Aantal uren terug (max: 168, default: 24)

### 4. Gebruiker

#### GET /api/user/profile

Haal gebruikersprofiel op.

**Requires Authentication**

**Response:**

```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "username": "gebruiker",
      "email": "user@example.com",
      "bio": "Mijn bio...",
      "profile_photo": "https://example.com/photo.jpg",
      "member_since": "2024-01-01 12:00:00",
      "is_admin": false,
      "stats": {
        "blogs_written": 5,
        "comments_made": 23
      }
    }
  }
}
```

#### PUT /api/user/profile

Bijwerken van gebruikersprofiel.

**Requires Authentication**

**Request Body:**

```json
{
  "username": "nieuwe_naam",
  "email": "nieuw@example.com",
  "bio": "Bijgewerkte bio"
}
```

#### PUT /api/user/password

Wachtwoord wijzigen.

**Requires Authentication**

**Request Body:**

```json
{
  "current_password": "huidig_wachtwoord",
  "new_password": "nieuw_wachtwoord",
  "confirm_password": "nieuw_wachtwoord"
}
```

#### GET /api/user/stats

Haal gedetailleerde gebruikersstatistieken op.

**Requires Authentication**

#### GET /api/user/blogs

Haal blogs van huidige gebruiker op.

**Requires Authentication**

### 5. Stemwijzer

#### GET /api/stemwijzer

Toegang tot bestaande stemwijzer API (zie `api/stemwijzer.php`).

## HTTP Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `405` - Method Not Allowed
- `409` - Conflict (bijv. email al in gebruik)
- `500` - Internal Server Error

## Rate Limiting

De API heeft momenteel geen rate limiting geïmplementeerd, maar dit wordt aanbevolen voor productie.

## CORS

CORS is geconfigureerd om alle origins toe te staan. Voor productie wordt aanbevolen dit te beperken tot specifieke domains.

## Foutafhandeling

Alle endpoints hebben consistente foutafhandeling met duidelijke error messages in het Nederlands.

## JWT Token Informatie

- **Geldigheid**: 24 uur
- **Algoritme**: HS256
- **Refresh**: Gebruik `/api/auth/refresh` endpoint

## Voorbeelden

### Volledige workflow voor mobile app

1. **Registreren**:

```bash
curl -X POST https://politiekpraat.nl/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "testuser",
    "email": "test@example.com",
    "password": "wachtwoord123",
    "confirm_password": "wachtwoord123"
  }'
```

2. **Inloggen**:

```bash
curl -X POST https://politiekpraat.nl/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "wachtwoord123"
  }'
```

3. **Blogs ophalen**:

```bash
curl -X GET "https://politiekpraat.nl/api/blogs?page=1&limit=10" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

4. **Blog aanmaken**:

```bash
curl -X POST https://politiekpraat.nl/api/blogs \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "title": "Mijn nieuwe blog",
    "content": "Dit is de content van mijn blog in Markdown.",
    "summary": "Een korte samenvatting"
  }'
```

5. **Nieuws ophalen**:

```bash
curl -X GET "https://politiekpraat.nl/api/news?filter=progressief&page=1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## Ontwikkelaar Informatie

Voor vragen of ondersteuning, neem contact op via:

- Email: naoufal.exe@gmail.com
- Website: https://politiekpraat.nl

**Versie**: 1.0.0
**Laatste Update**: Januari 2024
