 SepaXmlCreator - by Thomas Schiffler.de modified by Ilya Beliaev (http://blog.php-dev.info)
 http://www.thomasschiffler.de/tag/sepa-xml-creator-php/
=========================================================

Einfache PHP-Klasse zur Erstellung von SEPA-konformen XML Buchungssätzen zur Übergabe an die kontoführenden Institute.
Derzeit umfasst die Klasse die folgenden Funktionen:

1. Erstellung von SEPA Sammelüberweisungen
Klasse: SepaXmlCreator
Methode: generateSammelueberweisungXml
Beispiel-Code: siehe ueberweisungExample.php

2. Erstellung von SEPA Basislastschriften / Sammellastschriften
Klasse: SepaXmlCreator
Methode: generateBasislastschriftXml
Beispiel-Code: siehe lastschriftExample.php


=========================================================
Changelog: 

05.04.2015:

- Added method chaining to all relevant methods
- implemented new methods on english language, for replacing of old methods.
- Added some performance/standard improvements for methods
- Marked some not oop conform methods as deprecated and added new methods, which are oop conform.
- backward compatibility is available 
- changed some var names to english language
- marked all changes as version 0.2
- Currency-Attribute of "SepaBuchung" have now a higher priority than Currency of SepaXmlCreator, so when it is set, it will be used.
- NOTICE!!!! in next Version classes will be located in own files!

03.12.2013
- fixes Ausführungsdatum anstatt Offset zusätzlich ermöglichen

25.11.2013
- Umsetzung der SEPA Basislastschriften
- Methode 'setDebitorValues' auf Deprecated gesetzt -> setAccountValues nutzen
- Zahlenformat bei Beträgen > 1000 korrigiert

14.11.2013
- End2End Referenz auf NOTPROVIDED sofern nicht übergeben

03.09.2013 
- Veröffentlichung der Initialversion
- Sammelüberweisungen
