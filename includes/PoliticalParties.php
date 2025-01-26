<?php

class PoliticalParties {
    private $parties = [
        'pvv' => [
            'name' => 'PVV',
            'color' => '#0a2896',
            'website' => 'pvv.nl',
            'seats' => 37,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#0a2896" text-anchor="middle" dominant-baseline="central">PVV</text></svg>'
        ],
        'gl-pvda' => [
            'name' => 'GroenLinks-PvdA',
            'color' => '#8cc63f',
            'website' => 'groenlinks-pvda.nl',
            'seats' => 25,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="40" font-family="Arial Black" font-size="25" fill="#8cc63f" text-anchor="middle">GL</text><text x="50" y="70" font-family="Arial Black" font-size="25" fill="#e31e24" text-anchor="middle">PvdA</text></svg>'
        ],
        'vvd' => [
            'name' => 'VVD',
            'color' => '#ff7404',
            'website' => 'vvd.nl',
            'seats' => 24,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#ff7404" text-anchor="middle" dominant-baseline="central">VVD</text></svg>'
        ],
        'nsc' => [
            'name' => 'NSC',
            'color' => '#123b6d',
            'website' => 'nieuwsociaalcontract.nl',
            'seats' => 20,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#123b6d" text-anchor="middle" dominant-baseline="central">NSC</text></svg>'
        ],
        'd66' => [
            'name' => 'D66',
            'color' => '#00b13c',
            'website' => 'd66.nl',
            'seats' => 9,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#00b13c" text-anchor="middle" dominant-baseline="central">D66</text></svg>'
        ],
        'bbb' => [
            'name' => 'BBB',
            'color' => '#6e9c3c',
            'website' => 'boerburgerbeweging.nl',
            'seats' => 7,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#6e9c3c" text-anchor="middle" dominant-baseline="central">BBB</text></svg>'
        ],
        'cda' => [
            'name' => 'CDA',
            'color' => '#007749',
            'website' => 'cda.nl',
            'seats' => 5,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#007749" text-anchor="middle" dominant-baseline="central">CDA</text></svg>'
        ],
        'sp' => [
            'name' => 'SP',
            'color' => '#ee1f27',
            'website' => 'sp.nl',
            'seats' => 5,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#ee1f27" text-anchor="middle" dominant-baseline="central">SP</text></svg>'
        ],
        'cu' => [
            'name' => 'ChristenUnie',
            'color' => '#00a0dc',
            'website' => 'christenunie.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#00a0dc" text-anchor="middle" dominant-baseline="central">CU</text></svg>'
        ],
        'denk' => [
            'name' => 'DENK',
            'color' => '#009f41',
            'website' => 'bewegingdenk.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#009f41" text-anchor="middle" dominant-baseline="central">DENK</text></svg>'
        ],
        'fvd' => [
            'name' => 'FVD',
            'color' => '#841818',
            'website' => 'fvd.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#841818" text-anchor="middle" dominant-baseline="central">FVD</text></svg>'
        ],
        'pvdd' => [
            'name' => 'Partij voor de Dieren',
            'color' => '#006c2e',
            'website' => 'partijvoordedieren.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="30" fill="#006c2e" text-anchor="middle" dominant-baseline="central">PvdD</text></svg>'
        ],
        'sgp' => [
            'name' => 'SGP',
            'color' => '#254399',
            'website' => 'sgp.nl',
            'seats' => 3,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="45" fill="#254399" text-anchor="middle" dominant-baseline="central">SGP</text></svg>'
        ],
        'volt' => [
            'name' => 'Volt',
            'color' => '#502379',
            'website' => 'voltnederland.org',
            'seats' => 2,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="40" fill="#502379" text-anchor="middle" dominant-baseline="central">VOLT</text></svg>'
        ],
        'ja21' => [
            'name' => 'JA21',
            'color' => '#01557D',
            'website' => 'ja21.nl',
            'seats' => 1,
            'logo' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50" y="50" font-family="Arial Black" font-size="35" fill="#01557D" text-anchor="middle" dominant-baseline="central">JA21</text></svg>'
        ]
    ];

    private $linksePartijen = [
        'GroenLinks-PvdA' => [
            'klimaatbeleid' => 'Voorstander van ambitieus klimaatbeleid met focus op duurzame energie en CO2-reductie. Pleit voor eerlijke verdeling van de kosten van de energietransitie.',
            'woningmarkt' => 'Meer sociale huurwoningen, regulering van de vrije huursector en aanpak van woningspeculatie. Focus op betaalbaar wonen voor iedereen.',
            'economie' => 'Sterkere rol voor de overheid in de economie, hogere belastingen voor grote bedrijven en vermogens, en investeren in publieke voorzieningen.',
            'zorg' => 'Afschaffen marktwerking in de zorg, lagere eigen bijdragen en meer waardering voor zorgpersoneel. Focus op preventie en toegankelijkheid.',
            'onderwijs' => 'Meer investeren in onderwijs, kleinere klassen, hogere salarissen voor leraren en afschaffen leenstelsel. Focus op kansengelijkheid.',
            'arbeidsmarkt' => 'Hoger minimumloon, vaste contracten stimuleren, en betere bescherming voor flexwerkers. Focus op werknemersrechten.',
            'immigratie' => 'Humaan asielbeleid, betere opvang en integratie van vluchtelingen. Focus op inclusieve samenleving en gelijke kansen.',
            'veiligheid' => 'Preventie en aanpak van oorzaken van criminaliteit. Investeren in wijkagenten en sociale veiligheid.',
            'duurzaamheid' => 'Ambitieuze doelen voor circulaire economie, natuurbehoud en duurzame landbouw. Focus op klimaatrechtvaardigheid.'
        ],
        'SP' => [
            'klimaatbeleid' => 'Klimaatmaatregelen moeten eerlijk verdeeld worden, grote vervuilers moeten meer betalen. Focus op betaalbaarheid voor gewone mensen.',
            'woningmarkt' => 'Massaal bouwen van betaalbare woningen, huurprijzen bevriezen en speculanten aanpakken. Wonen is een recht, geen verdienmodel.',
            'economie' => 'Economie moet dienend zijn aan mensen, niet andersom. Herverdeling van welvaart en nationalisering van vitale sectoren.',
            'zorg' => 'Nationaal Zorgfonds zonder eigen risico, zorg in publieke handen en betere arbeidsvoorwaarden in de zorg.',
            'onderwijs' => 'Gratis onderwijs op alle niveaus, kleinere klassen en meer zeggenschap voor docenten en studenten.',
            'arbeidsmarkt' => 'Vast werk moet de norm zijn, minimumloon naar 15 euro en aanpak van doorgeslagen flexibilisering.',
            'immigratie' => 'Eerlijk asielbeleid met aandacht voor draagvlak in de samenleving. Focus op goede integratie en arbeidsparticipatie.',
            'veiligheid' => 'Meer wijkagenten, aanpak van georganiseerde misdaad en versterking van lokale veiligheid.',
            'duurzaamheid' => 'Duurzaamheid moet betaalbaar zijn voor iedereen. Grote vervuilers aanpakken en investeren in groene alternatieven.'
        ],
        'PvdD' => [
            'klimaatbeleid' => 'Radicale systeemverandering nodig voor klimaat en biodiversiteit. Focus op plantaardige economie en natuurherstel.',
            'woningmarkt' => 'Duurzaam en natuurinclusief bouwen, focus op renovatie en transformatie van bestaande gebouwen.',
            'economie' => 'Transitie naar circulaire economie, krimp van vervuilende industrie en stimuleren van duurzame alternatieven.',
            'zorg' => 'Preventieve gezondheidszorg, meer aandacht voor leefstijl en milieufactoren in gezondheid.',
            'onderwijs' => 'Meer aandacht voor duurzaamheid en dierenwelzijn in onderwijs, kleinschalig onderwijs stimuleren.',
            'arbeidsmarkt' => 'Korter werken met behoud van loon, basisinkomen onderzoeken en duurzame banen stimuleren.',
            'immigratie' => 'Humaan vluchtelingenbeleid en aandacht voor klimaatvluchtelingen. Focus op mondiale rechtvaardigheid.',
            'veiligheid' => 'Preventie van milieucriminaliteit en dierenmishandeling. Versterking van handhaving natuurwetgeving.',
            'duurzaamheid' => 'Radicale omslag naar plantaardige economie, natuurherstel en dierenwelzijn centraal stellen.'
        ]
    ];

    private $rechtsePartijen = [
        'VVD' => [
            'klimaatbeleid' => 'Klimaatdoelen halen door innovatie en kernenergie. Focus op haalbaarheid en betaalbaarheid, geen overhaaste maatregelen.',
            'woningmarkt' => 'Sneller en meer bouwen door vermindering regeldruk. Stimuleren eigen woningbezit en ruimte voor commerciële verhuur.',
            'economie' => 'Lage belastingen, minder regels voor ondernemers en gezonde overheidsfinanciën. Focus op economische groei.',
            'zorg' => 'Efficiëntere zorg door marktwerking, eigen verantwoordelijkheid en innovatie. Kritisch op stijgende zorgkosten.',
            'onderwijs' => 'Focus op kwaliteit en excellentie, meer maatwerk in onderwijs en aansluiting op arbeidsmarkt.',
            'arbeidsmarkt' => 'Flexibiliteit op arbeidsmarkt behouden, lagere lasten op arbeid en stimuleren van ondernemerschap.',
            'immigratie' => 'Streng maar rechtvaardig immigratiebeleid. Focus op arbeidsmigratie die bijdraagt aan de economie.',
            'veiligheid' => 'Harde aanpak van criminaliteit, meer bevoegdheden voor politie en justitie, investeren in cybersecurity.',
            'duurzaamheid' => 'Duurzaamheid door innovatie en ondernemerschap. Balans tussen economie en milieu.'
        ],
        'PVV' => [
            'klimaatbeleid' => 'Kritisch op klimaatmaatregelen, geen verdere investeringen in energietransitie. Behoud van fossiele energie.',
            'woningmarkt' => 'Voorrang voor Nederlanders bij woningtoewijzing, geen voorrang voor statushouders.',
            'economie' => 'Lagere belastingen, minder geld naar EU en ontwikkelingshulp. Focus op koopkracht gewone Nederlanders.',
            'zorg' => 'Lagere eigen bijdragen, meer handen aan het bed en behoud van kleinere ziekenhuizen.',
            'onderwijs' => 'Behoud van speciaal onderwijs, focus op kernvakken en Nederlandse cultuur en geschiedenis.',
            'arbeidsmarkt' => 'Bescherming Nederlandse werknemers, aanpak arbeidsmigratie en behoud pensioenstelsel.',
            'immigratie' => 'Stop immigratie uit islamitische landen, streng asielbeleid en focus op remigratie.',
            'veiligheid' => 'Zero tolerance beleid, hogere straffen en meer politie op straat.',
            'duurzaamheid' => 'Kritisch op klimaatmaatregelen en windmolens. Behoud van traditionele industrie.'
        ],
        'BBB' => [
            'klimaatbeleid' => 'Realistische klimaataanpak met oog voor belangen boeren en platteland. Kritisch op te snelle transitie.',
            'woningmarkt' => 'Meer woningbouw in landelijk gebied, behoud van karakter platteland en leefbaarheid dorpen.',
            'economie' => 'Bescherming van boeren en mkb, kritisch op doorgeslagen regelgeving en klimaatmaatregelen.',
            'zorg' => 'Behoud van regionale ziekenhuizen, meer waardering voor zorgpersoneel en menselijke maat in de zorg.',
            'onderwijs' => 'Behoud van scholen in krimpgebieden, meer praktijkgericht onderwijs en waardering vakmanschap.',
            'arbeidsmarkt' => 'Minder regels voor ondernemers, bescherming familiebedrijven en ondersteuning regionale economie.',
            'immigratie' => 'Beperking immigratie, focus op arbeidsmigratie die nodig is voor de arbeidsmarkt.',
            'veiligheid' => 'Meer blauw op straat in landelijk gebied, aanpak ondermijning en drugscriminaliteit.',
            'duurzaamheid' => 'Duurzaamheid met oog voor belangen boeren en platteland. Kritisch op rigoureuze natuurmaatregelen.'
        ]
    ];

    public function getParties() {
        return $this->parties;
    }

    public function getPartyLogos() {
        $logos = [];
        foreach ($this->parties as $slug => $party) {
            $logos[$slug] = 'data:image/svg+xml;base64,' . base64_encode($party['logo']);
        }
        return $logos;
    }

    public function getLinkseStandpunten($thema) {
        $standpunten = [];
        foreach ($this->linksePartijen as $partij => $themas) {
            if (isset($themas[$thema])) {
                $standpunten[$partij] = $themas[$thema];
            }
        }
        return $standpunten;
    }

    public function getRechtseStandpunten($thema) {
        $standpunten = [];
        foreach ($this->rechtsePartijen as $partij => $themas) {
            if (isset($themas[$thema])) {
                $standpunten[$partij] = $themas[$thema];
            }
        }
        return $standpunten;
    }
} 