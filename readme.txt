=== Membro Sportlink API Shortcodes ===
Contributors: Pieter Naber (Membro BV)
Tags: Atletiekunie, atletiek, loopsportverenigingen, NBB, basketball, NHV, handbal, KNBSB, baseball, softball, KNKV, korfbal, reddingsbrigade, KNVB, voetbal, Nevobo, volleybal, KNZB, zwem, zwemmen, waterpolo, synchroonzwemmen, Belgium Hockey, hockey, api, sportlink
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 3.0.1
Tested up to: 6.6
Stable tag: 1.0.0

Verenigingen en clubs in het bezit van een API sleutel voor de Sportlink Dataservice kunnen deze plugin gebruiken om API data te tonen in een Wordpress website.

Deze Sportlink Dataservice wordt aangeboden voor verenigingen die vallen onder de volgende sportbonden:
- Atletiekunie: atletiek- en loopsportverenigingen
- NBB: Nederlandse Basketball Bond
- NHV: Nederlands Handbal Verbond
- KNBSB: Koninklijke Nederlandse Baseball en Softball Bond
- KNKV: Koninklijk Nederlands Korfbalverbond
- Reddingsbrigade: Koninklijke Nederlandse Bond tot het Redden van Drenkelingen
- KNVB: Koninklijke Nederlandse Voetbal Bond
- Nevobo: Nederlandse Volleybalbond
- KNZB: Koninklijke Nederlandse Zwem Bond
- Belgium Hockey: Koninklijke Belgische Hockey Bond

== Description ==
Met deze plugin is het mogelijk om door middel van shortcodes data uit de Sportlink Dataservice API op te halen.

Deze shortcodes worden dan vertaalt naar een tabel in de Wordpress website. Naast alle reguliere API calls, zoals die staan beschreven op [de website van Sportlink](https://sportlinkservices.freshdesk.com/nl/support/solutions/articles/9000062943-toepassen-club-dataservice-en-werken-met-de-javascript-library), zitten er in deze plugin ook een aantal extra features zoals het tonen van het clublogo, filteren op alleen thuiswedstrijden, filteren op wedstrijden of op de volgende/vorige en huidige week.

Voor meer informatie over het gebruik van de verschillende shortcodes, zie onze [handleiding](https://membro.nl/uitleg/sportlink-gegevens-op-je-wordpress-website)

Hier volgt een lijst van de verschillende soorten shortcodes die beschikbaar zijn binnen de plugin:

* Lijst tonen van alle teams
* Lijst tonen met alle wedstrijden
* Lijst tonen met alle competities
* Lijst tonen met de uitslagen per team
* Lijst tonen met het programma per team
* Lijst tonen met de stand van een team
* Lijst tonen met de uitslagen van een competitie
* Lijst tonen met het programma van een competitie
* Lijst met de standen binnen een competitie
* Details van één specifieke wedstijd

Naast de bovenstaande reguliere shortcodes, kunt u per shortcode extra parameters toevoegen die zorgen extra filtering of sortering:

* Toon club logo bij de teams
* Sorteer wedstrijden op uit en thuis wedstrijden
* Toon enkel de thuis wedstrijden
* Toon de wedstrijden van de huidige, vorige, volgende week, een bepaald week nummer of van alle weken.
* Toon enkel de vriendschappelijke, reguliere of beker wedstrijden
* Toon enkel de wedstrijden binnen een bepaalde poule
* Toon enkel de veld of zaal wedstrijden
* Toon enkel de wedstrijden van de 1ste, 2e, 3e of 4e periode van het seizoen.

== Installation ==
= Automatisch installeren: =

1. Log via uw browser in op de backend van jouw Wordpress website.
2. Kies voor het menu item `Plugins`.
3. Klik op de knop `Nieuwe plugin toevoegen`.
4. Zoek op `Membro Sportlink API Shortcodes`.
5. Klik op `Nu installeren`.

= Handmatig installeren: =

1. Download de plugin op https://wordpress.org/plugins/membro-sportlink-api-shortcodes, er word een .zip bestand naar jouw computer gedownload.
2. Log via uw browser in op de backend van jouw Wordpress website.
3. Kies voor het menu item `Plugins`.
4. Klik op de knop `Nieuwe plugin toevoegen`.
5. Klik op de knop `Plugin uploaden`.
6. Selecteer het net gedownloade .zip bestand vanaf jouw computer.
7. Klik op `Nu installeren`.
8. Activeer de `Membro Sportlink API Shortcodes` plugin.

= Instellen: =

1. Log via uw browser in op de backend van uw Wordpress website.
2. Ga naar `Instellingen - Sportlink API`.
3. Geef de API sleutel in en de pathnaam van jouw club in. Als je het veld cache niet invult of op *0* zet dan zal de plugin geen data cachen.
4. Sla de instellingen op.
5. Aan de indicatie lampjes kunt je zien wat de status van API is en of je verbonden bent.
6. Via de verschillende tabs kunt je alle beschikbare shortcodes zien. Deze kunt je simpelweg kopiëren en plakken in een webpagina om de data zichtbaar te maken op jouw website.

== Screenshots ==

1. Instellingen scherm
2. Algemene shortcodes
3. Extra parameters

== Frequently Asked Questions ==
= Hoe stel ik de plugin in? =

* Log via jouw browser in op de backend van jouw Wordpress website.
* Ga naar `Instellingen - Sportlink API`.
* Geef de API sleutel in en de pathnaam van jouw club in. Als je het veld cache niet invult of op *0* zet dan zal de plugin geen data cachen.
* Sla de instellingen op.
* Aan de indicatie lampjes kunt je zien wat de status van API is en of je verbonden bent.
* Via de verschillende tabs kunt je alle beschikbare shortcodes zien. Deze kunt je simpelweg kopiëren en plakken in een webpagina om de data zichtbaar te maken op jouw website.

= Waarom zie ik zie geen text/content op de verschillende tabbladen van het instellingen menu van de plugin? =

* De cache map moet 777 rechten hebben. De cache map word normaal gesproken zelf door de plugin aangemaakt maar als je geen text/content ziet op de verschillende tab bladen van het instellingen scherm van de plugin dan moet je deze rechten wellicht manueel toekennen. Op een Linux server doet je dat als volgt:
* Log via ftp of ssh in op de server waar jouw Wordpress website staat.
* Navigeer naar de uploads map van de wordpress installatie ( b.v. `cd /var/www/html/wordpress/wp-content/uploads`).
* Controleer of er, in de uploads map, een map bestaat die `membro-sportlink-api-shortcodes` heet en of er in de shortcodes map een map staat de `cache` heet (`/uploads/shortcodes-membro-sportlink-api/cache`). Bestaat één van beide mappen niet dan moeten deze mappen aangemaakt worden en de 777 rechten aan de cache map worden toegekend. Dit kan allemaal via één command: `mkdir -p -m 777 membro-sportlink-api-shortcodes/cache`.
* Staan zowel de `membro-sportlink-api-shortcodes` map in de `uploads` map als de `cache` map in de shortcodes map, dan moeten alleen de juiste rechten aan de cache map worden toegekend d.m.v. het volgende command `chmod 777 membro-sportlink-api-shortcodes/cache`.
* Refresh het instellingen scherm van de plugin in jouw browser en controleer of er text/content in de verschillende tabbladen staat.

== Changelog ==
= 1.0.0 =
Release date: Jul 18, 2024

Feature Improvements:

* First release