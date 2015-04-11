#!/usr/bin/env php
<?php

/*
 * usage:
 * - Parameter anpassen
 * - Ausführen:
 *
 * ./ueberweisungFromCSV filename.csv "Verwendungszweck"
 *
 * Die Ausgabe ist in filename.xml zu finden.
 *
 */

require_once 'SepaXmlCreator.php';

if ($argc !== 3) {
    echo "    usage: ./ueberweisungFromCSV filename.csv \"Verwendungszweck\"\n";
    exit(-1);
}

$csvfilename = $argv[1];
$verwendungszweck = $argv[2];
$xmlfilename = preg_replace('"\.csv$"', '.xml', $csvfilename);

// Parameter
$separator = ';';
$meinName = 'Klaus Mustermann';
// from http://www.iban-bic.com/sample_accounts.html
$meineIBAN = 'DE12500105170648489890';
$meineBIC = 'KARSDE66XXX';
$end2end = FALSE;   // should be part of csv ?



// Programm
$creator = new SepaXmlCreator();
$creator->setAccountValues($meinName, $meineIBAN, $meineBIC);

$count = 0;
$file = fopen($csvfilename, 'r');
while (($data = fgetcsv($file, 8000, $separator)) !== FALSE) {
    $num = count($data);
    // ignore empty lines
    if ($num == 1) {
        continue;
    }
    $count++;
    $empfName = $data[0];
    $empfIBAN = $data[1];
    $empfBIC = $data[2];
    // convert ',' to '.' and hope for no rounding errors
    $empfBetrag = str_replace(',', '.', $data[3]);

    // echo "DEBUG: line " . $count . " / " . $empfName . " / " . $empfIBAN . " / " . $empfBIC . " / " . $empfBetrag . "\n";
    $buchung = new SepaBuchung();
    if ($end2end !== FALSE) {
        $buchung->setEnd2End($end2end);
    }
    $buchung->setBetrag($empfBetrag);
    $buchung->setBic($empfBIC);
    $buchung->setName($empfName);
    $buchung->setIban($empfIBAN);
    $buchung->setVerwendungszweck($verwendungszweck);
    $creator->addBuchung($buchung);
}
fclose($file);
echo $count . " Überweisungen bearbeitet\n";

$sepaxml = $creator->generateSammelueberweisungXml();
file_put_contents($xmlfilename, $sepaxml);
echo "Outputdatei in " . $xmlfilename . "\n";
$creator->validateUeberweisungXml($xmlfilename);
$creator->printXmlErrors();
?>
