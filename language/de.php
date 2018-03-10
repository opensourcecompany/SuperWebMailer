<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
#                    Alle Rechte vorbehalten.                               #
#                http://www.supermailinglist.de/                            #
#                http://www.superwebmailer.de/                              #
#   Support SuperMailingList: support@supermailinglist.de                   #
#   Support SuperWebMailer: support@superwebmailer.de                       #
#   Support-Forum: http://board.superscripte.de/                            #
#                                                                           #
#   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
#   muss eine Lizenz erworben werden.                                       #
#                                                                           #
#   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
#   verwendet oder weiterverkauft werden.                                   #
#                                                                           #
#   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
#                                                                           #
#   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
#   Veraenderungen koennen nicht supported werden.                          #
#                                                                           #
#############################################################################


   $language_strings_de  =         array(
                                        "000000" => "",
                                        "000001" => "Fehler beim MySQL-Zugriff / Error while connecting to MySQL database",
                                        "000002" => "Fehler beim Verwenden der Datenbank / Error while selecting database",
                                        "000003" => "Sitzung abgelaufen / Session expired",
                                        "000004" => "Anmeldung",
                                        "000005" => "Benutzername/Kennwort wurden nicht angegeben.",
                                        "000006" => "Benutzername und/oder Kennwort sind nicht korrekt.",
                                        "000007" => "&Uuml;bersicht",
                                        "000008" => "Zugangsdaten zusenden",
                                        "000009" => "Abmeldung",
                                        "000010" => "Sie m&uuml;ssen Benutzername <b>oder</b> E-Mail-Adresse eingeben.",
                                        "000011" => "Es konnte kein Benutzer mit Namen '%s' gefunden werden.",
                                        "000012" => "Es konnte kein Benutzer mit E-Mail-Adresse '%s' gefunden werden.",
                                        "000013" => "Anforderung Ihrer Zugangsdaten",
                                        "000014" => "Neue Empf&auml;ngerliste anlegen",
                                        "000015" => "Sie m&uuml;ssen Ihrer Empf&auml;ngerliste einen eindeutigen Namen geben.",
                                        "000016" => "Empf&auml;ngerlisten anzeigen",
                                        "000017" => "Fehler bei Ausf&uuml;hrung einer SQL-Anweisung",
                                        "000018" => "Die Empf&auml;ngerliste wurde erstellt, legen Sie jetzt weitere Einstellungen fest.",
                                        "000019" => "Empf&auml;ngerliste '%s' &auml;ndern",
                                        "000020" => "Es sind Fehler aufgetreten, korrigieren Sie die Angaben in den rot markierten Feldern.",
                                        "000021" => "Die Einstellungen wurden gespeichert.",
                                        "000022" => "Die Empf&auml;ngerliste(n) wurden gel&ouml;scht.",
                                        "000023" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000024" => "Report der An-/Abmeldungen",
                                        "000025" => "Anmeldungen",
                                        "000026" => "Abmeldungen",
                                        "000027" => "Empfängeraktivitäten",
                                        "000028" => "Die HTML-Seiten/Umleitungen wurden gel&ouml;scht.",
                                        "000029" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000030" => "HTML-Seiten/Umleitungen",
                                        "000031" => "Standard, Nicht l&ouml;schbar",
                                        "000032" => "HTML-Seite/Umleitung bearbeiten",
                                        "000033" => "Nicht l&ouml;schbar, da in Verwendung",
                                        "000034" => "Die Empf&auml;nger wurden gel&ouml;scht.",
                                        "000035" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000036" => "Empf&auml;nger anzeigen",
                                        "000037" => "Die Empf&auml;nger wurden verschoben.",
                                        "000038" => "Es traten Fehler beim Verschieben auf:<br />",
                                        "000039" => "Die Empf&auml;nger wurden kopiert.",
                                        "000040" => "Es traten Fehler beim Kopieren auf:<br />",
                                        "000041" => "Die Empf&auml;nger wurden zur Blockliste hinzugef&uuml;gt.",
                                        "000042" => "Es traten Fehler beim Hinzuf&uuml;gen zur Blockliste auf:<br />",
                                        "000043" => "Quell- und Ziel-Empf&auml;ngerliste d&uuml;rfen nicht identisch sein.",
                                        "000044" => "Empf&auml;nger bearbeiten",
                                        "000045" => "Die Empf&auml;ngerliste wurde nicht gefunden.",
                                        "000046" => "Es existiert bereits ein Empf&auml;nger mit dieser E-Mail-Adresse.",
                                        "000047" => "manuell hinzugef&uuml;gt",

                                        "000048" => "Best&auml;tigungslink noch nicht angeklickt",

                                        "000049" => "Empf&auml;nger aktiv",
                                        "000050" => "Best&auml;tigungslink zur Abmeldung noch nicht angeklickt",
                                        "000051" => "Permanent nicht zustellbar",
                                        "000052" => "Vor&uuml;bergehend nicht zustellbar",
                                        "000053" => "Empf&auml;ngerliste w&auml;hlen",
                                        "000054" => "Empf&auml;nger importieren",
                                        "000055" => "Datei %s konnte im Verzeichnis %s nicht gespeichert werden, pr&uuml;fen Sie die Rechte auf das Verzeichnis.",
                                        "000056" => "Kann die Datei %s nicht zum Lesen &ouml;ffnen.",
                                        "000057" => "Datumsformat",
                                        "000058" => "Es muss mindestens eine Feldzuordnung erfolgen.",
                                        "000059" => "Das Feld mit der E-Mail-Adresse muss eine Zuordnung erhalten.",
                                        "000060" => "Datei %s konnte nicht gel&ouml;scht werden.",
                                        "000061" => "Die Meldungstexte wurden gel&ouml;scht.",
                                        "000062" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000063" => "Meldungstexte",
                                        "000064" => "Meldungstexte bearbeiten",
                                        "000065" => "Das Formular wurden gel&ouml;scht.",
                                        "000066" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000067" => "An-/Abmeldeformulare",
                                        "000068" => "An-/Abmeldeformulare &auml;ndern",
                                        "000069" => '<option value="invisible">unsichtbar</option><option value="visible">sichtbar, optional</option>',
                                        "000070" => '<option value="visiblerequired">sichtbar, Pflichtfeld</option>',
                                        "000071" => "Formularerstellung",
                                        "000072" => "Formularerstellung - Integriertes PRODUCTAPPNAME-Formular",
                                        "000073" => "Formularerstellung - Formular f&uuml;r die eigenen Webseite",

                                        "000075" => "Die Versandvariante wurden gel&ouml;scht.",
                                        "000076" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000077" => "Versandvarianten (MTAs)",
                                        "000078" => "Versandvariante bearbeiten",
                                        "000079" => "Test E-Mail",
                                        "000080" => "Das ist der Text der Test E-Mail.",
                                        "000081" => "Die Test-E-Mail wurde erfolgreich versendet.",
                                        "000082" => "Die Test-E-Mail wurde NICHT erfolgreich versendet:<br />",

                                        "000084" => "Die Test-SMS wurde erfolgreich versendet.",
                                        "000085" => "Die Test-SMS wurde NICHT erfolgreich versendet:<br />",

                                        "000090" => "Die Empf&auml;nger wurden den Gruppen zugeordnet.",
                                        "000091" => "Die Gruppenzuordnung wurde entfernt.",
                                        "000092" => "Die Gruppen wurden gel&ouml;scht.",
                                        "000093" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000094" => "<b>Sie m&uuml;ssen mindestens eine Datei zum L&ouml;schen w&auml;hlen.</b>",

                                        "000098" => "Die Einstellungen wurden dupliziert.",
                                        "000099" => "Es traten Fehler beim Duplizieren auf:<br />",

                                        "000100" => "Benutzer bearbeiten",
                                        "000101" => "Die/Der Nutzer wurde(n) gel&ouml;scht. Datendateien und Ordner des Nutzers m&uuml;ssen manuell gel&ouml;scht werden.",
                                        "000102" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000103" => "Es sind Fehler aufgetreten, korrigieren Sie die Angaben in den rot markierten Feldern.",
                                        "000104" => "Es existiert bereits ein Nutzer mit diesem Benutzernamen, bitte w&auml;hlen Sie einen anderen Namen.",
                                        "000105" => "Nicht l&ouml;schbar, da der Nutzer Empf&auml;ngerlisten besitzt",
                                        "000106" => "Nicht l&ouml;schbar, da der Nutzer Nutzer besitzt",
                                        "000107" => "Eigenes Konto bearbeiten",

                                        "000110" => "Funktionen bearbeiten",
                                        "000111" => "Die Funktion(en) wurden gel&ouml;scht.",
                                        "000112" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000115" => "Funktion %s bearbeiten",
                                        "000116" => "Sie m&uuml;ssen der Funktion einen eindeutigen Namen geben.",
                                        "000117" => "Der Funktionsname darf die Zeichen [ und ] nicht enthalten.",
                                        "000118" => "Der Funktionsname darf nur die Zeichen A-Z, a-z, 0-9 und _ enthalten.",
                                        "000119" => "Bisher keine Bedingungen definiert.",
                                        "000120" => "Bedingung der Funktion %s bearbeiten.",

                                        "000130" => "Globale Blockliste",
                                        "000131" => "Lokale Blockliste",
                                        "000132" => "Die Eintr&auml;ge wurden gel&ouml;scht.",
                                        "000133" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000134" => "Diese E-Mail-Adresse existiert bereits in der Blockliste.",
                                        "000135" => "Die E-Mail-Adresse wurde in der Blockliste gespeichert.",
                                        "000136" => "Die E-Mail-Adresse konnte in der Blockliste nicht gespeichert werden, da diese bereits enthalten ist.<br />",
                                        "000137" => "Neuer Eintrag in globaler Blockliste",
                                        "000138" => "Neuer Eintrag in lokaler Blockliste",
                                        "000139" => "Importieren in globale Blockliste",
                                        "000140" => "Importieren in lokale Blockliste",

                                        "000141" => "Exportieren der globalen Blockliste",
                                        "000142" => "Exportieren der lokalen Blockliste",


                                        "000150" => "Empf&auml;nger exportieren",
                                        "000151" => "Datei %s konnte im Verzeichnis %s nicht gespeichert werden, pr&uuml;fen Sie die Rechte auf das Verzeichnis.",
                                        "000152" => "Kann die Datei %s nicht zum Schreiben &ouml;ffnen.",
                                        "000153" => "Es muss mindestens ein Feld gew&auml;hlt werden.",

                                        "000160" => "Der Posteingangsserver wurde gel&ouml;scht.",
                                        "000161" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000162" => "Posteingangsserver/Postf&auml;cher",
                                        "000163" => "Posteingangsserver/Postfach bearbeiten",
                                        "000164" => "Der Test des Posteingangsservers war erfolgreich. Es befinden sich %d E-Mail(s) im Postfach.",
                                        "000165" => "Der Test des Posteingangsservers war NICHT erfolgreich:<br />",
                                        "000166" => "Der Bounce-Status wurde zur&uuml;ckgesetzt.",
                                        "000167" => "Empf&auml;nger ist inaktiv f&uuml;r den E-Mail-Versand",
                                        "000168" => "Die Empf&auml;nger wurden aktiviert.",
                                        "000169" => "Hard bounces / R&uuml;ckl&auml;ufer",
                                        "000170" => "Der Anmeldestatus wurde ge&auml;ndert.",
                                        "000171" => "Die Empf&auml;nger wurden deaktiviert.",

                                        "000180" => "Anmeldung unbestätigt",  // must be UTF-8
                                        "000181" => "Abmeldung unbestätigt",  // must be UTF-8

                                        "000200" => "Serien-E-Mail-Vorschau",
                                        "000201" => "Serien-SMS-Vorschau",

                                        "000250" => "Empf&auml;nger anhand der Blocklisten aus Empf&auml;ngerliste entfernen",
                                        "000251" => "Empf&auml;nger anhand der lokalen Blockliste entfernen",
                                        "000252" => "Empf&auml;nger anhand der globalen Blockliste entfernen",
                                        "000253" => "Empf&auml;nger anhand der ECG-Liste entfernen",
                                        "000254" => "Empf&auml;nger anhand der lokalen Domain-Blockliste entfernen",
                                        "000255" => "Empf&auml;nger anhand der globalen Domain-Blockliste entfernen",

                                        "000260" => "Empf&auml;nger entfernt",
                                        "000261" => "Empf&auml;nger nicht in ECG-Liste enthalten",
                                        "000262" => "Empf&auml;nger nicht in Domain-Blockliste enthalten",

                                        "000300" => "Optionen",
                                        "000320" => "Firmenlogo &auml;ndern",

                                        "000325" => "System testen",
                                        "000326" => "Die E-Mail wurde erfolgreich versendet.",
                                        "000327" => "Die E-Mail wurde <b>nicht</b> erfolgreich versendet.",

                                        "000329" => "Datenbankwartung",
                                        "000330" => "Die Optimierung der Tabellen wurde durchgef&uuml;hrt.",
                                        "000331" => "Die Reparatur der Tabellen wurde durchgef&uuml;hrt.",

                                        "000340" => "Ereignisprotokoll der geplanten Aufgaben",
                                        "000345" => "Geplante Aufgaben",
                                        "000346" => "Angaben im Feld 'Ausf&uuml;hrungsintervall' fehlerhaft z.B. fehlende Eingaben.",
                                        "000347" => "Etwaige geplante Aufgaben wurden ausgef&uuml;hrt.",

                                        "000360" => "Verteilung der E-Mail-Provider",
                                        "000361" => "Gr&uuml;nde für die Abmeldung von '%s'",

                                        "000370" => "Statistik &uuml;ber alle Empf&auml;ngerlisten",
                                        "000371" => "in den letzten 5 Tagen",

                                        "000390" => "Kann Datenbankverbindung zu %s nicht &ouml;ffnen.",
                                        "000391" => "SQL-Anweisung fehlerhaft: %s",

                                        "000480" => "%s w&auml;hlen",
                                        "000481" => "Bitte w&auml;hlen Sie den anzuzeigenden %s.",
                                        "000482" => "%s",

                                        "000483" => "E-Mailing w&auml;hlen",
                                        "000484" => "Bitte w&auml;hlen Sie das anzuzeigende E-Mailing.",
                                        "000485" => "E-Mailing",

                                        "000486" => "Versandeintrag des E-Mailings '%s' w&auml;hlen",
                                        "000487" => "Bitte w&auml;hlen Sie den Versandeintrag des E-Mailings '%s'.",
                                        "000488" => "Versand am",

                                        "000489" => "Bitte w&auml;hlen Sie die anzuzeigende SMS-Kampagne.",

                                        "000490" => "Versandeintrag w&auml;hlen",
                                        "000491" => "Bitte w&auml;hlen Sie den Versandeintrag f&uuml;r '%s'.",
                                        "000492" => "Versand am",

                                        "000500" => "Der Autoresponder wurde gel&ouml;scht.",
                                        "000501" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000502" => "Autoresponder",
                                        "000503" => "Autoresponder bearbeiten",
                                        "000504" => "Es existiert bereits ein Autoresponder mit dem Namen '%s'.",
                                        "000510" => "Versandprotokoll des Autoresponders&nbsp;",
                                        "000511" => "Die Eintr&auml;g(e) wurden gel&ouml;scht.",
                                        "000512" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000513" => "Die Eintr&auml;g(e) wurden nochmals versendet bzw. sind f&uuml;r den Versand vorgesehen.",
                                        "000514" => "Es traten Fehler beim Versenden auf:<br />",

                                        "000520" => "Der Follow-Up-Responder wurde gel&ouml;scht.",
                                        "000521" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000522" => "Follow-Up-Responder",
                                        "000523" => "Follow-Up-Responder bearbeiten",
                                        "000524" => "Es existiert bereits ein Follow-Up-Responder mit dem Namen '%s'.",
                                        "000528" => "F&uuml;r aktionsbasierte Follow-Up-Responder muss das personalisierte Tracking (Empf&auml;ngertracking) mit Z&auml;hlung der &Ouml;ffnungen und Klicks auf Hyperlinks aktiviert werden.",
                                        "000529" => "Nicht &auml;nderbar, ein oder mehrere Folge-E-Mails wurden bereits versendet",
                                        "000530" => "Versandprotokoll des Follow-Up-Responders&nbsp;",

                                        "000540" => "Die Follow-Up-E-Mail(s) wurden gel&ouml;scht.",
                                        "000541" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000542" => "Follow-Up-E-Mails bearbeiten",
                                        "000543" => "nach %d %s",
                                        "000544" => "Es existiert bereits eine Follow-Up-E-Mail mit dem Namen '%s'.",
                                        "000545" => "Follow-Up-E-Mail des Follow-Up-Responders '%s' bearbeiten",

                                        "000549" => "Pr&uuml;fen Sie bei diesem aktionsbasierten Follow-Up-Responder die nachfolgende E-Mail auf korrekte Auswahl der auszuf&uuml;hrenden Aktion.",

                                        "000550" => "Empf&auml;nger und n&auml;chste Follow-Up-E-Mail des Follow-Up-Responders '%s' anzeigen",

                                        "000560" => "Der Geburtstags-Responder wurde gel&ouml;scht.",
                                        "000561" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000562" => "Geburtstags-Responder",
                                        "000563" => "Geburtstags-Responder bearbeiten",
                                        "000564" => "Es existiert bereits ein Geburtstags-Responder mit dem Namen '%s'.",
                                        "000570" => "Versandprotokoll des Geburtstags-Responder&nbsp;",
                                        "000571" => "Die Eintr&auml;g(e) wurden gel&ouml;scht.",
                                        "000572" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000573" => "Die Eintr&auml;g(e) wurden nochmals versendet bzw. sind f&uuml;r den Versand vorgesehen.",
                                        "000574" => "Es traten Fehler beim Versenden auf:<br />",

                                        "000580" => "Pr&uuml;fen Sie die komplette Mobilfunknummer '%s', diese darf nur Zahlen enthalten und muss 14 oder 15 stellig sein.",
                                        "000581" => "Pr&uuml;fen Sie die komplette Mobilfunknummer '%s', die Vorwahl ist unbekannt.",
                                        "000582" => "Pr&uuml;fen Sie die komplette Mobilfunknummer '%s', diese enth&auml;lt keine Landesvorwahl.",
                                        "000583" => "Pr&uuml;fen Sie die komplette Mobilfunknummer '%s', diese ist zu kurz.",
                                        "000584" => "Die Mobilfunknummer darf nicht leer sein.",

                                        "000600" => "Neues E-Mailing erstellen",
                                        "000601" => "Sie m&uuml;ssen Ihrem E-Mailing einen eindeutigen Namen geben.",
                                        "000602" => "Sie m&uuml;ssen eine Empf&auml;ngerliste f&uuml;r Ihr E-Mailing w&auml;hlen.",
                                        "000603" => "Das E-Mailing wurde erstellt, legen Sie jetzt weitere Einstellungen fest.",
                                        "000604" => "E-Mailing '%s' &auml;ndern",
                                        "000605" => "E-Mailing '%s' fertiggestellt",
                                        "000606" => "Dieses E-Mailing",

                                        "000610" => "Nicht &auml;nderbar, Versand l&auml;uft gerade",
                                        "000611" => "Nicht &auml;nderbar, wird von einem/mehreren Split Test(s) verwendet",

                                        "000620" => "E-Mailings",
                                        "000621" => "Die E-Mailing(s) wurden gel&ouml;scht.",
                                        "000622" => "Es traten Fehler beim L&ouml;schen auf:<br />",

                                        "000623" => "Der Versand des E-Mailings wurde abgebrochen.",
                                        "000624" => "Der Versand des E-Mailings wird beim nächsten Aufruf des CronJob-Scripts abgebrochen.",

                                        "000650" => "Versand des E-Mailings &quot;%s&quot;",
                                        "000651" => "Erfolgreich versendet. Zeit:",
                                        "000652" => "Nicht erfolgreich, Fehlercode: %d, Fehlertext: %s. Zeit:",
                                        "000653" => "Report des Versands des E-Mailings: %s",

                                        "000670" => "Versandprotokoll des E-Mailings&nbsp;'%s', versendet am %s",
                                        "000671" => "Die Eintr&auml;g(e) wurden gel&ouml;scht.",
                                        "000672" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000673" => "Die Eintr&auml;g(e) wurden nochmals versendet bzw. sind f&uuml;r den Versand vorgesehen.",
                                        "000674" => "Es traten Fehler beim Versenden auf:<br />",

                                        "000675" => "Versand l&auml;uft gerade",

                                        "000680" => "Tracking-Statistik des E-Mailings&nbsp;'%s', versendet am %s",
                                        "000681" => "Öffnungen", // must be UTF-8
                                        "000682" => "Soft bounces", // must be UTF-8
                                        "000683" => "Hard bounces", // must be UTF-8
                                        "000684" => "nach %d-%d Stunden", // must be UTF-8
                                        "000685" => "Zeitpunkt der Öffnung", // must be UTF-8
                                        "000686" => "Erfolgreich", // must be UTF-8
                                        "000687" => "Fehlgeschlagen", // must be UTF-8
                                        "000688" => "Auswertung des letzten E-Mailings", // must be UTF-8

                                        "000690" => "%RECIPIENTCOUNT% Empf&auml;nger, die die E-Mail ge&ouml;ffnet haben",
                                        "000691" => "%%RECIPIENTCOUNT%% Empf&auml;nger, die auf den Link '%s' geklickt haben",
                                        "000692" => "Empf&auml;nger existiert nicht mehr.",

                                        "000693" => "Erster Zugriff",
                                        "000694" => "Letzter Zugriff",
                                        "000695" => "Anzahl Zugriffe",

                                        "000696" => "Erster Klick",
                                        "000697" => "Letzter Klick",
                                        "000698" => "Anzahl Klicks",

                                        "000700" => "Statistik des Responders '%s'",
                                        "000701" => "Öffnungen %s - %s", // must be UTF-8
                                        "000702" => "%%RECIPIENTCOUNT%% Empf&auml;nger, die die E-Mail zwischen dem %s und %s ge&ouml;ffnet haben",
                                        "000703" => "%%RECIPIENTCOUNT%% Empf&auml;nger, die auf den Link '%s' zwischen dem %s und %s geklickt haben",
                                        "000704" => "Statistik des Responders '%s'; Follow-Up-E-Mail: '%s'",
                                        "000705" => "Statistik der Verteilerliste '%s'; E-Mail: '%s'",

                                        "000730" => "Statistik versendete E-Mails",
                                        "000731" => "Versendete E-Mails", // must be UTF-8

                                        "000740" => "Twitter Tweet posten",
                                        "000741" => "Facebook Status-Message posten",

                                        "000800" => "Die E-Mail-Vorlage(n) wurden gel&ouml;scht.",
                                        "000801" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000802" => "E-Mail-Vorlagen",
                                        "000803" => "Standard, Nicht l&ouml;schbar",
                                        "000804" => "E-Mail-Vorlage bearbeiten",
                                        "000805" => "Es existiert bereits ein E-Mail-Vorlage mit dem Namen '%s'.",
                                        "000806" => "Beispiel E-Mail-Vorlage",
                                        "000807" => "Beispiel E-Mail-Vorlage zur Nutzung mit dem Assistenten",

                                        "000810" => "E-Mail-Vorlage einf&uuml;gen",

                                        "000830" => "Newsletter-Archive",
                                        "000831" => "Standard, Nicht l&ouml;schbar",
                                        "000832" => "Newsletter-Archiv bearbeiten",
                                        "000833" => "Das Newsletter-Archiv wurde gel&ouml;scht.",
                                        "000834" => "Es traten Fehler beim L&ouml;schen auf:<br />",

                                        "000860" => "Der RSS2EMail-Responder wurde gel&ouml;scht.",
                                        "000861" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000862" => "RSS2EMail-Responder",
                                        "000863" => "RSS2EMail-Responder bearbeiten",
                                        "000864" => "Es existiert bereits ein RSS2EMail-Responder mit dem Namen '%s'.",
                                        "000870" => "Versandprotokoll des RSS2EMail-Responder&nbsp;",
                                        "000871" => "Die Eintr&auml;g(e) wurden gel&ouml;scht.",
                                        "000872" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "000873" => "Die Eintr&auml;g(e) wurden nochmals versendet bzw. sind f&uuml;r den Versand vorgesehen.",
                                        "000874" => "Es traten Fehler beim Versenden auf:<br />",

                                        "000950" => "Sie besitzen nicht die erforderliche Berechtigung.",
                                        "000951" => "Ausgangswarteschlange anzeigen",
                                        "000952" => "Die Eintr&auml;g(e) wurden gel&ouml;scht.",
                                        "000953" => "Es traten Fehler beim L&ouml;schen auf:<br />",

                                        "001110" => "Lokales Nachrichtencenter",
                                        "001111" => "Die Nachricht(en) wurden gel&ouml;scht.",
                                        "001112" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "001115" => "Nachricht lesen",
                                        "001116" => "Neue Nachricht",
                                        "001117" => "Nachricht beantworten",
                                        "001118" => "Der Nutzername existiert nicht.",
                                        "001119" => "Die Nachricht konnte nicht versendet werden, Fehler %s.",
                                        "001120" => "Die Nachricht wurde versendet.",

                                        "001130" => "Globale Domain-Blockliste",
                                        "001131" => "Lokale Domain-Blockliste",
                                        "001132" => "Die Eintr&auml;ge wurden gel&ouml;scht.",
                                        "001133" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "001134" => "Diese Domain existiert bereits in der Blockliste.",
                                        "001135" => "Die Domain wurde in die Blockliste gespeichert.",
                                        "001136" => "Die Domain konnte in die Blockliste nicht gespeichert werden, da diese bereits enthalten ist.<br />",
                                        "001137" => "Neuer Eintrag in globaler Domain-Blockliste",
                                        "001138" => "Neuer Eintrag in lokaler Domain-Blockliste",
                                        "001139" => "Importieren in globale Domain-Blockliste",
                                        "001140" => "Importieren in lokale Domain-Blockliste",

                                        "001141" => "Exportieren der globalen Domain-Blockliste",
                                        "001142" => "Exportieren der lokalen Domain-Blockliste",

                                        "001200" => "Automatischer Empf&auml;nger-Import",
                                        "001201" => "Nicht l&ouml;schbar, Import unvollst&auml;ndig",
                                        "001202" => "Import aus Datei",
                                        "001203" => "Import aus Datenbank",
                                        "001204" => "Es existiert bereits ein Eintrag mit dem Namen '%s'.",
                                        "001205" => "%s - Automatischer Empf&auml;nger-Import bearbeiten",
                                        "001206" => "DEMO - Automatischer Import wird deaktiviert gespeichert.",
                                        "001207" => "Der automatische Empf&auml;nger-Import wurde gel&ouml;scht.",

                                        "001300" => "Twitter Update nicht aktiviert.",
                                        "001301" => "Twitter Tweet wurde gepostet.",
                                        "001302" => "Fehler beim Posten des Twitter Tweets.",
                                        "001303" => "Fehler beim Verbinden mit dem Short-URL-Service.",

                                        "001310" => "Bei Twitter authentifizieren",
                                        "001311" => "Der Eintrag wurde erstellt:",
                                        "001312" => "Erstellungsdatum:",
                                        "001313" => "ID des Eintrags:",
                                        "001314" => "Text des Eintrags:",
                                        "001315" => "<b>Der Eintrag wurde nicht erstellt:</b>",
                                        "001316" => "<b>Fehler:</b>",
                                        "001317" => "Unauthorisierter Zugriff.",
                                        "001318" => "<b>Unbekannter Fehler</b>",

                                        "001410" => "Textbl&ouml;cke bearbeiten",
                                        "001411" => "Die Textbl&ouml;ck(e) wurden gel&ouml;scht.",
                                        "001412" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "001415" => "Textblock %s bearbeiten",
                                        "001416" => "Sie m&uuml;ssen dem Textblock einen eindeutigen Namen geben.",
                                        "001417" => "Der Name des Textblocks darf die Zeichen [ und ] nicht enthalten.",
                                        "001418" => "Der Name des Textblocks darf nur die Zeichen A-Z, a-z, 0-9 und _ enthalten.",
                                        "001419" => "Geben Sie einen Ausgabetext ein.",
                                        "001420" => "Der Name des Textblocks wird bereits f&uuml;r einen andern Platzhalter verwendet.",

                                        "001510" => "Zielgruppen bearbeiten",
                                        "001511" => "Die Zielgruppe(n) wurden gel&ouml;scht.",
                                        "001512" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "001515" => "Zielgruppe %s bearbeiten",
                                        "001516" => "Sie m&uuml;ssen der Zielgruppe einen eindeutigen Namen geben.",
                                        "001517" => "Der Name der Zielgruppe darf die Zeichen [ und ] nicht enthalten.",
                                        "001518" => "Der Name des Zielgruppe darf nur die Zeichen A-Z, a-z, 0-9 und _ enthalten.",

                                        "001600" => "Neue SMS-Kampagne erstellen",
                                        "001601" => "Sie m&uuml;ssen Ihrer SMS-Kampagne einen eindeutigen Namen geben.",
                                        "001602" => "Sie m&uuml;ssen eine Empf&auml;ngerliste f&uuml;r Ihre SMS-Kampagne w&auml;hlen.",
                                        "001603" => "Die SMS-Kampagne wurde erstellt, legen Sie jetzt weitere Einstellungen fest.",
                                        "001604" => "SMS-Kampagne '%s' &auml;ndern",
                                        "001605" => "SMS-Kampagne '%s' fertiggestellt",

                                        "001610" => "Nicht &auml;nderbar, Versand l&auml;uft gerade",

                                        "001620" => "SMS-Kampagnen",
                                        "001621" => "Die SMS-Kampagne(n) wurden gel&ouml;scht.",
                                        "001622" => "Es traten Fehler beim L&ouml;schen auf:<br />",

                                        "001623" => "Der Versand der SMS-Kampagne wurde abgebrochen.",
                                        "001624" => "Der Versand der SMS-Kampagne wird beim nächsten Aufruf des CronJob-Scripts abgebrochen.",

                                        "001650" => "Versand der SMS-Kampagne &quot;%s&quot;",
                                        "001651" => "Erfolgreich versendet, %s Zeit:",
                                        "001652" => "Nicht erfolgreich, Fehlercode: %d, Fehlertext: %s. Zeit:",
                                        "001653" => "Report des Versands der SMS-Kampagne: %s",

                                        "001670" => "Versandprotokoll der SMS-Kampagne&nbsp;'%s', versendet am %s",

                                        "001675" => "Versand l&auml;uft gerade",

                                        "001800" => "Neuen A/B Split Test erstellen",
                                        "001801" => "Sie m&uuml;ssen dem A/B Split Test einen eindeutigen Namen geben.",
                                        "001802" => "Sie m&uuml;ssen eine Empf&auml;ngerliste f&uuml;r den A/B Split Test w&auml;hlen.",
                                        "001803" => "Der A/B Split Test wurde erstellt, legen Sie jetzt weitere Einstellungen fest.",
                                        "001804" => "A/B Split Test '%s' &auml;ndern",
                                        "001805" => "A/B Split Test '%s' fertiggestellt",
                                        "001810" => "Nicht &auml;nderbar, Versand l&auml;uft gerade",
                                        "001820" => "A/B Split Test",
                                        "001821" => "Die Split Test(s) wurden gel&ouml;scht.",
                                        "001822" => "Es traten Fehler beim L&ouml;schen auf:<br />",

                                        "001830" => "Die gew&auml;hlten Empf&auml;ngergruppen und angelegten Regeln m&uuml;ssen f&uuml;r die gew&auml;hlten E-Mailings identisch sein.",

                                        "001840" => "Versand wird vorbereitet",
                                        "001841" => "Warte auf Ergebnisse des Versands",
                                        "001842" => "Versand des &quot;Gewinner E-Mailings&quot; wird vorbereitet",
                                        "001843" => "Versand des &quot;Gewinner E-Mailings&quot; l&auml;uft gerade",

                                        "001850" => "Versandprotokoll des A/B Split Tests&nbsp;'%s', versendet am %s",
                                        "001851" => "%s; %d &Ouml;ffnung(en) der E-Mail",
                                        "001852" => "%s; %d Klick(s) auf Hyperlinks",

                                        "001860" => "Tracking-Statistik des A/B Split Tests&nbsp;'%s', versendet am %s",

                                        "001900" => "Felder der Empf&auml;ngerlisten bearbeiten",

                                        "002000" => "Empf&auml;nger suchen",
                                        "002001" => "Ergebnis der Suche (<!--FOUNDCOUNT--> Treffer)",
                                        "002002" => "Gefundene Empf&auml;nger anzeigen",

                                        "002600" => "Neue Verteilerliste erstellen",
                                        "002601" => "Sie m&uuml;ssen Ihrer Verteilerliste einen eindeutigen Namen geben.",
                                        "002602" => "Sie m&uuml;ssen eine Empf&auml;ngerliste f&uuml;r Ihre Verteilerliste w&auml;hlen.",
                                        "002603" => "Die Verteilerliste wurde erstellt, legen Sie jetzt weitere Einstellungen fest.",
                                        "002604" => "Verteilerliste '%s' &auml;ndern",
                                        "002605" => "Verteilerliste '%s' fertiggestellt",
                                        "002606" => "Diese Verteilerliste",
                                        "002607" => "Sie m&uuml;ssen einen Posteingangsserver/Postfach w&auml;hlen.",

                                        "002610" => "Nicht &auml;nderbar, Versand l&auml;uft gerade",
                                        "002611" => "Nicht l&ouml;schbar, Versand l&auml;uft gerade",

                                        "002620" => "Verteilerlisten",
                                        "002621" => "Die Verteilerliste(n) wurden gel&ouml;scht.",
                                        "002622" => "Es traten Fehler beim L&ouml;schen auf:<br />",

                                        "002623" => "Der Versand der E-Mail(s) in der Verteilerliste wurde abgebrochen.",
                                        "002624" => "Der Versand der E-Mail(s) in der Verteilerliste wird beim n&auml;chsten Aufruf des CronJob-Scripts abgebrochen.",

                                        "002630" => "WARNUNG! Das Postfach wird ebenfalls genutzt f&uuml;r:",

                                        "002650" => "Versand des Eintrags in der Verteilerliste &quot;%s&quot;",
                                        "002651" => "Erfolgreich versendet. Zeit:",
                                        "002652" => "Nicht erfolgreich, Fehlercode: %d, Fehlertext: %s. Zeit:",
                                        "002653" => "Report des Versands f&uuml;r den Eintrag in der Verteilerliste: %s",

                                        "002660" => "E-Mail der Verteilerliste '%s' w&auml;hlen",
                                        "002661" => "Bitte w&auml;hlen Sie die E-Mail der Verteilerliste '%s'.",
                                        "002662" => "Betreff",
                                        "002663" => "Bitte w&auml;hlen Sie die anzuzeigende %s.",

                                        "002670" => "Versandprotokoll der E-Mail '%s' in Verteilerliste&nbsp;'%s', versendet am %s",
                                        "002671" => "Die E-Mail(s) wurden gel&ouml;scht.",
                                        "002672" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "002673" => "Die E-Mail(s) wurden nochmals versendet bzw. sind f&uuml;r den Versand vorgesehen.",
                                        "002674" => "Es traten Fehler beim Versenden auf:<br />",

                                        "002675" => "Versand l&auml;uft gerade",
                                        "002680" => "Bestätigung Versand einer E-Mail an Verteilerliste: [DISTRIBLISTNAME]",
                                        "002681" => "Report des Versands der Verteilerliste: %s; Betreff: %s",

                                        "002690" => "Die E-Mail(s) wurden gel&ouml;scht.",
                                        "002691" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "002692" => "E-Mails der Verteilerliste",
                                        "002693" => "Die E-Mail mit dem Best&auml;tigungslink wurde erneut an '%s' gesendet.",
                                        "002694" => "Die E-Mail mit dem Best&auml;tigungslink konnte nicht an '%s' gesendet werden.",

                                        "002710" => "Abmeldegr&uuml;nde bearbeiten",
                                        "002711" => "Die Abmeldegr&uuml;nd(e) wurden gel&ouml;scht.",
                                        "002712" => "Es traten Fehler beim L&ouml;schen auf:<br />",
                                        "002715" => "Abmeldegrund %s bearbeiten",
                                        "002716" => "Der Abmeldegrund muss einen Text enthalten.",

                                        "090000" => "SuperAdministrator anlegen",
                                        "090001" => "Kennwort und Kennwortwiederholung stimmen nicht &uuml;berein",
                                        "090002" => "Der Nutzer SuperAdmin wurde erstellt.",
                                        "090003" => "Der Benutzername darf nur die Zeichen A-Z, Ziffern 0-9 und den Unterstrich(_) enthalten. Es muss mindestens 3 Zeichen lang sein.",

                                        "090100" => "Installation PRODUCTAPPNAME - Willkommen",
                                        "090101" => "Installation PRODUCTAPPNAME - Lizenzdaten eingeben",
                                        "090102" => "Installation PRODUCTAPPNAME - Verzeichnisse eingeben",
                                        "090103" => "Installation PRODUCTAPPNAME - Daten f&uuml;r den Datenbankzugriff eingeben",
                                        "090104" => "Installation PRODUCTAPPNAME - Informationen zu CronJobs/Geplanten Aufgaben",
                                        "090105" => "Installation PRODUCTAPPNAME - Rechte auf Verzeichnisse und Dateien festlegen",
                                        "090107" => "Installation PRODUCTAPPNAME - SuperAdministrator anlegen",
                                        "090108" => "Installation PRODUCTAPPNAME - Ersten Administrator anlegen",
                                        "090109" => "Installation PRODUCTAPPNAME - L&ouml;schen des Scripts install.php",
                                        "090110" => "Installation PRODUCTAPPNAME - Abschluss der Installation",

                                        "090170" => "Upgrade PRODUCTAPPNAME - Willkommen",
                                        "090171" => "Upgrade PRODUCTAPPNAME - Analyse und Anpassung der Tabellen",
                                        "090185" => "Upgrade PRODUCTAPPNAME - L&ouml;schen des Scripts upgrade.php und install.php",
                                        "090186" => "Upgrade PRODUCTAPPNAME - Abschluss des Upgrades",

                                        "090197" => "Lizenz-Upgrade PRODUCTAPPNAME - Willkommen",
                                        "090198" => "Lizenz-Upgrade PRODUCTAPPNAME - Lizenzdaten eingeben",
                                        "090199" => "Lizenz-Upgrade PRODUCTAPPNAME - Abschluss des Upgrades",

                                        "090200" => "Pr&uuuml;fen Sie die Schreibung des Lizenznehmers und der Lizenznummer.",
                                        "090201" => "Fehler bei Pr&uuml;fung der Lizenz.<br />Klicken Sie auf &quot;Manuelle Verifizierung der Lizenz vornehmen&quot;, um im Browser die Lizenz pr&uuml;fen zu lassen.<br />",
                                        "090202" => "Die Lizenzangaben sind ung&uuml;ltig.",
                                        "090203" => "Diese Lizenz wurde gesperrt z.B. wegen nicht bezahlter Rechnung.",
                                        "090204" => "Die Lizenz wurde zu oft eingegeben. Dieses Produkt darf nur auf einem Server/Webspace installiert werden.",
                                        "090205" => "Ein unbekannter Fehler trat auf.",
                                        "090206" => "Der eingegebene Verifizierungscode ist nicht korrekt.",
                                        "090210" => "Konnte Datei config_paths.inc.php nicht speichern. Pr&uuml;fen Sie die Script-Server-Verzeichnis-Angabe und die Rechte auf die Datei config_paths.inc.php (unter Linux 777, unter Windows Vollzugriff).",
                                        "090211" => "Der Zugriff auf die MySQL-Datenbank mit den eingegebenen Zugangsdaten ist fehlgeschlagen, Fehler: ",
                                        "090212" => "Konnte Datei config_db.inc.php nicht speichern. Pr&uuml;fen Sie die Script-Server-Verzeichnis-Angabe und die Rechte auf die Datei config_db.inc.php (unter Linux 777, unter Windows Vollzugriff).",
                                        "090213" => "Das Script install.php existiert weiterhin auf Ihrer Webpr&auml;senz. L&ouml;schen Sie bitte das Script.",
                                        "090214" => "Das Script upgrade.php und/oder install.php existiert weiterhin auf Ihrer Webpr&auml;senz. L&ouml;schen Sie bitte die Scripte.",

                                        "999997" => "<b>Eingeschr&auml;nktes &ouml;ffentliches Demo</b>, es k&ouml;nnen nur <b>Test-E-Mails</b> versendet werden, der Versand von Massen-E-Mails wird nur simuliert. &Uuml;bertragen Sie ebenfalls <b>keine</b> pers&ouml;nlichen Daten, denn diese k&ouml;nnen von anderen Testern eingesehen werden.",
                                        "999998" => "<b>Eingeschr&auml;nkte Test-Version</b>, es kann nur <b>eine Empf&auml;ngerliste</b> pro Admin-Nutzer erstellt und <b>Test-E-Mails</b> versendet werden.",
                                        "999999" => "Test-Version, es kann nur eine Empf&auml;ngerliste in der Installation pro Admin-Nutzer erstellt werden.",

                                        "YES"  => "ja",
                                        "NO"  => "nein",
                                        "CLOSE"  => "Schlie&szlig;en",
                                        "CANCEL"  => "Abbrechen",
                                        "NA"  => "n/a",
                                        "ERROR" => "Fehler",
                                        "NEW"  => "neu",
                                        "NONE" => "keine",
                                        "NEVER" => "niemals",
                                        "INACTIVE" => "deaktiviert",
                                        "UNKNOWN" => "unbekannt",
                                        "FAILED" => "fehlgeschlagen",
                                        "EXECUTED" => "ausgef&uuml;hrt",
                                        "NOT_EXECUTED" => "nichts ausgef&uuml;hrt",
                                        "EXECUTING" => "wird gerade ausgef&uuml;hrt",
                                        "TRUE" => "wahr",
                                        "FALSE" => "falsch",
                                        "ENTRYNOTFOUND" => "Eintrag nicht gefunden",
                                        "NO_MAILINGLISTS" => "Es wurde keine Empf&auml;ngerlisten gefunden, erstellen Sie mindestens eine Empf&auml;ngerliste.",
                                        "RecipientsCount" => "Empf&auml;nger",
                                        "Date" => "Datum",
                                        "Quantity" => "Anzahl",
                                        "QuantityAccumulated" => "Anzahl, kumuliert",
                                        "CountOfVotes" => "Anzahl Abstimmungen",
                                        "PortionOfVotes" => "Anteil der Abstimmungen",
                                        "ClickRate" => "Klickanteil",
                                        "Subject" => "Betreff",
                                        "Clicks" => "Klicks",
                                        "LinkDescription" => "Link/Beschreibung",
                                        "Top10" => "Top 10",
                                        "UNKNOWN_COUNTRY" => "unbekanntes Land",
                                        "ImageOrFileNotFound" => "Bild oder Datei '%s' nicht gefunden.",
                                        "ImagesOrFilesNotFound" => "Bild(er) oder Datei(en) nicht gefunden.",
                                        "UnknownEMailClient" => "Unbekanntes E-Mail-Programm",
                                        "UnknownOS" => "Unbekanntes Betriebssystem",

                                        "UniqueOpenings" => "eindeutige &Ouml;ffnungen: %d",
                                        "UniqueClicks" => "eindeutige Klicks: %d",

                                        "SUBMITBtnText" => "Absenden",
                                        "SUBSCRIBEBtnText" => "Anmelden",
                                        "UNSUBSCRIBEBtnText" => "Abmelden",

                                        "PlaceholderBecauseOfMailingListRuleNotAllowed" => "Der Platzhalter '%s' darf nicht verwendet werden, weil die Einstellung der Empf&auml;ngerliste f&uuml;r An-/Abmeldungen dies verbietet.",

                                        "CampaignTargetGroupsFoundAutoCreateTextPartDisabled" => "Es wurden Zielgruppen im HTML-Teil der E-Mail gefunden, die automatische Erstellung des Textteils der E-Mail sollte aktiviert werden um den gleichen Inhalt des HTML-Teils im Text-Teil versenden zu lassen.",

                                        "AccountDisabled" => "Ihr Zugang wurde durch den Administrator gesperrt.",

                                        "MailingList" => "Empf&auml;ngerliste",
                                        "DomainName" => "Domain",

                                        "EMailCampaignSetupIncomplete" => "Einrichtung nicht abgeschlossen",
                                        "EMailCampaignSendingInProgress" => "Der Versand des E-Mailings wird gerade durchgef&uuml;hrt.",
                                        "SaveOnly" => "Nur speichern",
                                        "SendManually" => "Live-Versand",
                                        "SendImmediately" => "Sofortiger Versand per CronJob",
                                        "SCHEDULED" => "Geplant",
                                        "SendInFutureMultipleTimes" => "Geplant f&uuml;r mehrfachen Versand",

                                        "DistribListConfirmationPending" => "Wartet auf Best&auml;tigung",
                                        "DistribListSendingInProgress" => "Versand l&auml;uft gerade",
                                        "DistribListSendingDone" => "Versendet",

                                        "RcptsColumnsChanged" => "Die &Auml;nderungen wurden gespeichert. Lassen Sie die Empf&auml;ngerliste neu anzeigen, damit die &Auml;nderungen sichtbar werden.",

                                        "ALLOW_URL_FOPEN_OFF" => "allow_url_fopen ist in der Datei php.ini nicht aktiviert. http:// oder https://-Aufruf nicht m&ouml;glich.",
                                        "MEMORY_LIMIT_EXCEEDED" => "Die hochgerechnete Gr&ouml;&szlig;e der E-Mail %s wird das eingestellte Speicherlimit in der Datei php.ini m&ouml;glicherweise &uuml;berschreiten (Angabe memory_limit=%s). Sie sollten Bilder oder Anh&auml;nge aus der E-Mail entfernen, ansonsten wird M&ouml;glicherweise das Script w&auml;hrend des E-Mail-Versands komplett abgebrochen und keine E-Mail versendet.",
                                        "NO_MSSQL_SUPPORT" => "Die notwendige Erweiterung f&uuml;r den Import aus Microsoft SQL-Datenbanken ist im PHP nicht installiert.",

                                        "Minute" => "Minute",
                                        "Hour" => "Stunde",
                                        "Day" => "Tag",
                                        "Month" => "Monat",
                                        "Year" => "Jahr",
                                        "Week" => "Woche",
                                        "Quarter" => "Quartal",

                                        "Minutes" => "Minute(n)",
                                        "Hours" => "Stunde(n)",
                                        "Days" => "Tag(en)",
                                        "Months" => "Monat(en)",
                                        "Years" => "Jahr(en)",
                                        "Weeks" => "Woche(n)",
                                        "Quarters" => "Quartal(en)",

                                        "EveryDay" => "Jeden Tag",
                                        "EveryMonth" => "Jeden Monat",

                                        'Monday' => 'Montag',
                                        'Tuesday' => 'Dienstag',
                                        'Wednesday' => 'Mittwoch',
                                        'Thursday' => 'Donnerstag',
                                        'Friday' => 'Freitag',
                                        'Saturday' => 'Samstag',
                                        'Sunday' => 'Sonntag',

                                        "January" => "Januar",
                                        "February" => "Februar",
                                        "March" => "M&auml;rz",
                                        "April" => "April",
                                        "May" => "Mai",
                                        "June" => "Juni",
                                        "July" => "Juli",
                                        "August" => "August",
                                        "September" => "September",
                                        "October" => "Oktober",
                                        "November" => "November",
                                        "December" => "Dezember",

                                        'SuperAdmin' => "SuperAdmin",
                                        'Admin' => "Admin",
                                        'User' => "Nutzer",
                                        'Guest' => "Gast",

                                        "undefined" => "undefiniert",
                                        "man"  => "m&auml;nnlich",
                                        "woman"  => "weiblich",

                                        "MemberActivated" => "Empf&auml;nger aktiv",
                                        "MemberDeactivated" => "Empf&auml;nger deaktiviert",
                                        "MemberSubscribed" => "Empf&auml;nger angemeldet",
                                        "MemberOptInConfirmationPending" => "Empf&auml;nger hat Best&auml;tigungslink f&uuml;r die Anmeldung noch nicht angeklickt",
                                        "MemberOptOutConfirmationPending" => "Empf&auml;nger hat Best&auml;tigungslink f&uuml;r die Abmeldung noch nicht angeklickt",

                                        "MailSendPrepared" => "F&uuml;r Versand vorgesehen",
                                        "MailSendSent" => "versendet",
                                        "MailSendFailed" => "fehlgeschlagen",
                                        "MailSendPossiblySent" => "m&ouml;glicherweise versendet",

                                        "IF" => "WENN",
                                        "ELSE" => "ANSONSTEN",
                                        "ELSEIF" => "ANSONSTEN, WENN",
                                        "GIVEOUT" => "GIB AUS",
                                        "AND" => "UND",
                                        "OR" => "ODER",

                                        "contains" => "enth&auml;lt",
                                        "contains_not" => "enth&auml;lt nicht",
                                        "starts_with" => "beginnt mit",
                                        "REGEXP" => "regul&auml;rer Ausdruck",
                                        "IS" => "IS",

                                        "SUMTESTEMAILCOUNT" => "Test E-Mails", // must be UTF-8
                                        "SUMADMINNOTIFYMAILCOUNT" => "Informations E-Mails", // must be UTF-8
                                        "SUMOPTINCONFIRMATIONMAILCOUNT" => "Bestätigungs-E-Mails zur Anmeldung", // must be UTF-8
                                        "SUMOPTINCONFIRMEDMAILCOUNT" => "E-Mails nach Anmeldung", // must be UTF-8
                                        "SUMOPTOUTCONFIRMATIONMAILCOUNT" => "Bestätigungs-E-Mails zur Abmeldung", // must be UTF-8
                                        "SUMOPTOUTCONFIRMEDMAILCOUNT" => "E-Mails nach erfolgreicher Abmeldung", // must be UTF-8
                                        "SUMAUTORESPONDEREMAILCOUNT" => "Autoresponder E-Mails", // must be UTF-8
                                        "SUMBIRTHDAYRESPONDEREMAILCOUNT" => "Geburtstags-Responder E-Mails", // must be UTF-8
                                        "SUMFOLLOWUPRESPONDEREMAILCOUNT" => "Follow-Up-Responder E-Mails", // must be UTF-8
                                        "SUMEVENTRESPONDEREMAILCOUNT" => "Veranstaltungs-Responder E-Mails", // must be UTF-8
                                        "SUMCAMPAIGNEMAILCOUNT" => "E-Mailings/Newsletter", // must be UTF-8
                                        "SUMRSS2EMAILRESPONDEREMAILCOUNT" => "RSS2EMail-Responder E-Mails", // must be UTF-8
                                        "SUMEDITCONFIRMATIONMAILCOUNT" => "Bestätigungs-E-Mails zur Änderung", // must be UTF-8
                                        "SUMEDITCONFIRMEDMAILCOUNT" => "E-Mails nach Änderung", // must be UTF-8
                                        "SUMDISTRIBLISTEMAILCOUNT" => "Verteilerlisten E-Mails", // must be UTF-8

                                        "unknown_Action"  => "Unbekannter Wert f&uuml;r Variable Action.",

                                        "CantSaveFile" => "Kann Datei %s nicht speichern. SAFE_MODE probleme?",
                                        "CantOpenFile" => "Kann Datei %s nicht &ouml;ffnen. SAFE_MODE probleme?",

                                        "AdminNotifySubjectOnSubscribe" => "Anmeldung zur Empfängerliste '%s'",
                                        "AdminNotifySubjectOnUnubscribe" => "Abmeldung von der Empfängerliste '%s'",
                                        "AdminNotifySubjectOnEdit" => "Änderung des Empfängers in der Empfängerliste '%s'",
                                        "AdminNotifyBody" => "Empfängerdaten:",
                                        "AdminNotifyEMailOld" => "E-Mail-Adresse alt",

                                        "EntryCount" => "&nbsp;(%RECIPIENTCOUNT%&nbsp;Eintr&auml;ge)",
                                        "RecipientCount" => "&nbsp;(%RECIPIENTCOUNT%&nbsp;Empf&auml;nger)",
                                        "MKDIR_ERROR" => "Verzeichnis %s konnte nicht erstellt werden. Sie m&uuml;ssen das Verzeichnis manuell per FTP erstellen und bei Linux/Unix-Systemen mit den Rechten 0777 versehen.",
                                        "EMailCount" => "&nbsp;-&nbsp;E-Mail:&nbsp;%EMAILCOUNT%",
                                        "SMSCount" => "&nbsp;-&nbsp;SMS:&nbsp;%EMAILCOUNT%",
                                        "NoRecipients" => "Die Empf&auml;ngerliste enth&auml;lt keine Empf&auml;nger oder Empf&auml;nger nicht gefunden.",
                                        "ScriptURLDifferentFromReferer" => "Pr&uuml;fen Sie die URL des Aufrufs f&uuml;r das Script nl.php und defaultnewsletter.php. Der HTTP-Aufruf dieser %s-Installation scheint nicht mit den Angaben in der Datei config_paths.inc.php &uuml;bereinzustimmen, damit werden Parameter bei der An-/Abmeldung per Formular bei einem HTTP Redirect nicht &uuml;bermittelt (%s).",

                                        "SubscribeRejectLink" => "Cancel Anmeldung",
                                        "SubscribeConfirmationLink" => "Best&auml;tigungslink",
                                        "UnsubscribeRejectLink" => "Cancel Abmeldung",
                                        "UnsubscribeConfirmationLink" => "Best&auml;tigungslink",
                                        "UnsubscribeLink" => "Abmeldelink",
                                        "EditRejectLink" => "Cancel &Auml;nderung",
                                        "EditConfirmationLink" => "Best&auml;tigungslink",
                                        "EditLink" => "&Auml;nderungs-Link",
                                        'DateShort' => 'Datum kurz',
                                        'DateLong' => 'Datum lang',
                                        'Time' => 'Zeit',
                                        'RecipientId' => 'Empf&auml;nger-Id',
                                        'MailingListId' => 'Empf&auml;ngerlisten-Id',
                                        'SubscriptionStatus' => 'Anmeldestatus',
                                        'DateOfSubscription' => 'Datum der Anmeldung',
                                        'DateOfOptInConfirmation' => 'Datum Best&auml;tigung',
                                        'IPOnSubscription' => 'IP bei Anmeldung',
                                        'OrgMailSubject' => 'Org. Mail Betreff',
                                        'MembersAge' => 'Alter',
                                        'LastEMailSent' => 'Datum letzter E-Mail-Versand',
                                        'Days_to_Birthday' => 'Tage bis zum Geburtstag',
                                        'AltBrowserLink' => 'Alternativer Browserlink',
                                        'DistribListsName' => 'Name der Verteilerliste',
                                        'DistribListsDescription' => 'Beschreibung der Verteilerliste',
                                        'MailingListName' => 'Name der Empf&auml;ngerliste',
                                        'DistribSenderEMailAddress' => 'Versender der E-Mail',
                                        'DistribListsSubject' => 'Betreff der E-Mail',
                                        'DistribListsConfirmationLink' => 'Best&auml;tigungslink',

                                        'ReasonsForUnsubscriptionSurvey' => 'Umfrage Grund f&uuml;r Abmeldung',

                                        "DefaultSubscribeSubject" => "Ihre Anmeldung zu unserem Newsletterverteiler",
                                        "DefaultSubscribePlainMail" => "Hallo, \r\n\r\nvielen Dank für die Anmeldung zu unserem Newsletterverteiler. \r\n\r\nDamit wir Ihre E-Mail-Adresse zu unserem Verteiler hinzufügen können, klicken Sie bitte auf folgenden Link um Ihre Anmeldung abzuschließen. \r\n\r\n[SubscribeConfirmationLink] \r\n\r\nMöchten Sie nicht zu unserem Newsletterverteiler hinzugefügt werden, dann klicken Sie auf folgenden Link \r\n\r\n[SubscribeRejectLink] \r\n\r\n\r\nMit freundlichen Grüßen",
                                        "DefaultSubscribeHTMLMail" => '<html><head><title></title></head><body><div style="font-size: 10pt; font-family: Verdana">Hallo,<p>vielen Dank f&uuml;r die Anmeldung zu unserem Newsletterverteiler. Damit wir Ihre E-Mail-Adresse zu unserem Verteiler hinzuf&uuml;gen k&ouml;nnen, klicken Sie bitte auf folgenden Link um Ihre Anmeldung abzuschlie&szlig;en.</p><p><a href="[SubscribeConfirmationLink]">[SubscribeConfirmationLink]</a>.</p><p>M&ouml;chten Sie nicht zu unserem Newsletterverteiler hinzugef&uuml;gt werden, dann klicken Sie auf folgenden Link</p><p><a href="[SubscribeRejectLink]">[SubscribeRejectLink]</a>.</p><p>Mit freundlichen Gr&uuml;&szlig;en</p></div></body></html>',

                                        "DefaultUnsubscribeSubject" => "Ihre Abmeldung von unserem Newsletterverteiler",
                                        "DefaultUnsubscribePlainMail" => "Hallo,  \r\n\r\nwir haben die Abmeldung aus unserem Newsletterverteiler erhalten. Um den Abmeldevorgang abzuschließen klicken Sie bitte auf diesen Link \r\n\r\n[UnsubscribeConfirmationLink].  \r\n\r\nMöchten Sie nicht aus unserem Verteiler entfernt werden? Dann klicken Sie einfach auf diesen Link \r\n\r\n[UnsubscribeRejectLink]. \r\n\r\nMit freundlichen Grüßen",
                                        "DefaultUnsubscribeHTMLMail" => '<html><head><title></title></head><body><div style="font-size: 10pt; font-family: Verdana">Hallo,<p>wir haben die Abmeldung aus unserem Newsletterverteiler erhalten. Um den Abmeldevorgang abzuschlie&szlig;en klicken Sie bitte auf diesen Link <a href="[UnsubscribeConfirmationLink]">[UnsubscribeConfirmationLink]</a>.</p><p>M&ouml;chten Sie nicht aus unserem Verteiler entfernt werden? Dann klicken Sie einfach auf diesen Link <a href="[UnsubscribeRejectLink]">[UnsubscribeRejectLink]</a>.</p><p>Mit freundlichen Gr&uuml;&szlig;en</p></div></body></html>',

                                        "DefaultUnsSubscribeLinkHTML" => '<p>Wenn Sie diesen Newsletter nicht mehr erhalten wollen, dann klicken Sie auf <a href="%s">diesen Link</a>.</p>',
                                        "DefaultUnsSubscribeLinkTEXT" => "Wenn Sie diesen Newsletter nicht mehr erhalten wollen, dann klicken Sie auf diesen Link %s.",

                                        "DefaultEditLinkHTML" => '<p>Zum Ändern Ihrer Daten klicken Sie auf <a href="%s">diesen Link</a>.</p>',
                                        "DefaultEditLinkTEXT" => "Zum Ändern Ihrer Daten klicken Sie auf diesen Link %s.",

                                        "DefaultEditSubject" => "Ihre Änderung der Daten in unserem Newsletterverteiler",
                                        "DefaultEditPlainMail" => "Hallo, \r\n\r\nvielen Dank für die Änderung Ihrer Daten in unserem Newsletterverteiler. \r\n\r\nDamit wir Ihre Änderungen speichern können, klicken Sie bitte auf folgenden Link um Ihre Änderungen zu bestätigen. \r\n\r\n[EditConfirmationLink] \r\n\r\nMöchten Sie Ihre Änderungen nicht bestätigen, dann klicken Sie auf folgenden Link \r\n\r\n[EditRejectLink] \r\n\r\n\r\nMit freundlichen Grüßen",
                                        "DefaultEditHTMLMail" => '<html><head><title></title></head><body><div style="font-size: 10pt; font-family: Verdana">Hallo,<p>vielen Dank f&uuml;r die &Auml;nderung Ihrer Daten in unserem Newsletterverteiler. Damit wir Ihre &Auml;nderungen speichern k&ouml;nnen, klicken Sie bitte auf folgenden Link um Ihre &Auml;nderungen zu best&auml;tigen.</p><p><a href="[EditConfirmationLink]">[EditConfirmationLink]</a>.</p><p>M&ouml;chten Sie Ihre &Auml;nderungen nicht best&auml;tigen, dann klicken Sie auf folgenden Link</p><p><a href="[EditRejectLink]">[EditRejectLink]</a>.</p><p>Mit freundlichen Gr&uuml;&szlig;en</p></div></body></html>',

                                        "DefaultCaptchaText" => "Geben Sie das Wort, wie im nachfolgenden Feld angezeigt, ein (Spam-Schutz)*",
                                        "DefaultReCaptchaText" => "",

                                        "CronOptInOptOutExpirationCheck" => "Pr&uuml;fung Ablauf Opt-In/Opt-Out",
                                        "CronCronLogCleanUp" => "L&ouml;schen von alten CronJob-Log-Eintr&auml;gen",
                                        "CronMailingListStatCleanUp" => "L&ouml;schen von alten Empf&auml;ngerlisten-Statistik-Eintr&auml;gen",
                                        "CronResponderStatCleanUp" => "L&ouml;schen von Responder/E-Mailing-Versand-Eintr&auml;gen",
                                        "CronTrackingStatCleanUp" => "L&ouml;schen von Tracking-Daten der Responder/Mailings",
                                        "CronBounceChecking" => "Pr&uuml;fung auf unzustellbare E-Mails (Hard bounces)",
                                        "CronAutoresponderChecking" => "Pr&uuml;fung Autoresponder",
                                        "CronFollowUpResponderChecking" => "Pr&uuml;fung Follow-Up-Responder",
                                        "CronBirthdayResponderChecking" => "Pr&uuml;fung Geburtstags-Responder",
                                        "CronEventResponderChecking" => "Pr&uuml;fung Veranstaltungs-Responder",
                                        "CronSendEngineChecking" => "Pr&uuml;fung auf zu versendende E-Mails",
                                        "CronCampaignChecking" => "Pr&uuml;fung auf anstehende E-Mailings",
                                        "Cron" => "Script-Fehler",
                                        "CronRSS2EMailResponderChecking" => "Pr&uuml;fung RSS2EMail-Responder",
                                        "CronAutoImport" => "Automatischer Empf&auml;nger-Import",
                                        "CronSplitTestChecking" => "Pr&uuml;fung auf anstehende Split Tests",
                                        "CronSMSCampaignChecking" => "Pr&uuml;fung auf anstehende SMS-Kampagnen",
                                        "CronDistribListChecking" => "Pr&uuml;fung Verteilerlisten",

                                        "OutqueueSourcenone" => "undefiniert",
                                        "OutqueueSourceAutoresponder" => "Autoresponder",
                                        "OutqueueSourceFollowUpResponder" => "Follow-Up-Responder",
                                        "OutqueueSourceBirthdayResponder" => "Geburtstags-Responder",
                                        "OutqueueSourceCampaign" => "E-Mailing",
                                        "OutqueueSourceEventResponder" => "Event-Responder",
                                        "OutqueueSourceRSS2EMailResponder" => "RSS2EMail-Responder",
                                        "OutqueueSourceSMSCampaign" => "SMS-Kampagne",
                                        "OutqueueSourceDistributionList" => "Verteilerliste",

                                        "MailFormatPlainText" => "Reine Text-E-Mail",
                                        "MailFormatHTML" => "Reine HTML-E-Mail",
                                        "MailFormatMultipart" => "multipart E-Mail",

                                        "PlainText" => "Text",
                                        "HTML" => "HTML",
                                        "Multipart" => "multipart",

                                        "MailPriorityLow" => "niedrige Priorit&auml;t",
                                        "MailPriorityNormal" => "normale Priorit&auml;t",
                                        "MailPriorityHigh" => "hohe Priorit&auml;t",

                                        "MailFormatNotApplicable" => "Die Zeichen im Betreff oder im Text-Teil der E-Mail k&ouml;nnen mit dem gew&auml;hlten Zeichensatz nicht versendet werden. &Auml;ndern Sie die E-Mail-Codierung auf UTF-8/Unicode.",
                                        "SMSTextFormatNotApplicable" => "Die verwendeten Zeichen k&ouml;nnen nicht in das iso-8859-1 Format konvertiert werden. Bitte entfernen Sie alle Sonderzeichen z.B. typographische Anf&uuml;hrungszeichen, so dass eine Konvertierung erfolgen kann.",

                                        "NoTemplateLoaded" => "Keine Vorlage geladen.",
                                        "NoTemplateLoadedHint" => "Klicken Sie auf die Schaltfläche<br /><br /><img src='images/templates24x24.gif' border='0' /><br /><br />um eine bestehende E-Mail-Vorlage zu laden.",

                                        "MySQLQueryEmptyResult" => "Die Abfrage lieferte kein Resultat",

                                        "ConfigFilesWriteable" => '<img src="images/icon_warning.gif" width="24" height="24" align="left" />WARNUNG: Die Dateien config.inc.php, config_db.inc.php ODER config_paths.inc.php sind &auml;nderbar. &Auml;ndern Sie die Rechte auf Nur LESEN f&uuml;r alle Nutzer inkl. des Besitzers der Datei (chmod 444 auf Linux/Unix-Systemen).',
                                        "UserPathsNotWriteable" => '<img src="images/icon_warning.gif" width="24" height="24" align="left" />WARNUNG: Die Nutzerverzeichnisse zur Ablage von Bildern, Anh&auml;ngen, Import-Dateien und Export-Dateien sind nicht beschreibbar. &Auml;ndern Sie die Rechte auf das Verzeichnis %s und Unterverzeichnisse export, file, image und import, so dass in das Verzeichnis geschrieben werden kann (chmod 777 auf Linux/Unix-Systemen).',

                                        "ChartNoDataText" => "Keine Daten vorhanden.",
                                        "PBarLoadingText" => "Bitte warten, das Chart wird erstellt...",

                                        "UpdateAvailableSubject" => 'Neue Version verf&uuml;gbar',
                                        "UpdateAvailable" => '<img src="images/icon_information.gif" width="24" height="24" align="left" alt="Update" />&nbsp;Es ist ein Update f&uuml;r PRODUCTAPPNAME verf&uuml;gbar, neue Version: %NEWVERSION%, %NEWVERSIONDATE%<br />&nbsp;Um das Update zu laden besuchen Sie die Seite <a href="PRODUCTURL" target="_blank">PRODUCTURL</a>.',

                                        "TrackingIPBlocking" => '(IP-Blocking aktiviert)',
                                        "MailingListPermissionsError" => 'Sie verf&uuml;gen nicht &uuml;ber die erforderlichen Rechte, die Empf&auml;nger dieser Empf&auml;ngerliste anzuschauen.',

                                        "PermissionsError" => 'Sie verf&uuml;gen nicht &uuml;ber die erforderlichen Rechte, diese Funktion auszuf&uuml;hren.',

                                        "GeoLiteCityDatMissing" => 'Die Datei geoip/GeoLiteCity.dat bzw. geoip/GeoLite2-City.mmdb ab PHP 5.3.1 wurde nicht gefunden oder konnte nicht ge&ouml;ffnet werden. Sie k&ouml;nnen die Datei unter http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz oder http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz laden und in das geoip-Verzeichnis auspacken.',
                                        "GeoConfirmedSubscribtions" => 'Best&auml;tigte Anmeldungen',
                                        "GeoOwnStation" => "Eigener Standort",
                                        "GeoStationOfSubscriber" => "Standort des Angemeldeten",
                                        "GeoStationOfOpener" => "Standort der &Ouml;ffnung",
                                        "GeoStationOfClick" => "Standort des Klicks auf einen Link",

                                        "ExportOptionalFields" => "<b>Optionale interne Felder exportieren (nicht importierbar)</b>",
                                        "ExportOptIsActive" => "Empf&auml;nger aktiv",
                                        "ExportOptSubscriptionStatus" => "Anmeldestatus",
                                        "ExportOptDateOfSubscription" => "Anmeldedatum",
                                        "ExportOptDateOfOptInConfirmation" => "Datum des Klicks auf Best&auml;tigungslink",
                                        "ExportOptIPOnSubscription" => "IP-Adresse bei Anmeldung",
                                        "ExportOptIdentString" => "Identifikationzeichenkette",
                                        "ExportOptLastEMailSent" => "Datum letzter E-Mail-Versand",
                                        "ExportOptBounceStatus" => "Bounce Status",
                                        "ExportOptSoftbounceCount" => "Anzahl Soft bounces",
                                        "ExportOptHardbounceCount" => "Anzahl Hard bounces",
                                        "ExportOptHistoryOfSendEMails" => "History der versendeten E-Mails",
                                        "ExportOptGroupsRelations" => "Gruppenzugeh&ouml;rigkeit des Empf&auml;ngers",

                                        "ImportErrorDuplicateEntry" => "E-Mail-Adresse '%s' bereits in Liste enthalten.",
                                        "ImportErrorEMailAddressIncorrect" => "E-Mail-Adresse '%s' ist syntaktisch inkorrekt.",
                                        "ImportErrorNoEMailAddress" => "'%s' ist keine E-Mail-Adresse.",
                                        "ImportErrorEMailAddressInGlobalBlockList" => "E-Mail-Adresse '%s' ist in globaler Blockliste.",
                                        "ImportErrorEMailAddressInLocalBlockList" => "E-Mail-Adresse '%s' ist in lokaler Blockliste.",
                                        "ImportErrorEMailAddressInGlobalDomainBlockList" => "E-Mail-Adresse '%s' ist in globaler Domain-Blockliste.",
                                        "ImportErrorEMailAddressInLocalDomainBlockList" => "E-Mail-Adresse '%s' ist in lokaler Domain-Blockliste.",
                                        "ImportErrorEMailAddressInECGList" => "E-Mail-Adresse '%s' ist in ECG-Liste.",
                                        "ImportErrorFileTypeNotAllowed" => "Der Dateityp kann nicht importiert werden, verwenden Sie nur CSV-Dateien/Text mit Trennzeichen.",

                                        "rsNAStartPageTitle" => "Newsletter-Archiv Startseite",
                                        "rsNAStartPageHeadline" => "Newsletter-Archiv der Mustermann GmbH",
                                        "rsNAYearsLabel" => "Jahr:",
                                        "rsNATextBehindYears" => "Klicken Sie auf ein Jahr um die Newsletter des jeweiligen Jahres anzuschauen.",
                                        "rsNAYearsHeaderlineSelect" => "Newsletter-Archiv [NewsletterYear]",
                                        "rsNANewsletterEntryText" => "Newsletter vom [NewsletterDate]",
                                        "rsNALinkLabelToMainArchive" => "Newsletter-Archiv-Hauptseite",
                                        "rsNALinkLabelPrev" => "Vorheriger",
                                        "rsNALinkLabelNext" => "N&auml;chster",
                                        "rsNAImpressumText" => "<p><b>Impressum</b></p>\n<p>Mustermann GmbH<br>\nMusterstra&szlig;e 11</p>\n<p><b>12345 Musterstadt</b></p>\n<p>E-Mail: mustermann@mustermann.de</p>\n<p>http://www.mustermann.de</p>\n<p>Tel.: 0123/123456<br>\nFax: 0123/123457</p>\n<p>Amtsgericht Musterstadt, HRB 12345<br>\nSteuernummer: 1234/56789<br>\nGesch&auml;ftsf&uuml;hrer: Max Mustermann</p>\n",
                                        "rsNAShowNewsletterWithoutFramesText" => "Newsletter au&szlig;erhalb des Frames anzeigen",
                                        "rsNAPrintingLabel" => "Drucken",

                                        "WinnerTypeWinnerOpens" => "anhand der &Ouml;ffnungen der E-Mail",
                                        "WinnerTypeWinnerClicks" => "anhand der Klicks auf Hyperlinks",
                                        "TestTypeTestSendToAllMembers" => "an alle zuf&auml;llig ausgew&auml;hlten Empf&auml;nger der Empf&auml;ngerliste in gleichen Gruppen.",
                                        "TestTypeTestSendToListPercentage" => "an %d%% der Empf&auml;nger der Empf&auml;ngerliste, die Gewinner-E-Mail wird nach %d %s an die &uuml;brigen Empf&auml;nger versendet.",

                                        "TabCaptionEMailAsHTML" => "E-Mail im HTML-Format",
                                        "TabCaptionEMailAsPlainText" => "E-Mail im Text-Format",
                                        "TabCaptionSMSAsPlainText" => "Text der SMS",
                                        "TabCaptionEMailAttachments" => "Anh&auml;nge der E-Mail",

                                        "PWReminderLinkNotFound" => "Es wurde kein passender Eintrag f&uuml;r diesen Anforderungslink gefunden oder dieser wurde bereits verwendet.",

                                        "SaveAsPNGFile" => "Als PNG-Datei speichern",
                                        "SaveAsJPEGFile" => "Als JPEG-Datei speichern",
                                        "ChartOptions" => "Optionen",

                                        "CantLoadCert" => "Zertifikat kann nicht geladen werden.",

                                        "GoogleDeveloperPublicKeyMissing" => 'Google API Schl&uuml;ssel wurde nicht in den Optionen angegeben, daher kann GoogleMaps nicht verwendet werden.',

                                        "CantRemoveFUResponderMails" => 'Das L&ouml;schen der Folge-E-Mails ist nicht m&ouml;glich, da der Follow-Up-Responder aktiviert ist oder sich Folge-E-Mails in der Ausgangswarteschlange befinden.',

                                        "AfterDoingAnAction" => "Nach Ausf&uuml;hrung der Aktion",

                                        "SampleUnsubscripeReasons" => "ich erhalte den Newsletter zu häufig.;ich finde die Inhalte des Newsletters nicht interessant.;der Newsletter wird bei mir nicht richtig dargestellt.;anderer Grund",

                                        "iso-8859-2" => "Kroat., Poln., Rum&auml;n., Slowak., Slowen., Tschech., Ungarisch (iso-8859-2)",
                                        "iso-8859-3" => "Esperanto, Galizisch, Maltesisch, T&uuml;rkisch (iso-8859-3)",
                                        "iso-8859-4" => "Estnisch, Lettisch und Litauisch (iso-8859-4)",
                                        "iso-8859-6" => "Arabisch (iso-8859-6)",
                                        "iso-8859-7" => "neugriechische Schrift (iso-8859-7)",
                                        "iso-8859-8" => "hebr&auml;ische Schrift (iso-8859-8)",
                                        "iso-8859-9" => "T&uuml;rkisch, wie 8859-1 anstelle der isl&auml;nd. Sonderz. t&uuml;rk. Zeichen (iso-8859-9)",
                                        "iso-8859-10" => "Gr&ouml;nl&auml;ndisch (Inuit) und Lappisch (Sami). (iso-8859-10)",
                                        "windows-1250" => "Zentraleurop&auml;isch (windows-1250)",
                                        "windows-1251" => "Bulgarisch, Mazedonisch, Russisch, Serbisch, Ukrainisch (windows-1251)",
                                        "windows-1252" => "US/Westeurop&auml;isch (windows-1252)",
                                        "windows-1253" => "Griechisch (windows-1253)",
                                        "windows-1254" => "T&uuml;rkisch (windows-1254)",
                                        "windows-1255" => "Hebr&auml;isch (windows-1255)",
                                        "windows-1256" => "Arabisch (windows-1256)",
                                        "windows-1257" => "Baltisch (windows-1257)",
                                        "windows-1258" => "Vietnamesisch (windows-1258)",
                                        "KOI8-R" => "Kyrillisch (KOI8-R)",
                                        "KOI8-U" => "Kyrillisch (KOI8-U)",
                                        "KOI8-RU" => "Kyrillisch (KOI8-RU)"

                                       );

?>
