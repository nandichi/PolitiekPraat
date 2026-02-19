# PolitiekPraat API - Blog publiceren via API-sleutel

Documentatie voor het koppelen van externe systemen (zoals OpenClaw, n8n, Make) aan PolitiekPraat voor het automatisch plaatsen van blogs.

---

## 1. Overzicht

Met een API-sleutel kun je blogs aanmaken, bewerken en verwijderen via de REST API. Alle blogs die via de API-sleutel worden geplaatst, verschijnen onder de gebruiker **naoufal** (ID=1).

**Base URL (productie):** `https://politiekpraat.nl/api`

---

## 2. API-sleutel verkrijgen

1. Log in op PolitiekPraat als admin
2. Ga naar **Admin Dashboard** > **API Sleutels**
3. Vul een naam in (bijv. "OpenClaw integratie") en klik op **Sleutel Genereren**
4. **Kopieer de sleutel direct** – deze wordt slechts eenmaal getoond
5. Sla de sleutel veilig op (bijv. in een wachtwoordmanager of als environment variable)

De sleutel ziet er zo uit: `pp_` gevolgd door 40 hexadecimale tekens (bijv. `pp_a1b2c3d4e5f6...`).

---

## 3. Authenticatie

Stuur de API-sleutel mee als HTTP-header bij elk request:

```
X-API-Key: pp_uw-sleutel-hier
```

**Belangrijk:** Gebruik de header `X-API-Key`, niet `Authorization`.

---

## 4. Endpoints

### 4.1 Blog aanmaken (POST)

**URL:** `POST https://politiekpraat.nl/api/blogs`

**Headers:**
```
Content-Type: application/json
X-API-Key: pp_uw-sleutel-hier
```

**Body (JSON):**

| Veld       | Verplicht | Type   | Beschrijving                                      |
|-----------|-----------|--------|---------------------------------------------------|
| title     | Ja        | string | Titel van de blog (max. 255 tekens)               |
| content   | Ja        | string | Volledige inhoud (HTML of platte tekst)          |
| summary   | Nee       | string | Korte samenvatting; wordt automatisch gegenereerd als leeg |
| image_url | Nee       | string | URL van afbeelding of relatief pad                |
| video_url | Nee       | string | URL van video (YouTube, Vimeo, etc.)              |
| audio_url | Nee       | string | URL van audiobestand                              |

**Minimaal voorbeeld:**
```json
{
  "title": "Mijn eerste blog via API",
  "content": "Dit is de inhoud van de blog. HTML is toegestaan."
}
```

**Volledig voorbeeld:**
```json
{
  "title": "Nieuwe analyse: verkiezingen 2025",
  "content": "<p>De verkiezingen van 2025 laten een interessante verschuiving zien...</p>",
  "summary": "Een korte samenvatting voor de voorvertoning.",
  "image_url": "https://example.com/afbeelding.jpg",
  "video_url": ""
}
```

**Succesvolle response (201):**
```json
{
  "success": true,
  "data": {
    "message": "Blog succesvol aangemaakt",
    "blog": {
      "id": 123,
      "title": "Mijn eerste blog via API",
      "slug": "mijn-eerste-blog-via-api",
      "summary": "...",
      "content": "...",
      "author": {
        "id": 1,
        "name": "Naoufal",
        "profile_photo": "..."
      },
      "published_at": "2025-02-19 14:30:00",
      "views": 0,
      "likes": 0,
      "media": {
        "image_url": "...",
        "video_url": null,
        "audio_url": null
      }
    }
  },
  "timestamp": "2025-02-19T14:30:00+00:00"
}
```

---

### 4.2 Blog bewerken (PUT)

**URL:** `PUT https://politiekpraat.nl/api/blogs/{id}`

Vervang `{id}` door het blog-ID (bijv. `123`).

**Headers:** Zelfde als bij POST.

**Body:** Zelfde velden als bij aanmaken. Alleen meegegeven velden worden bijgewerkt.

---

### 4.3 Blog verwijderen (DELETE)

**URL:** `DELETE https://politiekpraat.nl/api/blogs/{id}`

**Headers:** Alleen `X-API-Key` is nodig.

---

## 5. cURL voorbeelden

**Blog aanmaken:**
```bash
curl -X POST "https://politiekpraat.nl/api/blogs" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pp_uw-sleutel-hier" \
  -d '{
    "title": "Test blog via API",
    "content": "Dit is de inhoud."
  }'
```

**Blog bewerken:**
```bash
curl -X PUT "https://politiekpraat.nl/api/blogs/123" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: pp_uw-sleutel-hier" \
  -d '{"title": "Aangepaste titel", "content": "Aangepaste inhoud"}'
```

**Blog verwijderen:**
```bash
curl -X DELETE "https://politiekpraat.nl/api/blogs/123" \
  -H "X-API-Key: pp_uw-sleutel-hier"
```

---

## 6. OpenClaw / n8n / Make integratie

### HTTP Request configuratie

| Instelling   | Waarde                                      |
|-------------|---------------------------------------------|
| Method      | POST (of PUT/DELETE)                        |
| URL         | `https://politiekpraat.nl/api/blogs`        |
| Headers     | `X-API-Key`: `pp_uw-sleutel`                |
| Body Type   | JSON                                        |
| Body        | `{"title": "...", "content": "..."}`        |

### n8n

- Gebruik de **HTTP Request** node
- Method: POST
- URL: `https://politiekpraat.nl/api/blogs`
- Authentication: None (handmatig header)
- Add header: `X-API-Key` = `{{ $env.API_KEY }}` of hardcoded sleutel
- Body Content Type: JSON
- Specify Body: Using JSON
- JSON: `{ "title": "{{ $json.title }}", "content": "{{ $json.content }}" }`

### Make (Integromat)

- **HTTP** module > **Make a request**
- URL: `https://politiekpraat.nl/api/blogs`
- Method: POST
- Headers: `X-API-Key` = uw sleutel
- Body type: Raw
- Content type: application/json
- Request content: JSON object met title en content

---

## 7. Foutafhandeling

| Status | Betekenis |
|--------|-----------|
| 200    | Succes (bij PUT/DELETE) |
| 201    | Blog aangemaakt |
| 400    | Ongeldige input (ontbrekende titel/content, invalid JSON) |
| 401    | Geen of ongeldige API-sleutel |
| 403    | Geen rechten (bijv. blog van andere gebruiker bewerken) |
| 404    | Blog niet gevonden |
| 500    | Serverfout |

**Voorbeeld foutresponse:**
```json
{
  "success": false,
  "error": "Authenticatie vereist",
  "timestamp": "2025-02-19T14:30:00+00:00"
}
```

---

## 8. Beveiliging

- Deel de API-sleutel **nooit** in code die naar een repository gaat
- Gebruik environment variables of secrets in automatiseringstools
- Deactiveer of verwijder sleutels direct als ze mogelijk gelekt zijn (via Admin > API Sleutels)
- Elke sleutel is gekoppeld aan gebruiker naoufal; alle blogs verschijnen onder die account
