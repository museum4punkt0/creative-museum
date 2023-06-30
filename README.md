# Kurzbeschreibung #
Creative Museum ist die neue Partizipationsplattform des Badischen Landesmuseums. Angelenht an die sozialen Medien bietet es einen digitalen Raum für Austausch und Debatte. Museumsmitarbeiter:innen, Expert:innen und alle Interessierten können sich treffen, diskutieren und in thematischen Feeds eigene Inhalte beisteuern. Verhandelt werden die Themen in zeitlich begrenzten Kampagnen, die mit einer Frage beginnen und flexibel gestaltet werden können. Gamification-Elemente zielen auf maximale Beteiligung, fördern nutzer:innengenerierte Inhalte und die gemeinsame Sammlung von Ideen. Zu Hause auf dem Sofa, unterwegs und an jedem beliebigen Ort können Interessierte einsteigen und direkt mitdiskutieren.

# Entstehungskontext # 
Die Progressive-Web-App Creative Museum auf Basis des Symphony Frameworks ist entstanden im Verbundprojekt museum4punkt0 – Digitale Strategien für das Museum der Zukunft, Teilprojekt: museum x.o des Badischen Landesmuseums Karlsruhe.

# Förderung #
Das Projekt museum4punkt0 wird gefördert durch die Beauftragte der Bundesregierung für Kultur und Medien aufgrund eines Beschlusses des Deutschen Bundestages. Weitere Informationen: www.museum4punkt0.de

Weitere Informationen:\
www.museum4punkt0.de

![alt text](https://github.com/museum4punkt0/media_storage/blob/2c46af6cb625a2560f39b01ecb8c4c360733811c/BKM_Fz_2017_Web_de.gif) ![alt text](https://github.com/museum4punkt0/media_storage/blob/e87f37973c3d91e2762d74d51bed81de5026e06e/BKM_Neustart_Kultur_Wortmarke_pos_RGB_RZ_web.jpg)

# Technische Dokumentation der Programmierung #

## Kampagnen ##
Jeder von Nutzer:innen generierte Inhalt im Creative Museum ist in thematischen Kampagnen organisiert. So können durch die Redaktion Themenräume zu aktuellen Veranstaltungen oder gesellschaftlichen Diskursen geöffnet werden.
- Kampagnen-Menü: Auf der Startseite können Nutzer:innen über Wischgesten einen Stapel mit verschiedenen Kampagnenkarten steuern. Jede Kampagne zeigt ein Start- und Enddatum, Titel, Beschreibung und Logos von Partnerinstitutionen der Kampagne an. Es können sowohl aktive, als auch archivierte Kampagnen angezeigt werden. Die oberste Karte ermöglicht per Tap oder Klick das Öffnen des Kampagnenfeeds.
- Kampagnen-Feed: Über den Feed sind alle User-Interaktionen zugänglich. Registrierte und angemeldete User können über ein Text-, Bild-, Umfrage-, Audio-, Video- und Sammlungs-Modul Inhalte posten und alle Inhalte über ein Voting-, Reaktions-, und Kommentar-Modul bewerten oder kommentieren. Inhalte können zusätzliche in Sammlungen gespeichert werden und als solche zusammen mit anderen als eigenes Content-Modul gepostet werden.

## Content-Module ##
Alle Content-Module sind mit begrenzter Inhaltslänge verfügbar. Jedes Modul kann unterschiedliche Pflichtfelder haben. Bild- und Video-Module verfügen über einen Alternativtextfeld.
- Text-Modul: Inhalte über das Text-Modul können als formatierter Text mit begrenzter Zeichenanzahl und Titel im Feed angezeigt werden.
- Bild-Modul: Bild-Inhalte können formatunabhängig verwendet werden. Die angezeigte Größe richtet sich nach der Bildbreite.
- Umfrage-Modul: Für das Umfrage-Modul sind, neben Frage und Beschreibungstext, zwischen zwei und fünf Antwort-Vorgaben möglich. Nach der Auswahl einer Option wird der Zwischenstand der Umfrage angezeigt.
- Audio-Modul: Audio-Inhalte können als Datei in den Feed geladen oder direkt aufgenommen werden. Optional kann dem Audio-Inhalt ein Bild/Cover hinzugefügt werden.
- Video-Modul: Video-Inhalte können aus der Medien-Bibliothek des Smartphones, dem Datei-Browser ausgewählt oder direkt aufgenommen werden.
- Sammlung-Modul: Über die Beitragsoptionen eines jeden Posts kann jeder Inhaltstyp zu Sammlungen hinzugefügt werden. Nutzer:innen können so Beiträge von verschiedenen anderen Nutzer:innen und von sich selbst zu einem selbstgewählten Thema zusammentragen. Voneinander unabhängig kreierte Inhalte erhalten so eine neue Bedeutung. 

## Interaktionsmodule ##
Neben eigenen Inhalten, können Nutzer:innen auf die Inhalte anderer reagieren. Inhalte können nach ihrer Relevanz bewertet, mit einer Reaktion oder mit Text kommentiert werden. Über die Sortieren-Funktionen können die einnzelnen Bewertungskriterien ab- oder aufsteigend sortiert werden.
- Relevanz-Modul: Inhalte können über ein hoch/runter Icon nach Relevanz bewertet werden. So können Nutzer:innen Einfluss auf alle Inhalte nehmen. Beiträge der Redaktion werden so einer ehrlichen Prüfung der Community unterzogen. Der kuratorische Einfluss wird aufgehoben.
- Kommentar-Modul: Nutzer:innen können Inhalte mit Text und Emojis kommentieren. So kann ein freier Diskurs über die Beiträge entstehen und niedrigschwellige Feedbacks wie Relevanz-Voting und Reaktionen differenziert werden.
- Reaktion-Modul: Die Redaktion kann fünf vorgegebene direkte Reaktionen einstellen. Nutzer:innen können so mit einem Tap oder Klick einen Beitrag inhaltlich bewerten. Die vorgegebenen Reaktionen können sich inhaltlich auf das Kampagnenthema beziehen und bilden so eine Klammer zwischen Kampagnenthema und Funktionen.

## Game-Engine ## 
Alle Aktionen der User:innen im Creative Museum generieren Punkte, die den spielerischen Wettbewerb innerhalb der Kampagnen entscheiden. Am Ende einer Kampagne entscheiden die verschenkten Awards an andere Nutzer:innen.
- Punkte: Über das CMS-Plugin kann die Redaktion Punktelevel für die zu erreichenden Badges und Awards einstellen. Dadurch lässt sich die Dramaturgie auf die Laufzeit einer Kampagne anpassen.
- Badges: Titel und Icons von Badges und Awards lassen sich im CMS-Plugin einstellen. Je nach Thematik kann die Redaktion hierdurch die Tonalität der Kampagne bestimmen. Ob witzig oder ernst framen Titel und Icons der Badges und Awards die kommunikative Richtung einer Kampagne. Badges fungieren als Meilensteine der Beteiligung von Nutzer:innen. Der zuletzt erreichte Badge wird als Status neben dem Nutzer:innennamen im Profil angezeigt. Der Status mit höheren Badge-Levels motiviert andere Nutzer:innen zur stärkeren Beteiligung.
- Awards: Höhere Punktelevels schalten Awards frei. Awards können dann durch Nutzer:innen an andere Nutzer:innen verschenkt werden. Je nach Titel des Awards, können damit Komplimente verteilt werden, aber auch Kritik geäußert werden.
- Score: Punkte sind die Währung im Creative Museum. Die Punkte sind mit den Accounts der Nutzer:innen verknüpft. Die Punkte können so perspektivisch mit realen Angeboten einer Institution verknüpft werden. Z.B. als Zahlungsmittel für Shop, Onlineshop, Ticketoffice oder exklusive Zusatzfeatures in Ausstellungen freischalten.

## Nutzer:innenprofil ##
tbc

# Install instructions #

## Requirements ##
- [Git](https://git-scm.com/) - Distributed version-control system for tracking
  changes in source code during software development.
- [Git LFS](https://git-lfs.github.com/) - Git LFS (Large
  File Storage) is a Git extension, that reduces the impact of large files in
  a repository by downloading the relevant versions of them lazily.
- [Composer](https://getcomposer.org/) - Dependency manager for the PHP
  programming language.
- [DDEV](https://github.com/drud/ddev) - Open source tool that makes it simple
  to get local PHP development environments up and running in minutes.
- Docker Environment
  - [Docker](https://www.docker.com/products/docker-desktop) - Docker Desktop is a
    tool for MacOS and Windows machines for the building and sharing of
    containerized applications and microservices.
  - [Colima](https://github.com/abiosoft/colima) - Colima is a Docker Desktop alternative which brings aproximately 10 times better performance (Install HEAD for best performance ```brew unlink colima && brew install --HEAD colima```)

## Clone ##
Clone from remote repository into your local directory:
```
git clone git@bitbucket.org:jwied/creative-museum.git
```

## DDEV start ##
After cloning you have to start your *DDEV* services (usually *web* and *db*).
Navigate to your project root directory and run the following command:
```
ddev start
```

## Install dependencies ##
After the project was started successfully you will have to install all required
dependencies via *npm*. Navigate to the root directory of your project and
execute the following command:

[Backend](https://backend.creative-museum.ddev.site)
```
ddev backend-install
```

[Frontend](https://creative-museum.ddev.site)
```
ddev frontend-install
```

[TYPO3](https://typo3.creative-museum.ddev.site/typo3)
```
ddev typo3-install
ddev import-db --target-db=typo3 --src=data/typo3.sql.gz
```
### Frontend Watcher ###
```
ddev frontend-dev
```

# Benutzung #
1. Start Screen: Kampagnenauswahl und Öffnen des Feeds
2. Einführung in die Kampagne: Der/Die Nutzer:in liest den Einführungstext zur Kampagne und versteht den thematischen Rahmen.
3. Content-Module: Der/Die Nutzer:in sieht die unterschiedlichen Beitragsarten und vertieft sich in einzelne Beiträge.
4. Onboarding I: Alle Inhalte sind frei zugänglich. Der/Die Nutzer:in möchte sich zu einem Beitrag verhalten. Durch Tap oder anklicken eines Interaktionsmoduls wird das Login-Overlay mit der Aufforderung zur Registrierung angezeigt.
5. Onboarding II: Registrierung des Nutzer:innenaccounts per Double Opt-in oder Social Media Login und Reward durch Login-Strike mit erstem Punktegewinn.
6. Onboarding III: In den vier Onboarding-Overlays wird die grundsätzliche Funktionsweise der App beschrieben.
7. Content-Beitrag oder Feedback: Der/die Nutzer:in entscheidet sich, die vorgegebenen Feedback-Funktionen zu nutzen oder trägt selbst Inhalte zum Feed bei. Jede Aktion bringt Punkte.
8. Nutzer:innenprofile: Der/Die Nutzer:in sieht sich Profile andere Nutzer:innen an und erhält einen Überblick über die unterschiedlichen Fortschritte im Creative Museum.
9. Onboarding IV: Gewinn des ersten Badges und Aufforderung weitere Punkte durch Interaktion zu erhalten, um höhere Fortschritte freizuschalten.
10. Sammlung-Modul: Ansehen der hervorgehobenen Sammlung-Beiträge im Feed. Erkennen der Funktion und Anlegen einer eigenen Sammlung.
11. Notifications und e-Mails: Der/Die Nutzer:in erhält in seiner/ihrer Abwesenheit eine E-Mail mit der Benachrichtigung, dass er/sie einen Award durch einen/eine anderen/andere Nutzer:in erhalten hat. Er/Sie kehrt über die Web-App ins Creative Museum zurück und sieht sich den Award und den/die Nutzer:in an.
12. Award-Vergabe: Durch weitere Interaktion wird ein erster Award zur eigenen Vergabe freigeschaltet. Der/Die Nutzer:in vergibt ihn an einen/eine anderen/andere Nutzer:in und erhält dafür weitere Punkte. 
13. Ein Motivationskreislauf von vergebenen und erhaltenen Awards, Punkten, Kommentaren, Votings und Reaktionen entsteht. 
14. Finale einer Kampagne: Der/Die Nutzer:in erhält eine E-Mail und In-App Notification durch die Redaktion, dass die Kampagne bald endet und wird aufgefordert, noch nicht vergeben Awards zu verschenken.
15. Abschluss einer Kampagne: Per E-Mail und In-App Notification wird das Ende einer Kampagne verkündet. Der/Die Nutzer:in öffnet den Feed, um das finale Kampagnenranking anzusehen. Beiträge sind nicht mehr möglich. Der/Die Nutzer:in kann weiterhin alle Inhalte ansehen und filtern oder sortieren.
16. Neue Kampagne: Der/Die Nutzer:in wird per auf eine neue Kampagne aufmerksam gemacht und sieht sich die Beschreibung an, um seinen/ihren Beitrag zu verfassen oder andere zu kommentieren.
17. Nutzer:innenprofil: Das Profil ist über die obere Bedienleiste immer erreichbar und bietet folgende Funktionen: Namen eintragen/ändern, Bild/Avatar aufnehmen oder hochladen, Persönliche Daten eintragen/ändern, Persönliche Benachrichtigungen (Erhaltene Kommentare, Awards und Badges, gemeldete Beiträge) aktivieren/deaktivieren, Creative Museum Benachrichtigungen (Neue Kampagne verfügbar, Kampagne beendet) aktivieren/deaktivieren, Profil löschen.
18. Hauptmenü: Das Profil ist über die obere Bedienleiste immer erreichbar und bietet folgende Funktionen: Profil (Einstellungen ansehen/ändern, Profil suchen, Freunde einladen), Creative Museum (Über Creative Museum lesen, Erste Schritte ansehen, FAQ lesen), Sprachauswahl einstellen (Deutsch/Englisch), Leichte Sprache Text lesen, DGS Video ansehen.

# Credits #
Auftraggeberin/Rechteinhaberin: Badisches Landesmuseum Karlsruhe
Urheber:innen: Christiane Lindner, Johannes Bernhardt, anschlaege.de, jwied

# Markenname # 
„Creative Museum“ ist eine Marke des Badischen Landesmuseums Karlsruhe. Die Marke kann von anderen gemeinnützigen Körperschaften für Software genutzt werden, die auf dem veröffentlichten Code (Repositorium) basiert. In diesem Fall wird um Angabe des folgenden Nachweises gebeten:

„Creative Museum“ ist eine Marke des Badischen Landesmuseums Karlsruhe."

# Lizenz # 
Creative Museum Copyright © 2023 Badisches Landesmuseum Karlsruhe; entwickelt von Badisches Landesmuseum Karlsruhe, Christiane Lindner, Johannes Bernhardt, anschlaege.de und jwied, im Rahmen des Verbundprojekts museum4punkt0.
In allen Referenzen auf die Software muss das folgende Copyright genannt werden:
© 2023 Badisches Landesmuseum Karlsruhe; entwickelt von Badisches Landesmuseum Karlsruhe, Christiane Lindner, Johannes Bernhardt, anschlaege.de und jwied, im Rahmen des Verbundprojekts museum4punkt0.
Alle Codeteile sind unter der MIT-Lizenz veröffentlicht.
