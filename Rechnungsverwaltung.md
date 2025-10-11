# Komplexe Rechnungsverwaltung

Idee / Use-Case: es soll möglich sein, Bestellungen auch nach dem Erstellen und Versand einer Rechnung zu bearbeiten (bspw. zur Fehlerkorrektur oder wenn Kunden vorab eine Rechnung haben möchten, sich im Laufe der Vermietung jedoch noch etwas ändert).

Dabei müssen rechtliche Anforderungen (bspw. hinsichtlich stornierter Rechnungen) berücksichtigt werden. So muss etwa eine Bestellung, zu der bereits eine Rechnung versendet wurde und die daraufhin bearbeitet wird, erfordern, dass eine aktualisierte Rechnung versendet wird.

## Stornierte Rechnungen

Eine Rechnung wird storniert, indem eine Rechnung über den negativen Betrag mit Referenz auf die ursprüngliche Rechnung ausgestellt wird.  
Eine Rechnung wird „geändert“, indem die ursprüngliche Rechnung storniert und eine neue Rechnung ausgestellt wird.

Soll also eine Rechnung r12345v1 über 100 € auf 80 € „geändert“ werden, wird dazu erst eine Rechnung r12345v1-storno über -100 € sowie eine Rechnung r12345v2 über 80 € ausgestellt.

## UI-Elemente

Existierende Rechnungen sowie Storno-Rechnungen werden in einer Liste aufgeführt, aus welcher heraus sie sich

- einsehen lassen
- sowie, falls es sich um die aktuelle Rechnung handelt
    - per Mail versenden lassen, wobei ein ausstehendes Rechnungsstorno ebenfalls mitgeschickt wird,
    - stornieren lassen, sofern die Rechnung nicht storniert ist.

Ferner existieren Buttons

- zum Erzeugen einer Rechnungsvorschau (entspricht dem Aussehen einer Rechnung, Wasserzeichen „Vorschau“ o. Ä., wird bei Erstellung nicht persistiert, enthält keine E-Rechnungs-Metadaten)
- sowie zum Erzeugen einer aktuellen Rechnung, wobei eine etwaige alte Rechnung storniert wird,

sofern derzeit keine aktuelle Rechnung existiert und die Bestellung noch nicht abgeschlossen oder storniert ist.

Ein ausstehendes Rechnungsstorno bezeichnet ein noch nicht verschicktes Rechnungsstorno zu einer Rechnung, die bereits versendet und danach storniert worden ist.

## Aktualität von Rechnungen

Eine Rechnung ist dann aktuell, wenn sie nicht storniert ist und die rechnungsrelevanten Daten der Bestellung mit jenen in der Rechnung übereinstimmen.

Das sind

- Rechnungsempfänger (Name, Anschrift)
- Berechnete Gegenstände (`orderItem`)
    - Art des Gegenstands (`item`)
    - Anzahl
    - Zeitraum
    - Ursprünglicher Betrag
    - Betrag
- Rabattierung
- Veranstaltungsname / Verwendungszweck (wird auf der Rechnung abgedruckt und ist mitunter Grundlage zur Einordnung als (nicht) rabattierfähige Vermietung)

Um die Aktualität feststellen zu können, ohne diese Daten für jede Rechnung separate speichern zu müssen, wird ein Hash-Wert der rechnungsrelevanten Daten gebildet und mit der Rechnung gespeichert.

Eine aktuelle Rechnung kann nur die zuletzt ausgestellte Rechnung sein. (Alle anderen Rechnungen müssten in dem Fall ohnehin storniert sein.)

### Beispiel

Unbeachtet der Stornierung würde also auch in folgendem Szenario am Ende keine aktuelle Rechnung existieren:

- Es wird eine neue Rechnung zu einer Bestellung ausgestellt (das ist die aktuelle Rechnung)
- Die Bestellung wird verändert, es wird eine neue Rechnung ausgestellt (dadurch wird auch die alte Rechnung storniert)
- Die Änderung an der Bestellung wird rückgängig gemacht

Dadurch stimmt der Hashwert der ursprünglichen Rechnung mit der Bestellung überein. Da jedoch zwischenzeitlich eine neue Rechnung ausgestellt worden ist, verfügt die Bestellung dennoch nicht über eine aktuelle Rechnung (zumal die ursprüngliche Rechnung ohnehin storniert worden ist).

## Versandpflicht von Rechnungen

Eine Rechnung muss grundsätzlich ausgestellt und versendet werden, wenn der Rechnungsbetrag nicht Null ist.  
Wird eine Rechnung, die versendet worden ist, storniert, muss die Storno-Rechnung versendet werden.

Der Versandpflicht wird nachgekommen, indem der Versand für das Abschließen einer Bestellung vorausgesetzt wird.  
„Abschließen“ bedeutet, den Bestellstatus auf „Abgeschlossen“ oder „Storniert“ zu setzen.

## Aufbewahrungs- und Archivpflichten

Die GoBD stellt einige Anforderungen an die Aufbewahrung von Rechnungen. Rechnungen dürfen etwa nicht vor Ablauf einer mehrjährigen Frist gelöscht und müssen in der Zeit revissionssicher gespeichert werden.

Revisionssicherheit im Mietportal selbst umzusetzen ist technisch nicht viabel. Stattdessen wird die Möglichkeit geschaffen, alle Rechnungen in einem filterbaren Zeitraum als ZIP-Datei o. Ä. herunterzuladen, sodass diese daraufhin in einem revisionssicheren Speicher (bspw. Lexware-Cloud oder gebrannt auf eine CD) abgelegt werden können.

Zugriff auf diesen Bereich erfolgt über eine neue Benutzerrolle („Buchhaltung“).

Rechnungen sowie ihre Dateien werden beim Löschen von Bestellungen beibehalten. Damit sichergestellt ist, dass sich eine Rechnung jederzeit stornieren lässt, selbst, wenn die zugehörige Bestellung nicht mehr existiert, wird für jede Rechnung unabhängig von der Bestellung eine Referenz auf den Besteller (`customer`) gespeichert. Zum Stornieren ist ausschließlich der Besteller sowie die ursprüngliche Rechnung erforderlich, die Artikel müssen nicht separat aufgelistet werden. (Eine Rechnung darf aus mehreren Dokumenten bestehen, vgl. [§ 31 I UStDV](https://www.gesetze-im-internet.de/ustdv_1980/__31.html).)


## Technische Umsetzung

- Zu jeder Rechnung wird gespeichert, ob sie bereits versendet worden, ob sie storniert und ob die stornierte Rechnung versendet worden ist.  
  Flags: `notified`, `cancelled`, `cancellationNotified`

- Zu einer Bestellung wird gespeichert, ob das Ausstellen einer neuen Rechnung erforderlich ist  
  Flag: `invoiceRequired`  
  Der Wert dieses Flags ergibt sich wie folgt:
    ```
    `invoiceRequired` ist genau dann 1, wenn 
         die Bestellung nicht storniert ist UND (
             der Gesamtbetrag der Bestellung nicht Null ist UND 
             keine aktuelle Rechnung existiert 
             ODER 
             der Gesamtbetrag der Bestellung Null ist UND 
             eine nicht-aktuelle nicht-stornierte Rechnung existiert (d. h. der Hash stimmt nicht mit der Bestellung überein)
         ).
    ```
  Der Wert des Flags wird nur aktualisiert
    - bei Änderungen an rechnungsrelevanten Daten,
    - beim Stornieren der Bestellung,
    - beim Aufheben der Stornierung der Bestellung.

- Sonderfall: Bestellungen ohne Artikel  
  Zu einer Bestellung ohne Artikel kann keine Rechnung erzeugt werden. (Eine Rechnung wäre in dem Fall auch bedeutungslos.) Falls zu einer Bestellung mit einem Betrag != 0 eine Rechnung erzeugt wird und daraufhin alle Artikel entfernt werden, muss die Rechnung dennoch storniert (und die Storno-Rechnung ggf. versendet) werden.  
  Das `invoiceRequired`-Flag ist in diesem Fall ebenfalls `1`. Ein Stornieren der veralteten Rechnung führt dazu, dass es wieder auf `0` gesetzt wird. Ein Erzeugen einer neuen Rechnung ist nicht möglich.

### Zustandsübergänge und Anforderungen

- Stornierung einer Rechnung: wurde eine versendete Rechnung storniert, ist es zum Abschließen der Bestellung erforderlich, dass die Storno-Rechnung ebenfalls versendet wird. Unabhängig vom Rechnungsbetrag.
- Wird eine Bestellung storniert, werden dabei automatisch etwaige existierende Rechnungen storniert. (Der Automatismus wird durch eine Checkbox beim Editieren der Bestellung optional gemacht.)
- Eine neue Rechnung kann manuell unabhängig vom Wert des `invoiceRequired`-Flags erstellt werden, solange keine aktuelle Rechnung existiert (bspw. für 0€-Rechnungen, die geändert werden)
- Bedingungen, eine Bestellung auf „Storniert“ zu setzen (wird ausgewertet, nachdem alle Rechnungen storniert worden sind, sofern der Automatismus aktiv war):
    - Es darf keine nicht-stornierten Rechnungen geben
    - Sofern eine versendete Rechnung storniert worden ist, muss der Versand der entsprechenden Storno-Rechnung erfolgt sein (vgl. der erste Punkt)
- Bedingungen, eine Bestellung auf „Abgeschlossen“ zu setzen:
    - `invoiceRequired` darf nicht 1 sein
    - Rechnungen müssen versendet worden sein:
        - sofern sie existiert und ihr Gesamtbetrag nicht Null ist, die aktuelle Rechnung
        - sofern eine versendete Rechnung storniert worden ist, die entsprechende Storno-Rechnung (vgl. der erste Punkt).
- Es ist nicht direkt möglich, eine abgeschlossene Bestellung zu bearbeiten. Lediglich die Statusänderung zu „in Bearbeitung“ ist möglich
    - Hinweis anzeigen, bspw. „Diese Bestellung ist bereits abgeschlossen und kann nicht mehr bearbeitet werden.\<br>\<br>\<button>Bestellung wieder öffnen\</button>“ mit Warnung beim Klick auf Button: „Du bist dabei, eine bereits abgeschlossene Bestellung zu bearbeiten. Um diese daraufhin wieder abschließen zu können, wird es gegebenenfalls erforderlich sein, eine neue Rechnung zu erstellen und zu versenden.“
    - UI-Elemente zum Bearbeiten der Bestellung und der `orderItems` ausblenden.
