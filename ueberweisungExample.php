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
	 * Mit Hilfe eines Ausführungs-Offsets können Sie definieren, wann die Buchung durchgeführt wird. Die Anzahl 
	 * der übergebenen Tage wird auf den aktuellen Kalendertag addiert
	 * 
	 * Beispiel 1
	 * heute = 1. Juni 2013
	 * Offset nicht übergeben
	 * Ausführung -> Heute bzw. nächst möglich
	 * 
	 * Beispiel 2
	 * heute = 1. Juni 2013
	 * Offset 3
	 * Ausführung -> 4. Juni 2013
	 */ 
	$creator->setAusfuehrungOffset(7);
	
	// Erzeugung einer neuen Überweisung
	$buchung = new SepaBuchung();
	// gewünschter Überweisungsbetrag
	$buchung->setBetrag(10);
	// gewünschte End2End Referenz (OPTIONAL)
	$buchung->setEnd2End('ID-00002');
	// BIC des Empfängerinstituts
	$buchung->setBic('EMPFAENGERBIC');
	// Name des Zahlungsempfängers
	$buchung->setName('Mustermann, Max');
	// IBAN des Zahlungsmpfängers
	$buchung->setIban('DE1234566..');
	// gewünschter Verwendungszweck (OPTIONAL)
	$buchung->setVerwendungszweck('Test Buchung');
	// Buchung zur Liste hinzufügen
	$creator->addBuchung($buchung); 
	
	// Dies kann beliebig oft wiederholt werden ...
	$buchung = new SepaBuchung();
	$buchung->setBetrag(7);
	$buchung->setBic('EMPFAENGERBIC');
	$buchung->setName('Mustermann, Max');
	$buchung->setIban('DE1234566..');
	$creator->addBuchung($buchung);
	
	// Nun kann die XML-Datei über den Aufruf der entsprechenden Methode generiert werden
	$sepaxml = $creator->generateSammelueberweisungXml();
	echo $sepaxml;
	file_put_contents('sepaueberweisung-example.xml', $sepaxml);
	$creator->validateUeberweisungXml('sepaueberweisung-example.xml');
	$creator->printXmlErrors();
?>
