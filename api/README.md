# PolitiekPraat REST API

Deze REST API is gebouwd voor de PolitiekPraat website en biedt endpoints voor blogs en authenticatie.

## Endpoints

### Blogs API

#### Alle blogs ophalen

```bash
GET /api/blogs.php
```

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Blog titel",
      "slug": "blog-titel",
      "summary": "Korte samenvatting",
      "content": "Volledige blog content...",
      "image_path": "/uploads/blogs/image.jpg",
      "video_path": null,
      "video_url": null,
      "views": 42,
      "published_at": "2024-01-15 10:30:00",
      "author_name": "Gebruikersnaam"
    }
  ],
  "count": 1,
  "message": "Blogs succesvol opgehaald"
}
```

#### Specifieke blog ophalen

```bash
GET /api/blogs.php?id=1
```

**Response:**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Blog titel",
    "slug": "blog-titel",
    "summary": "Korte samenvatting",
    "content": "Volledige blog content...",
    "image_path": "/uploads/blogs/image.jpg",
    "video_path": null,
    "video_url": null,
    "views": 43,
    "published_at": "2024-01-15 10:30:00",
    "author_name": "Gebruikersnaam"
  },
  "message": "Blog succesvol opgehaald"
}
```

### Authenticatie API

#### Inloggen

```bash
POST /api/auth.php?action=login
Content-Type: application/json

{
  "email": "gebruiker@example.com",
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
      "email": "gebruiker@example.com",
      "is_admin": false
    }
  },
  "message": "Succesvol ingelogd"
}
```

#### Registreren

```bash
POST /api/auth.php?action=register
Content-Type: application/json

{
  "username": "nieuwegebruiker",
  "email": "nieuw@example.com",
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
      "id": 2,
      "username": "nieuwegebruiker",
      "email": "nieuw@example.com",
      "is_admin": false
    }
  },
  "message": "Account succesvol aangemaakt"
}
```

#### Token verifiëren

```bash
POST /api/auth.php?action=verify
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Content-Type: application/json
```

**Response:**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "gebruiker",
    "email": "gebruiker@example.com",
    "is_admin": false
  },
  "message": "Gebruiker succesvol opgehaald"
}
```

## Authenticatie

De API gebruikt JWT (JSON Web Tokens) voor authenticatie:

- **Token lifetime**: 7 dagen
- **Header format**: `Authorization: Bearer {token}`
- **Token generatie**: Automatisch bij login/register
- **Token verificatie**: Gebruik de `verify` endpoint

## Voorbeeldcode

### JavaScript (Fetch API)

```javascript
// Inloggen
async function login(email, password) {
  const response = await fetch("/api/auth.php?action=login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ email, password }),
  });

  const data = await response.json();
  if (data.success) {
    // Sla token op
    localStorage.setItem("token", data.data.token);
    return data.data.user;
  } else {
    throw new Error(data.message);
  }
}

// Blogs ophalen
async function getBlogs() {
  const response = await fetch("/api/blogs.php");
  const data = await response.json();

  if (data.success) {
    return data.data;
  } else {
    throw new Error(data.message);
  }
}

// Authenticated request
async function getCurrentUser() {
  const token = localStorage.getItem("token");

  const response = await fetch("/api/auth.php?action=verify", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
    },
  });

  const data = await response.json();
  if (data.success) {
    return data.data;
  } else {
    // Token is ongeldig, verwijder uit localStorage
    localStorage.removeItem("token");
    throw new Error(data.message);
  }
}
```

### PHP (cURL)

```php
<?php
// Inloggen
function login($email, $password) {
    $url = 'http://localhost:8000/api/auth.php?action=login';
    $data = json_encode(['email' => $email, 'password' => $password]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Blogs ophalen
function getBlogs() {
    $url = 'http://localhost:8000/api/blogs.php';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Token verifiëren
function verifyToken($token) {
    $url = 'http://localhost:8000/api/auth.php?action=verify';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Voorbeeld gebruik
$loginResult = login('gebruiker@example.com', 'wachtwoord123');
if ($loginResult['success']) {
    $token = $loginResult['data']['token'];
    echo "Token: " . $token . "\n";

    // Verificeer token
    $verifyResult = verifyToken($token);
    if ($verifyResult['success']) {
        echo "Gebruiker: " . $verifyResult['data']['username'] . "\n";
    }
}

// Blogs ophalen
$blogsResult = getBlogs();
if ($blogsResult['success']) {
    echo "Aantal blogs: " . $blogsResult['count'] . "\n";
}
?>
```

### Python (requests)

```python
import requests
import json

# Inloggen
def login(email, password):
    url = 'http://localhost:8000/api/auth.php?action=login'
    data = {'email': email, 'password': password}

    response = requests.post(url, json=data)
    return response.json()

# Blogs ophalen
def get_blogs():
    url = 'http://localhost:8000/api/blogs.php'

    response = requests.get(url)
    return response.json()

# Token verifiëren
def verify_token(token):
    url = 'http://localhost:8000/api/auth.php?action=verify'
    headers = {'Authorization': f'Bearer {token}'}

    response = requests.post(url, headers=headers)
    return response.json()

# Voorbeeld gebruik
login_result = login('gebruiker@example.com', 'wachtwoord123')
if login_result['success']:
    token = login_result['data']['token']
    print(f"Token: {token}")

    # Verificeer token
    verify_result = verify_token(token)
    if verify_result['success']:
        print(f"Gebruiker: {verify_result['data']['username']}")

# Blogs ophalen
blogs_result = get_blogs()
if blogs_result['success']:
    print(f"Aantal blogs: {blogs_result['count']}")
```

## Error Handling

Alle endpoints retourneren consistente error responses:

```json
{
  "success": false,
  "error": "Error type",
  "message": "Nederlandse foutmelding"
}
```

### HTTP Status Codes

- `200` - Success
- `400` - Bad Request (ongeldige parameters)
- `401` - Unauthorized (authenticatie vereist/ongeldige token)
- `403` - Forbidden (geen toegang)
- `404` - Not Found (resource niet gevonden)
- `405` - Method Not Allowed (HTTP methode niet ondersteund)
- `500` - Internal Server Error (server fout)

## Uitbreidbaarheid

De API is ontworpen om makkelijk uit te breiden:

1. **Nieuwe endpoints**: Voeg nieuwe PHP bestanden toe in de `/api` map
2. **Authenticatie**: Gebruik `JWTHelper::requireAuth()` voor beschermde endpoints
3. **Admin endpoints**: Gebruik `JWTHelper::requireAdmin()` voor admin-only endpoints
4. **Database queries**: Gebruik de bestaande `Database` class met prepared statements

## Beveiliging

- **Prepared statements**: Alle database queries gebruiken prepared statements
- **Password hashing**: Wachtwoorden worden gehashed met `password_hash()`
- **JWT tokens**: Veilige token-based authenticatie
- **CORS headers**: Juiste CORS configuratie voor cross-origin requests
- **Input validation**: Alle input wordt gevalideerd
- **Error handling**: Geen gevoelige informatie in error messages

## Database Schema

De API gebruikt de bestaande database tabellen:

### Users tabel

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Blogs tabel

```sql
CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary TEXT,
    content LONGTEXT,
    image_path VARCHAR(255),
    video_path VARCHAR(255),
    video_url VARCHAR(255),
    author_id INT,
    views INT DEFAULT 0,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);
```
