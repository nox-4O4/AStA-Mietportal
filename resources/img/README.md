# SVG-Dateien und zugehörige Resourcen

Die `*_master.svg`-Dateien sind die ursprünglichen Inkscape-Dateien. Diese werden nicht im Portal referenziert. Aus ihnen wurden die anderen svg-Dateien erzeugt, welche im Portal referenziert werden.

## Produktplatzhalter

* `product-placeholder_master.svg`: die ursprüngliche Inkscape-Datei.
* `product-placeholder.svg`: aus der Inkscape-Datei generiert und **manuell bearbeitet**. Das Bild wird im Mietportal in die HTML-Seite eingebunden.

  Folgend die Schritte zum Erzeugen der eingebundenen Datei:

    * In Inkscape als optimierte SVG-Datei speichern
    * Ausgeblendete Elemente manuell aus dem XML entfernen
    * `<?xml ... ?>`-Tag entfernen (die Datei wird unverändert ins Markup geladen)
    * Sofern enthalten: `width` und `height`-Angaben aus dem `svg`-Element entfernen (damit das Bild standardmäßig auf `100%` des vorhandenen Platzes skaliert wird)
    * `fill="currentColor"` dem Root-Gruppenelement (oder dem SVG-Element) hinzufügen und alle etwaigen weiteren `fill`-Angaben entfernen (dadurch übernimmt das Bild die Textfarbe aus dem HTML-Dokument, ist für Dark-Mode relevant)

## 404 Not Found

* `404-not-found_master.svg`: die Vorlage. Text ist Text (kein Pfad), Font ist erforderlich.
* `404.svg`, `not-found.svg`: aus der Vorlage erstellte und optimierte Dateien:
    * Nicht erforderliche Elemente (Schriftzug bzw. „404“-Text) entfernen
    * Text zu Pfad konvertieren
    * bei `not-found.svg` den Text mittig positionieren (Abstand rechts hinzufügen)
    * als optimiertes SVG exportieren
    * `width` und `height` entfernen
    * `fill="currentColor"` hinzufügen (vgl. Produktplatzhalter)
* `Hey August.otf`: Font für den Schriftzug. Nur erforderlich, wenn man die Vorlage bearbeiten möchte.

## Logos

`logo-asta.svg` wird in der Webanwendung und in den generierten PDFs verwendet. Es ist aus der Vorlage im Drive erstellt, dazu:

* als optimiertes SVG exportieren
* `width` und `height` entfernen
* `fill="currentColor"` hinzufügen (vgl. Produktplatzhalter)

Die `png`-Version wird für die E-Mails verwendet, da SVG-Support in E-Mail-Clients traurig schlecht ist.
