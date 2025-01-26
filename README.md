# PolitiekPraat 

PolitiekPraat is een website waar iedereen kan meepraten over Nederlandse politiek. We maken politiek makkelijk te begrijpen en brengen burgers en politiek dichter bij elkaar.

## 🎯 Waar staan wij voor?

Wij vinden dat iedereen moet kunnen meepraten over politiek. Daarom willen we:
- Politiek uitleggen in normale mensentaal
- Een plek maken waar mensen fijn met elkaar kunnen praten
- Mensen helpen om mee te doen met politieke beslissingen
- Laten zien hoe politieke keuzes worden gemaakt

## 🚀 Wat kun je allemaal doen?

### 📰 Nieuws
- Het laatste politieke nieuws, meteen als het gebeurt
- Uitleg over wat er in de politiek gebeurt
- Een overzicht van belangrijke politieke momenten

### 💭 Meepraten over thema's
- Praten over onderwerpen die nu spelen
- Uitleg van mensen die er veel vanaf weten
- Controleren of informatie klopt
- Zorgen dat iedereen netjes met elkaar omgaat

### ✍️ Blogs schrijven
- Je eigen mening delen over politiek
- Zelf stukjes schrijven over politieke onderwerpen
- Reageren op elkaars verhalen

### 📊 Cijfers en feiten
- Handige plaatjes die politiek uitleggen
- Zien wat politici doen en beslissen
- Actuele peilingen en uitslagen

### 📅 Politieke agenda
- Zien wat er binnenkort gebeurt
- Wanneer er belangrijke debatten zijn
- Belangrijke data en deadlines

## 🛠️ Technische informatie

### Wat heb je nodig?
- PHP 7.4 of nieuwer
- MySQL 5.7 of nieuwer
- Composer
- Een webserver

### Hoe zet je het op?

1. Kopieer de code:
```bash
git clone https://github.com/jouw-username/PolitiekPraat.git
cd PolitiekPraat
```

2. Installeer de benodigde onderdelen:
```bash
composer install
```

3. Stel de database in:
- Maak een nieuwe database
- Kopieer `includes/config.example.php` naar `includes/config.php`
- Vul je database gegevens in

4. Zet de database klaar:
```bash
mysql -u gebruiker -p database_naam < database/setup.sql
```

## 🔧 Hoe zit de website in elkaar?

```
PolitiekPraat/
├── controllers/      # Besturing van de website
├── includes/         # Belangrijke onderdelen en instellingen
├── views/           # Wat je op het scherm ziet
├── public/          # Plaatjes, styling en scripts
├── scripts/         # Handige hulpprogramma's
└── database/        # Database bestanden
```

## 🔒 Veiligheid & Privacy

- Veilig inloggen
- Netjes omgaan met je gegevens (volgens de wet)
- Bescherming tegen hackers
- Regelmatige controles op veiligheid

## 🤝 Meehelpen

Wil je meehelpen om de website beter te maken? Graag! Zo doe je dat:

1. Maak een kopie van het project
2. Maak een nieuwe tak voor je verbetering (`git checkout -b verbetering/NieuweFeature`)
3. Sla je werk op (`git commit -m 'Nieuwe feature toegevoegd'`)
4. Stuur je werk door (`git push origin verbetering/NieuweFeature`)
5. Vraag of we je verbetering willen toevoegen

## 📝 Licentie

Je mag deze code gebruiken volgens de MIT License.

## 👥 Team

- [Naoufal Andichi] - maker van de website

## 📞 Contact

Heb je vragen of ideeën? Je kunt ons bereiken via:
- Email: [naoufal.exe@gmail.com]
- Website: [www.politiekpraat.nl]
- Twitter: [@naoufalexe]

---

*PolitiekPraat - Samen praten over politiek* 🇳🇱