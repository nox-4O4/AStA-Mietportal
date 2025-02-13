# AStA Mietportal

Das Mietportal für den AStA HKA.

## Deployment

### Initial

* Source auschecken
* `.env.example-prod` zu `.env` kopieren und Konfiguration anpassen
* Composer-Pakete installieren: `composer install`
* Frontend-Assets generieren: `npm ci && npm run build`
* Datenbank initialisieren `php artisan migrate --seed --force`
* Verzeichnis für Bilduploads verknüpfen: `php artisan storage:link`
* Cronjob einrichten: `* * * * * php artisan schedule:run`

### Aktualisierung

...

### Wartungsmodus

* Zum Aktivieren: `php artisan down`
* Zum Deaktivieren: `php artisan up`

Weitere Optionen sind hier beschrieben: https://laravel.com/docs/11.x/configuration#maintenance-mode

## Entwicklung

* Lokale Datenbank (MariaDB, MySQL) muss vorhanden sein
* Anleitung fürs initiale Deployment befolgen
* Zum Ausführen und Debuggen bevorzugt einen lokalen Apache-Webserver nutzen. Quick & Dirty-Alternative: `php artisan serve` (nutzt den PHP-Build-In Webserver)

### Assets generieren

Die Dateien unter `public/build` werden mit Vite generiert. Die Quelldateien befinden sich im `resources`-Ordner.

Assets während der Entwicklung mit Hot-Reload-Support generieren: `npm run dev`

Bei manchen Änderungen an den Style-Definitionen (bspw. neue SASS-generierte Bootstrap-Utility-Klassen) ist es erforderlich, mit `npm run build` die statischen Assets neu zu generieren, damit die IDE die Klassen vorschlagen kann.
