<?php
/*
 * SepaXmlCreator - by Thomas Schiffler.de
 * http://www.ThomasSchiffler.de/2013_09/code-schnipsel/sepa-sammeluberweisung-xml-datei-mit-php-erstellen
 *
 * Copyright (c) 2013 Thomas Schiffler (http://www.ThomasSchiffler.de
 * GPL (http://www.opensource.org/licenses/gpl-license.php) license.
 *
 */

class SepaBuchung{
	var $end2end, $iban, $bic, $kontoinhaber, $verwendungszweck, $amount;

	function __construct() {
		$this->end2end = "NOTPROVIDED";
	}

	function setEnd2End($end2end) {
		$this->end2end = $end2end;
	}

	function setIban($iban) {
		$this->iban = str_replace(' ','',$iban);
	}

	function setBic($bic) {
		$this->bic = $bic;
	}

	function setName($name) {
		$this->kontoinhaber = $name;
	}

	function setVerwendungszweck($verwendungszweck) {
		$this->verwendungszweck = $verwendungszweck;
	}

	function setBetrag($betrag) {
		$this->amount = $betrag;
	}
}

class SepaXmlCreator {
	var $buchungssaetze = array();

	var $debitorName, $debitorIban, $debitorBic;
	var $offset = 0;
	var $waehrung = "EUR";

	function setDebitorValues($name, $iban, $bic) {
		$this->debitorName = $name;
		$this->debitorIban = $iban;
		$this->debitorBic = $bic;
	}

	function setCurrency($currency) {
		$this->waehrung = $currency;
	}

	function addBuchung($buchungssatz) {
		array_push($this->buchungssaetze, $buchungssatz);
	}

	function setAusfuehrungOffset($offset) {
		$this->offset = $offset;
	}

	function generateSammelueberweisungXml() {
		$dom = new DOMDocument('1.0', 'utf-8');

		// Build Document-Root
		$document = $dom->createElement('Document');
		$document->setAttribute('xmlns', 'urn:iso:std:iso:20022:tech:xsd:pain.001.002.03');
		$document->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$document->setAttribute('xsi:schemaLocation', 'urn:iso:std:iso:20022:tech:xsd:pain.001.002.03 pain.001.002.03.xsd');
		$dom->appendChild($document);

		// Build Content-Root
		$content = $dom->createElement('CstmrCdtTrfInitn');
		$document->appendChild($content);

		// Build Header
		$header = $dom->createElement('GrpHdr');
		$content->appendChild($header);

		$creationTime = time();

		// Msg-ID
		$header->appendChild($dom->createElement('MsgId', $this->debitorBic . '00' . date('YmdHis', $creationTime)));
		$header->appendChild($dom->createElement('CreDtTm', date('Y-m-d', $creationTime) . 'T' . date('H:i:s', $creationTime) . '.000Z'));
		$header->appendChild($dom->createElement('NbOfTxs', count($this->buchungssaetze)));
		$header->appendChild($initatorName = $dom->createElement('InitgPty'));
		$initatorName->appendChild($dom->createElement('Nm', $this->debitorName));

		// PaymentInfo
		$paymentInfo = $dom->createElement('PmtInf');
		$content->appendChild($paymentInfo);

		$paymentInfo->appendChild($dom->createElement('PmtInfId', 'PMT-ID0-' . date('YmdHis', $creationTime)));
		// TRF = Transfer (Überweisung), TRA = CreditTransfer (Lastschrift)
		$paymentInfo->appendChild($dom->createElement('PmtMtd', 'TRF'));
		$paymentInfo->appendChild($dom->createElement('BtchBookg', 'true'));
		$paymentInfo->appendChild($dom->createElement('NbOfTxs', count($this->buchungssaetze)));
		$paymentInfo->appendChild($dom->createElement('CtrlSum', number_format($this->getUmsatzsumme(), 2, '.', '')));
		$paymentInfo->appendChild($tmp1 = $dom->createElement('PmtTpInf'));
		$tmp1->appendChild($tmp2 = $dom->createElement('SvcLvl'));
		$tmp2->appendChild($dom->createElement('Cd', 'SEPA'));

		// Ausführungsdatum berechnen
		$ausfuehrungszeit = $creationTime;
		if ($this->offset > 0) {
			$ausfuehrungszeit = $ausfuehrungszeit + (24 * 3600 * $this->offset);
		}
		$paymentInfo->appendChild($dom->createElement('ReqdExctnDt', date('Y-m-d', $ausfuehrungszeit)));

		// Debitor Daten
		$paymentInfo->appendChild($tmp1 = $dom->createElement('Dbtr'));
		$tmp1->appendChild($dom->createElement('Nm', $this->debitorName));
		$paymentInfo->appendChild($tmp1 = $dom->createElement('DbtrAcct'));
		$tmp1->appendChild($tmp2 = $dom->createElement('Id'));
		$tmp2->appendChild($dom->createElement('IBAN', $this->debitorIban));
		$paymentInfo->appendChild($tmp1 = $dom->createElement('DbtrAgt'));
		$tmp1->appendChild($tmp2 = $dom->createElement('FinInstnId'));
		$tmp2->appendChild($dom->createElement('BIC', $this->debitorBic));

		$paymentInfo->appendChild($dom->createElement('ChrgBr', 'SLEV'));

		// Buchungssätze hinzufügen
		foreach ($this->buchungssaetze as $buchungssatz) {
			$paymentInfo->appendChild($buchung = $dom->createElement('CdtTrfTxInf'));

			// End2End setzen
			if (isset($buchungssatz->end2end)) {
				$buchung->appendChild($tmp1 = $dom->createElement('PmtId'));
				$tmp1->appendChild($dom->createElement('EndToEndId', $buchungssatz->end2end));
			}

			// Betrag
			$buchung->appendChild($tmp1 = $dom->createElement('Amt'));
			$tmp1->appendChild($tmp2 = $dom->createElement('InstdAmt', number_format($buchungssatz->amount, 2, '.', '')));
			$tmp2->setAttribute('Ccy', $this->waehrung);

			// Institut
			$buchung->appendChild($tmp1 = $dom->createElement('CdtrAgt'));
			$tmp1->appendChild($tmp2 = $dom->createElement('FinInstnId'));
			$tmp2->appendChild($dom->createElement('BIC', $buchungssatz->bic));

			// Inhaber
			$buchung->appendChild($tmp1 = $dom->createElement('Cdtr'));
			$tmp1->appendChild($dom->createElement('Nm', $buchungssatz->kontoinhaber));

			// IBAN
			$buchung->appendChild($tmp1 = $dom->createElement('CdtrAcct'));
			$tmp1->appendChild($tmp2 = $dom->createElement('Id'));
			$tmp2->appendChild($dom->createElement('IBAN', $buchungssatz->iban));

			// Verwendungszweck
			$buchung->appendChild($tmp1 = $dom->createElement('RmtInf'));
			$tmp1->appendChild($dom->createElement('Ustrd', $buchungssatz->verwendungszweck));
		}

		// XML exportieren
		return $dom->saveXML();
	}

	function getUmsatzsumme() {
		$betrag = 0;

		foreach ($this->buchungssaetze as $buchungssatz) {
			$betrag = $betrag + $buchungssatz->amount;
		}

		return $betrag;
	}
}



?>
