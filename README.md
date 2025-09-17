# AStA Mietportal

Dies ist das Mietportal für den AStA HKA. Es ist eine auf [Laravel](https://laravel.com/docs) und [Livewire](https://livewire.laravel.com/) basierte [PHP](https://www.php.net/)-Anwendung.

## Requirements

- **PHP 8.4** mit den standardmäßig aktiven Extensions sowie den folgenden, nicht-standardmäßig aktiven Extensions:
  - `ext-intl`
  - `ext-pdo` und dem entsprechenden Datenbanktreiber (bspw. `pdo_mysql` für MySQL)
  - `ext-mbstring`
  - `ext-openssl`
  - `ext-fileinfo` (nur auf Windows nicht standardmäßig aktiv)

  Im Zweifelsfall wird bei fehlenden Extensions der Composer-Befehl beim initialen Setup mit einer aussagekräftigen Fehlermeldung fehlschlagen.

- Ein **Webserver**, bspw. Apache oder Nginx

  Für die lokale Entwicklung kann auch der PHP-Build-In-Webserver genutzt werden (vgl. Entwicklungsanleitung)

- **Datenbank**, bspw. MariaDB 10.6+ oder MySQL 8.0+

- **Node.js** zum generieren der Assets

  Die Assets können im Zweifelsfall auch lokal generiert und daraufhin auf das Produktivsystem geladen werden.

## Deployment

### Initiales Produktiv-Deployment

* Source auschecken
* `.env.example-prod` zu `.env` kopieren und Konfiguration anpassen. Die `.env`-Datei enthält die lokale Konfiguration.
* Composer-Pakete installieren: `composer install`
* Frontend-Assets generieren: `npm ci && npm run build`
* Datenbank initialisieren `php artisan migrate --seed --force`
* Verzeichnis für Bilduploads verknüpfen: `php artisan storage:link`  
  In `chroot`ed-Umgebungen, etwa bei Shared-Hosting-Systemen, kann es sein, dass der Link nicht korrekt gesetzt wird. In diesem Fall kann auch ein relativer Link manuell hinterlegt werden: dazu muss `public/storage` auf `../storage/app/public` zeigen.
* Cronjob einrichten: `* * * * * php artisan schedule:run`
* Konfiguration u. Ä. cachen: `php artisan optimize --except config`  
  (Die Konfiguration wird vom Cache ausgeschlossen, da dies in `chroot`ed-Umgebungen, etwa bei Shared-Hosting-Setups, zu Problemen bei absoluten Pfaden führen kann. Bei Problemen kann der Konfigurations-Cache mit `php artisan config:clear` wieder gelöscht werden.)
* Initialen Benutzer anlegen: `php artisan user:create`

### Aktualisierung

Vor einem Update am besten immer ein Backup (Datenbank und Dateisystem) machen.

Zukünftig werden die Befehle zusammengefasst werden. Bis dahin hier die einzelnen Schritte:

* Code-Änderungen laden, sodass Diffs betrachtet werden können, ohne, dass dabei bereits Dateien geändert worden sind:  
  `git remote update`
* Diff der `.env.example`-Datei betrachten und `.env`-Datei entsprechend anpassen:  
  `git diff HEAD..origin/master -- .env.example-prod`
* Wartungsmodus aktivieren: `artisan down`
* Neuen Code auschecken: `git pull`
* Composer-Pakete installieren: `composer install`
* npm-Pakete installieren: `npm install`
* Statische Assets generieren: `npm run build`
* Datenbankmigrationen vornehmen und Seeder ausführen: `php artisan migrate --seed --force`
* Anwendungscache löschen: `php artisan cache:clear`
* Durch den `optimize`-Befehl gecachte Daten löschen: `php artisan optimize:clear`
* Konfiguration u. Ä. cachen: `php artisan optimize --except config`
* Wartungsmodus deaktivieren: `artisan up`
* Testen, dass alles funktioniert.


### Wartungsmodus

* Zum Aktivieren: `php artisan down`
* Zum Deaktivieren: `php artisan up`

Weitere Optionen sind hier beschrieben: https://laravel.com/docs/11.x/configuration#maintenance-mode

## Entwicklung

Zukünftig wird eine Docker-Umgebung zur Entwicklung bereitgestellt werden.

Ohne Docker geht es folgendermaßen:

* Lokale Datenbank (MariaDB oder MySQL) muss vorhanden sein
* Anleitung fürs initiale Deployment befolgen, dabei Cache für Konfiguration u. Ä. weglassen oder danach `php artisan optimize:clear` ausführen
* Zum Ausführen und Debuggen bevorzugt einen lokalen Apache-Webserver nutzen. Quick & Dirty-Alternative: `php artisan serve` (nutzt den PHP-Build-In-Webserver)
* Zum Debuggen sollte [Xdebug](https://xdebug.org/) lokal installiert und konfiguriert sein.

Empfohlene IDE: [PhpStorm](https://www.jetbrains.com/de-de/phpstorm/) zusammen mit dem [Laravel-Idea-Plugin](https://laravel-idea.com/) (beides für Studenten kostenlos).


### Assets generieren

Die Dateien unter `public/build` werden mit Vite generiert. Die Quelldateien befinden sich im `resources`-Ordner.

Assets während der Entwicklung mit Hot-Reload-Support generieren: `npm run dev`

Bei manchen Änderungen an den Style-Definitionen (bspw. neue SASS-generierte Bootstrap-Utility-Klassen) ist es erforderlich, mit `npm run build` die statischen Assets neu zu generieren, damit die IDE die Klassen vorschlagen kann.
