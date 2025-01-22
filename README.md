# PolitiekPraat 🗳️

PolitiekPraat is een interactief platform waar gebruikers kunnen discussiëren over Nederlandse politiek, blogs kunnen schrijven en op de hoogte kunnen blijven van het laatste politieke nieuws.

## 🚀 Functionaliteiten

- **Nieuws Feed**: Actueel politiek nieuws van verschillende Nederlandse nieuwsbronnen
- **Blog Platform**: Gebruikers kunnen politieke blogs schrijven en delen
- **Forum**: Interactieve discussies over politieke onderwerpen
- **Politieke Agenda**: Overzicht van belangrijke politieke gebeurtenissen
- **Actuele Thema's**: Inzicht in trending politieke onderwerpen
- **Statistieken**: Real-time inzichten in politieke activiteiten

## 📋 Vereisten

- PHP 7.4 of hoger
- MySQL 5.7 of hoger
- Composer
- Web server 

## 🛠️ Installatie

1. Clone de repository:
```bash
git clone https://github.com/jouw-username/PolitiekPraat.git
cd PolitiekPraat
```

2. Installeer dependencies:
```bash
composer install
```

3. Configureer de database:
- Maak een nieuwe MySQL database aan
- Kopieer `includes/config.example.php` naar `includes/config.php`
- Vul de database gegevens in

4. Importeer de database structuur:
```bash
mysql -u gebruiker -p database_naam < database/setup.sql
```

## 🔧 Configuratie

De volgende API's moeten worden geconfigureerd in `includes/config.php`:
- NewsAPI
- OpenDataAPI
- PoliticalPartyAPI

## 🏗️ Project Structuur

```
PolitiekPraat/
├── controllers/      # Route controllers
├── includes/         # Core functionaliteit en APIs
├── views/           # Frontend templates
├── public/          # Publieke bestanden
├── scripts/         # Helper scripts
└── database/        # Database migraties
```

## 🔒 Beveiliging

- Gebruikersauthenticatie
- XSS bescherming
- CSRF beveiliging
- SQL injectie preventie

## 🤝 Bijdragen

Bijdragen zijn welkom! Voor grote veranderingen, open eerst een issue om te bespreken wat je wilt veranderen.

## 📝 Licentie

Dit project is gelicentieerd onder de MIT License.

## 👥 Team

- [Naoufal Andichi] - Initiële ontwikkelaar

## 📞 Contact

Voor vragen of suggesties, neem contact op via:
- Email: [naoufal.exe@gmail.com]