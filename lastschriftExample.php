<?php 

	// Einbindung der SepaXmlCreator-Klasse
	require_once 'SepaXmlCreator.php';

	// Erzeugen einer neuen Instanz
	$creator = new SepaXmlCreator();
	
	/*
	 * Mit den Account-Werten wird das eigene Konto beschrieben
	 * erster Parameter = Name
	 * zweiter Parameter = IBAN
	 * dritter Paramenter = BIC
	 */ 
	$creator->setAccountValues('mein Name', 'meine IBAN', 'meine BIC');
	
	/*
	 * Setzen Sie von der Bundesbank übermittelte Gläubiger-ID 
	 */
	$creator->setGlaeubigerId("DE98ZZZ09999999999");
	
	/*
	 * Mit Hilfe eines Ausführungs-Offsets können Sie definieren, wann die Lastchrift gezogen wird. Die Anzahl 
	 * der übergebenen Tage wird auf den aktuellen Kalendertag addiert
	 * 
	 * Beispiel 1
	 * heute = 1. Juni 2013
	 * Offset nicht übergeben
	 * Ausführung -> Heute bzw. nächst möglich
	 * 
	 * Beispiel 1
	 * heute = 1. Juni 2013
	 * Offset 3
	 * Ausführung -> 4. Juni 2013
	 */ 
	$creator->setAusfuehrungOffset(7);
	
	// Erzeugung einer neuen Buchungssatz
	$buchung = new SepaBuchung();
	// gewünschter Einzugsbetrag
	$buchung->setBetrag(10);
	// gewünschte End2End Referenz (OPTIONAL)
	$buchung->setEnd2End('ID-00002');
	// BIC des Zahlungspflichtigen Institutes
	$buchung->setBic('EMPFAENGERBIC');
	// Name des Zahlungspflichtigen
	$buchung->setName('Mustermann, Max');
	// IBAN des Zahlungspflichtigen
	$buchung->setIban('DE1234566..');
	// gewünschter Verwendungszweck (OPTIONAL)
	$buchung->setVerwendungszweck('Test Buchung');
	// Referenz auf das vom Kunden erteilte Lastschriftmandat
	// ID = MANDAT0001
	// Erteilung durch Kunden am 20. Mai 2013
	// False = seit letzter Lastschrift wurde am Mandat nichts geändert
	$buchung->setMandat("MANDAT0001", "2013-05-20", false);
	// Buchung zur Liste hinzufügen
	$creator->addBuchung($buchung); 
	
	// Dies kann beliebig oft wiederholt werden ...
	$buchung = new SepaBuchung();
	$buchung->setBetrag(7);
	$buchung->setBic('EMPFAENGERBIC');
	$buchung->setName('Mustermann, Max');
	$buchung->setIban('DE1234566..');
	// weitere felder nicht übergeben = heutige erteilung
	$buchung->setMandat("MANDAT0002");
	$creator->addBuchung($buchung);
	
	// Nun kann die XML-Datei über den Aufruf der entsprechenden Methode generiert werden
	echo $creator->generateBasislastschriftXml();
	
?>
