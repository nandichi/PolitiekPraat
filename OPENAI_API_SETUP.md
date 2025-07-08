# OpenAI API Key Setup

## Probleem

De OpenAI API key was hard-coded in de source code, waardoor deze zichtbaar was in de git repository. Dit is een beveiligingsrisico en de key is door OpenAI uitgeschakeld.

## Oplossing

De ChatGPT API class is aangepast om de API key uit veilige bronnen te halen in plaats van hard-coded in de code.

## Configuratie Opties

### Optie 1: .env bestand (Aanbevolen)

1. Maak een `.env` bestand aan in de root directory
2. Voeg de volgende regel toe:

```
OPENAI_API_KEY=sk-proj-jouw-nieuwe-api-key-hier
```

### Optie 2: Environment variabele

Stel een environment variabele in:

```bash
export OPENAI_API_KEY=sk-proj-jouw-nieuwe-api-key-hier
```

### Optie 3: Config bestand

1. Maak een bestand aan: `config/api_keys.php`
2. Voeg de volgende inhoud toe:

```php
<?php
return [
    'openai_api_key' => 'sk-proj-jouw-nieuwe-api-key-hier'
];
```

## Nieuwe API Key Aanmaken

1. Ga naar https://platform.openai.com/api-keys
2. Maak een nieuwe API key aan
3. Configureer de key volgens een van de bovenstaande opties
4. Test de verbinding met het test script

## Beveiliging

- Het `.env` bestand staat in `.gitignore` en wordt niet gecommit
- Het `config/api_keys.php` bestand staat in `.gitignore` en wordt niet gecommit
- Deel nooit API keys via chat, email of andere onveilige kanalen

## Testen

Na configuratie kun je de API verbinding testen met:

```php
$chatGPT = new ChatGPTAPI();
$result = $chatGPT->testConnection();
```
