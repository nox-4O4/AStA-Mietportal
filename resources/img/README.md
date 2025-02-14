Die Datei `product-placeholder.svg` ist die ursprüngliche Inkscape-Datei, mit der das Bild erzeugt wurde. Sie wird nicht durch das Mietportal referenziert.

Die Datei `product-placeholder-opt.svg` wurde aus der Inkscape-Datei generiert und daraufhin **manuell bearbeitet**. Diese wird im Mietportal in die HTML-Seite eingebunden.

Folgend die Schritte zum Erzeugen der Datei:

* In Inkscape als optimierte SVG-Datei speichern
* Ausgeblendete Elemente manuell aus dem XML entfernen
* `<?xml ... ?>`-Tag entfernen (die Datei wird unverändert ins Markup geladen)
* Sofern enthalten: `width` und `height`-Angaben aus dem `svg`-Element entfernen (damit das Bild standardmäßig auf `100%` des vorhandenen Platzes skaliert wird)
* `fill="currentColor"` dem Root-Gruppenelement (oder dem SVG-Element) hinzufügen und alle etwaigen weiteren `fill`-Angaben entfernen (dadurch übernimmt das Bild die Textfarbe aus dem HTML-Dokument, ist für Dark-Mode relevant)
