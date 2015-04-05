<?php
/*
 * SepaXmlCreator - by Thomas Schiffler.de modified by Ilya Beliaev
 * http://www.ThomasSchiffler.de/2013_09/code-schnipsel/sepa-sammeluberweisung-xml-datei-mit-php-erstellen
 *
 * Copyright (c) 2013 Thomas Schiffler (http://www.ThomasSchiffler.de
 * GPL (http://www.opensource.org/licenses/gpl-license.php) license.
 *
 */
class SepaBuchung{
    
    private $end2end = "NOTPROVIDED";
    private $iban = "";
    private $bic = "";
    private $kontoinhaber = "";
    private $verwendungszweck = "";
    private $amount = 0.00;
    private $currency = "EUR";

    /**
     * Mandate Information for direct Debig
     */
    private $mandatId;
    private $mandatDatum;
    private $mandatAenderung;

    /**
     * Set end 2 end reference
     * @param string $end2end
     * @return \SepaBuchung
     */
    public function setEnd2End($end2end) {
        $this->end2end = $this->normalizeString($end2end);
        return $this;
    }

    /**
     * Get end 2 end reference
     * @return string
     */
    public function getEnd2End(){
        return $this->end2end;
    }

    /**
     * Set IBAN of customer
     * @param string $iban
     * @return \SepaBuchung
     */
    public function setIban($iban) {
        $this->iban = str_replace(' ','',$iban);
        return $this;
    }

    /**
     * Get IBAN of customer
     * @return string
     */
    public function getIban(){
        return $this->iban;
    }

    /**
     * Set bic of customer
     * @param string $bic
     * @return \SepaBuchung
     */
    public function setBic($bic) {
        $this->bic = $bic;
        return $this;
    }

    /**
     * get Bic of customer
     * @return type
     */
    public function getBic(){
        return $this->bic;
    }

    /**
     * Set Name of customer
     * @param string $name
     * @return \SepaBuchung
     */
    public function setName($name) {
        $this->kontoinhaber = $this->normalizeString($name);
        return $this;
    }

    /**
     * Get Name of customer
     * @return string
     */
    public function getName(){
        return $this->kontoinhaber;
    }

    /**
     * set Transaction information 
     * @deprecated since version 0.2
     * @deprecated use setTransactionInformation instead
     * @param string $verwendungszweck
     * @return \SepaBuchung
     */
    public function setVerwendungszweck($verwendungszweck) {
        $this->verwendungszweck = $this->normalizeString($verwendungszweck);
        return $this;
    }

    /**
     * Get Transaction information for direct debit
     * @deprecated since version 0.2
     * @deprecated use getTransactionInformation instead
     * @return string
     */
    public function getVerwendungszweck(){
        return $this->verwendungszweck;
    }

    /**
     * Set Transaction information for direct debit
     * @param string $transActionInformation
     * @return \SepaBuchung
     */
    public function setTransactionInformation($transActionInformation){
        $this->verwendungszweck = $this->normalizeString($transActionInformation);
        return $this;
    }

    /**
     * Get Transaction information for direct debit
     * @return string
     */
    public function getTransactionInformation(){
        return $this->verwendungszweck;
    }

    /**
     * Set Payment Amount 
     * @deprecated since version 0.2
     * @deprecated use setAmount instead
     * @param double $betrag
     * @return \SepaBuchung
     */
    public function setBetrag($betrag) {
        $this->amount = $betrag;
        return $this;
    }

    /**
     * Get payment amount
     * @deprecated since version 0.2
     * @deprecated use getAmount instead
     * @return double
     */
    public function getBetrag(){
        return $this->amount;
    }

    /**
     * Set Payment amount
     * @param double $amount
     * @return \SepaBuchung
     */
    public function setAmount($amount){
        $this->amount = (double)$amount;
        return $this;
    }

    /**
     * Get Payment amount
     * @return double
     */
    public function getAmount(){
        return $this->amount;
    }

    /**
     * Set Currency of direct debit
     * @param string $currency
     * @return \SepaBuchung
     */
    public function setCurrency($currency){
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get Currency
     * @return string
     */
    public function getCurrency(){
        return $this->currency;
    }
	
    /**
     * Methode zum Setzen des Mandates - notwendig beim Generieren von Lastschriften. Wenn gew�nscht kann
     * nur die Mandats-ID gesetzt werden, hierbei wird das aktuelle Tagesdatum als Datum der Mandatserteilung
     * genommen. Das Datum ist im Format (YYYY-mm-dd - bsp. 2013-11-02 zu �bergeben)
     * 
     * @param String $id
     * @param String $mandatDatum
     * @param boolean $mandatAenderung - true wenn das Mandat seit letzer Erteilung ge�ndert wurde
     * @deprecated since version 0.2
     * @deprecated use setMandatId, setMandateChange and setDueDate instead
     */
    public function setMandat($id, $mandatDatum = null, $mandatAenderung = true) {
        $this->setMandateId($id);
        $this->setMandateChange($mandatAenderung);
        $this->setMandateDate($mandatDatum);
        return $this;
    }
    
    /**
     * Set mandate ID
     * @param string $mandatId
     */
    public function setMandateId($mandatId){
        $this->mandatId = $mandatId;
        return $this;
    }
    
    /**
     * Get Mandate ID 
     * @return type
     */
    public function getMandateId(){
        return $this->mandatId;
    }
    
    /**
     * Set marking flag for change
     * @param string $mandatAenderung
     * @return \SepaBuchung
     */
    public function setMandateChange($mandatAenderung = false){
        $this->mandatAenderung = (bool)$mandatAenderung;
        return $this;
    }
    
    /**
     * get marking flag for change
     * @return string
     */
    public function getMandateChange(){
        return $this->mandatAenderung;
    }
    
    /**
     * Set mandate date
     * @param string $mandateDate
     * @return \SepaBuchung
     */
    public function setMandateDate($mandateDate){
        if(empty($mandateDate)){
            $this->mandatDatum = date('Y-m-d');	
        }else{
            $this->mandatDatum = $mandateDate;
        }
        
        return $this;
    }
    
    /**
     * get Mandate date
     * @return string
     */
    public function getMandateDate(){
        return $this->mandatDatum;
    }

    private function normalizeString($input) {
        // Only below characters can be used within the XML tags according the guideline.
        // a b c d e f g h i j k l m n o p q r s t u v w x y z
        // A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
        // 0 1 2 3 4 5 6 7 8 9
        // / - ? : ( ) . , � +
        // Space
        //
        // Create a normalized array and cleanup the string $XMLText for unexpected characters in names
        $normalizeChars = array(
            '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'Ae', '�'=>'AE', '�'=>'C',
            '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I', '�'=>'I', '�'=>'Eth',
            '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O',
            '�'=>'U', '�'=>'U', '�'=>'U', '�'=>'Ue', '�'=>'Y',

            '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'ae', '�'=>'ae', '�'=>'c',
            '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i', '�'=>'i', '�'=>'eth',
            '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'oe', '�'=>'o',
            '�'=>'u', '�'=>'u', '�'=>'u', '�'=>'ue', '�'=>'y',

            '�'=>'ss', '�'=>'thorn', '�'=>'y',

            '&'=>'u.', '@'=>'at', '#'=>'h', '$'=>'s', '%'=>'perc', '^'=>'-','*'=>'-'
        );

        $output = strtr($input, $normalizeChars);
        return $output;
    }
    
    /**
     * Get Copy of all attributes as array
     * @return array
     */
    public function getArrayCopy(){
        return get_object_vars($this);
    }
}

/*
 * SepaXmlCreator - by Thomas Schiffler.de
 * http://www.ThomasSchiffler.de/2013_09/code-schnipsel/sepa-sammeluberweisung-xml-datei-mit-php-erstellen
 *
 * Copyright (c) 2013 Thomas Schiffler (http://www.ThomasSchiffler.de
 * GPL (http://www.opensource.org/licenses/gpl-license.php) license.
 *
 */

class SepaXmlCreator {
    
    const DIRECTDEBITING = 1;
    const MASSPAYMENT = 2;
    
    private $allowedModes = array(
        self::DIRECTDEBITING,
        self::MASSPAYMENT,
    );
    
    private $buchungssaetze = array();
    private $accountName;
    private $accountIban;
    private $accountBic;
    private $offset = 0;
    private $fixedDate;
    private $waehrung;
    //Mode = 1 -> payment / Mode = 2 -> direct debit
    private $mode = 1;
    private $isFirst = true;
    private $glaeubigerId;

    // XML-Errors
    private $xmlerrors;
    
    private function getExecutionModes(){
        return $this->allowedModes;
    }

    /**
     * @deprecated since version 0.1
     * @param string $name 
     * @param string $iban
     * @param string $bic
     */
    public function setDebitorValues($name, $iban, $bic) {
        trigger_error('Use setAccountValues($name, $iban, $bic) instead', E_USER_DEPRECATED);

        $this->setAccountValues($name, $iban, $bic);
        return $this;
    }

    /**
     * Set Account values
     * @param string $name
     * @param string $iban
     * @param string $bic
     * @deprecated since version 0.2
     * @deprecated use setAccountName, setAccountIban and SetAccountBic instead
     * @return \SepaXmlCreator
     */
    public function setAccountValues($name, $iban, $bic) {
        $this->setAccountName($name)
             ->setAccountIban($iban)
             ->setAccountBic($bic);
        return $this;
    }
    
    /**
     * Set Account name
     * @param string $accountName
     * @return \SepaXmlCreator
     */
    public function setAccountName($accountName){
        $this->accountName = $accountName;
        return $this;
    }
    
    /**
     * Get Account name
     * @return string
     */
    public function getAccountName(){
        return $this->accountName;
    }
    
    /**
     * Set Account IBAN
     * @param string $accountIban
     * @return \SepaXmlCreator
     */
    public function setAccountIban($accountIban){
        $this->accountIban = $accountIban;
        return $this;
    }
    
    /**
     * Get Account IBAN
     * @return string
     */
    public function getAccountIban(){
        return $this->accountIban;
    }

    /**
     * Set Account BIC
     * @param string $accountBic
     * @return \SepaXmlCreator
     */
    public function setAccountBic($accountBic){
        $this->accountBic = $accountBic;
        return $this;
    }
    
    /**
     * Get Account BIC
     * @return string
     */
    public function getAccountBic(){
        return $this->accountBic;
    }
    
    /**
     * Set Creditor ID
     * @deprecated since version 0.2
     * @deprecated use setCreditorId instead
     * @param string $glaeubigerId
     */
    public function setGlaeubigerId($glaeubigerId) {
        $this->glaeubigerId = $glaeubigerId;
    }
    
    /**
     * Set Creditor ID
     * @param string $creditorId
     * @return \SepaXmlCreator
     */
    public function setCreditorId($creditorId){
        $this->glaeubigerId = $creditorId;
        return $this;
    }
    
    /**
     * Get CreditorId
     * @return string
     */
    public function getCreditorId(){
        return $this->glaeubigerId;
    }

    /**
     * Set Currency of Payments
     * @param type $currency
     */
    public function setCurrency($currency) {
        $this->waehrung = $currency;
        return $this;
    }
    
    /**
     * Get Currency of Payments
     * @return string
     */
    public function getCurrency(){
        return $this->waehrung;
    }

    /**
     * Add new Payment to List
     * @deprecated since version 0.2
     * @deprecated use addPayment instead
     * @param \SepaBuchung $buchungssatz
     * @return \SepaXmlCreator
     */
    public function addBuchung($buchungssatz) {
        $this->buchungssaetze[] = $buchungssatz;
        return $this;
    }
    
    /**
     * Set a list of SepaPayment objects
     * @param \SepaBuchung[] $payments
     * @return \SepaXmlCreator
     */
    public function setPayments(array $payments){
        $this->buchungssaetze = $payments;
        return $this;
    }
    
    /**
     * Get Payments as array
     * @return \SepaBuchung[]
     */
    public function getPayments(){
        return $this->buchungssaetze;
    }
    
    /**
     * Add new Payment to List
     * @param SepaBuchung $payment
     * @return \SepaXmlCreator
     */
    public function addPayment(SepaBuchung $payment){
        $this->buchungssaetz[] = $payment;
        return $this;
    }
    
    /**
     * Set the offset for Execution
     * @deprecated since version 0.2
     * @deprecated use setExecutionOffset instead
     * @param integer $offset
     * @return \SepaXmlCreator
     */
    public function setAusfuehrungOffset($offset) {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * Set the offset for Execution
     * @param integer $offset
     * @return \SepaXmlCreator
     */
    public function setExecutionOffset($offset){
        $this->offset = (int)$offset;
        return $this;
    }
    
    /**
     * Get the offset for Execution
     * @return integer
     */
    public function getExecutionOffset(){
        return $this->offset;
    }
    

    /**
     * Set Execution Date
     * @deprecated since version 0.2
     * @deprecated use setExecutionDate instead
     * @param string $datum
     * @return \SepaXmlCreator
     */
    public function setAusfuehrungDatum($datum) {
        $this->fixedDate = $datum;
        return $this;
    }
    
    /**
     * Set Execution Date
     * @param string $fixedDate
     * @return \SepaXmlCreator
     */
    public function setExecutionDate($fixedDate){
        $this->fixedDate = $fixedDate;
        return $this;
    }
    
    /**
     * Get Execution Date
     * @return string
     */
    public function getExecutionDate(){
        return $this->fixedDate;
    }
    
    /**
     * Set Execution Mode for Creator
     * @param integer $mode SepaXmlCreator::DIRECTDEBITING|SepaXmlCreator::MASSPAYMENT
     * @return \SepaXmlCreator
     */
    public function setExecutionMode($mode = self::DIRECTDEBITING){
        $exectionModes = $this->getExecutionModes();
        if(in_array($mode, $exectionModes)){
            $this->mode = $mode;
        }
        return $this;
    }
    
    /**
     * Get Execution Mode
     * @return integer
     */
    public function getExecutionMode(){
        return $this->mode;
    }

    /**
     * Generate XML for Mass Payment
     * @deprecated since version 0.2
     * @deprecated use generateMassPaymentXML instead
     * @return string
     */
    public function generateSammelueberweisungXml() {
        // Set Mode = 1 -> Sammelüberweisung
        $this->setExecutionMode(self::MASSPAYMENT);
        return $this->getGeneratedXml();
    }
    
    /**
     * Generate XML for Mass Payment
     * @return string
     */
    public function generateMassPaymentXML(){
        $this->setExecutionMode(self::MASSPAYMENT);
        return $this->getGeneratedXml();
    }

    /**
     * Generate Direct Debiting Xml
     * @deprecated since version 0.2
     * @deprecated use generateDirectDebitingXml instead
     * @return string
     */
    public function generateBasislastschriftXml() {
        // Set Mode = 2 -> Basislastschrift
        $this->setExecutionMode(self::DIRECTDEBITING);
        return $this->getGeneratedXml();
    }
    
    /**
     * Generate Direct Debiting Xml
     * @return string
     */
    public function generateDirectDebitingXml(){
        $this->setExecutionMode(self::DIRECTDEBITING);
        return $this->getGeneratedXml();
    }

    /**
     * Defines the Payment as returning payment
     * @deprecated since version 0.2
     * @deprecated use setReturningDirectDebiting instead
     * @return \SepaXmlCreator
     */
    public function setIsFolgelastschrift() {
        $this->isFirst = false;
        return $this;
    }
    
    /**
     * Defines the Payment as returning payment or not 
     * @param boolean $isFirst
     * @return \SepaXmlCreator
     */
    public function setReturningDirectDebiting($isFirst = false){
        $this->isFirst = $isFirst;
        return $this;
    }
    
    /**
     * Get Definition for Payment Returning or single
     * @return boolean
     */
    public function getReturningDirectDebiting(){
        return $this->isFirst;
    }

    public function getGeneratedXml() {	
        
        $mode = $this->getExecutionMode();
        $dom = new DOMDocument('1.0', 'utf-8');

        // Build Document-Root
        $document = $dom->createElement('Document');
        if ($mode == 2) {
            $document->setAttribute('xmlns', 'urn:iso:std:iso:20022:tech:xsd:pain.008.002.02');
            $document->setAttribute('xsi:schemaLocation', 'urn:iso:std:iso:20022:tech:xsd:pain.008.002.02 pain.008.002.02.xsd');
        } else {
            $document->setAttribute('xmlns', 'urn:iso:std:iso:20022:tech:xsd:pain.001.002.03');
            $document->setAttribute('xsi:schemaLocation', 'urn:iso:std:iso:20022:tech:xsd:pain.001.002.03 pain.001.002.03.xsd');
        }
        $document->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');		
        $dom->appendChild($document);

        // Build Content-Root
        if ($mode == 2) {
            $content = $dom->createElement('CstmrDrctDbtInitn');
        } else {
            $content = $dom->createElement('CstmrCdtTrfInitn');
        }

        $document->appendChild($content);

        // Build Header
        $header = $dom->createElement('GrpHdr');
        $content->appendChild($header);

        $creationTime = time();

        // Msg-ID
        $header->appendChild($dom->createElement('MsgId', $this->getAccountBic() . '00' . date('YmdHis', $creationTime)));
        $header->appendChild($dom->createElement('CreDtTm', date('Y-m-d', $creationTime) . 'T' . date('H:i:s', $creationTime) . '.000Z'));
        $header->appendChild($dom->createElement('NbOfTxs', count($this->getPayments())));
        $header->appendChild($initatorName = $dom->createElement('InitgPty'));
        $initatorName->appendChild($dom->createElement('Nm', $this->getAccountName()));

        // PaymentInfo
        $paymentInfo = $dom->createElement('PmtInf');
        $content->appendChild($paymentInfo);

        $paymentInfo->appendChild($dom->createElement('PmtInfId', 'PMT-ID0-' . date('YmdHis', $creationTime)));
        switch ($mode) {
            case 2:
                // 2 = Basislastschrift
                $paymentInfo->appendChild($dom->createElement('PmtMtd', 'DD'));
                break;
            default:
                // Default / 1 = Überweisung
                $paymentInfo->appendChild($dom->createElement('PmtMtd', 'TRF'));
                break;
        }

        $paymentInfo->appendChild($dom->createElement('BtchBookg', 'true'));
        $paymentInfo->appendChild($dom->createElement('NbOfTxs', count($this->getPayments())));
        $paymentInfo->appendChild($dom->createElement('CtrlSum', number_format($this->getUmsatzsumme(), 2, '.', '')));
        $paymentInfo->appendChild($tmp1 = $dom->createElement('PmtTpInf'));
        $tmp1->appendChild($tmp2 = $dom->createElement('SvcLvl'));
        $tmp2->appendChild($dom->createElement('Cd', 'SEPA'));

        if ($mode == 2) {
            // zusätzliche Attribute für Lastschriften
            $tmp1->appendChild($tmp2 = $dom->createElement('LclInstrm'));
            $tmp2->appendChild($dom->createElement('Cd', 'CORE'));

            if ($this->getReturningDirectDebiting()) {
                $tmp1->appendChild($dom->createElement('SeqTp', 'FRST'));
            } else {
                $tmp1->appendChild($dom->createElement('SeqTp', 'RCUR'));
            }
        }

        // Ausführungsdatum berechnen
        $fixedDate = $this->getExecutionDate();

        if (!empty($fixedDate)) {
            $ausfuehrungsdatum = $fixedDate;
        } else {
            $ausfuehrungszeit = $creationTime;
            $executionOffset = $this->getExecutionOffset();

            if ($executionOffset > 0) {
                $ausfuehrungszeit = $ausfuehrungszeit + (24 * 3600 * $executionOffset);
            }
            $ausfuehrungsdatum = date('Y-m-d', $ausfuehrungszeit);
        }

        if ($mode == 2) {
            $paymentInfo->appendChild($dom->createElement('ReqdColltnDt', $ausfuehrungsdatum));
        } else {
            $paymentInfo->appendChild($dom->createElement('ReqdExctnDt', $ausfuehrungsdatum));
        }

        // eigene Account-Daten Daten
        if ($mode == 2) {
            $paymentInfo->appendChild($tmp1 = $dom->createElement('Cdtr'));
        } else {
            $paymentInfo->appendChild($tmp1 = $dom->createElement('Dbtr'));
        }
        $tmp1->appendChild($dom->createElement('Nm', $this->getAccountName()));

        if ($mode == 2) {
            $paymentInfo->appendChild($tmp1 = $dom->createElement('CdtrAcct'));
        } else {
            $paymentInfo->appendChild($tmp1 = $dom->createElement('DbtrAcct'));
        }

        $tmp1->appendChild($tmp2 = $dom->createElement('Id'));
        $tmp2->appendChild($dom->createElement('IBAN', $this->getAccountIban()));

        if ($mode == 2) {
            $paymentInfo->appendChild($tmp1 = $dom->createElement('CdtrAgt'));
        } else {
            $paymentInfo->appendChild($tmp1 = $dom->createElement('DbtrAgt'));
        }

        $tmp1->appendChild($tmp2 = $dom->createElement('FinInstnId'));
        $tmp2->appendChild($dom->createElement('BIC', $this->getAccountBic()));
        $paymentInfo->appendChild($dom->createElement('ChrgBr', 'SLEV'));

        if ($mode == 2) {
            $tmp1 = $dom->createElement('CdtrSchmeId');
            $tmp2 = $dom->createElement('Id');
            $tmp3 = $dom->createElement('PrvtId');
            $tmp4 = $dom->createElement('Othr');
            $tmp5 = $dom->createElement('SchmeNm');

            $paymentInfo->appendChild($tmp1);
            $tmp1->appendChild($tmp2);
            $tmp2->appendChild($tmp3);
            $tmp3->appendChild($tmp4);
            $tmp4->appendChild($dom->createElement('Id', $this->getCreditorId()));
            $tmp4->appendChild($tmp5);
            $tmp5->appendChild($dom->createElement('Prtry', 'SEPA'));

        }
        
        /**
         * fix your bullshit code, please
         * *facepalm*
         */

        // Buchungssätze hinzufügen
        /* @var $paymentObject Sepabuchung */
        foreach ($this->getPayments() as $paymentObject) {
            if ($mode == 2) {
                $paymentDOM = $dom->createElement('DrctDbtTxInf');
            } else {
                $paymentDOM = $dom->createElement('CdtTrfTxInf');
            }

            $paymentInfo->appendChild($paymentDOM);

            $end2end = $paymentObject->getEnd2End();

            // End2End setzen
            if (!empty($end2end)) {
                $tmp1 = $dom->createElement('PmtId');
                $tmp1->appendChild($dom->createElement('EndToEndId', $end2end));
                $paymentDOM->appendChild($tmp1);
            }

            // Betrag
            $amount = number_format($paymentObject->getAmount(), 2, '.', '');
            $currency = $paymentObject->getCurrency();
            
            if(empty($currency)){
                $currency = $this->getCurrency();
            }
            

            $tmp2 = $dom->createElement('InstdAmt', $amount);
            $tmp2->setAttribute('Ccy', $currency);

            if ($mode == 2) {
                $paymentDOM->appendChild($tmp2);
            } else {
                $tmp1 = $dom->createElement('Amt');
                $tmp1->appendChild($tmp2);
                $paymentDOM->appendChild($tmp1);
            }

            if ($mode == 2) {
                // Lastschrift -> Mandatsinformationen
                $tmp2 = $dom->createElement('MndtRltdInf');

                $tmp2->appendChild($dom->createElement('MndtId', $paymentObject->getMandateId()));
                $tmp2->appendChild($dom->createElement('DtOfSgntr', $paymentObject->getMandateDate()));

                if ($paymentObject->getMandateChange()) {
                    $tmp2->appendChild($dom->createElement('AmdmntInd', 'true'));
                } else {
                    $tmp2->appendChild($dom->createElement('AmdmntInd', 'false'));
                }

                $tmp1 = $dom->createElement('DrctDbtTx');
                $tmp1->appendChild($tmp2);
                $paymentDOM->appendChild($tmp1);
            }

            // Institut
            if ($mode == 2) {
                $tmp1 = $dom->createElement('DbtrAgt');
            } else {
                $tmp1 = $dom->createElement('CdtrAgt');
            }

            $paymentDOM->appendChild($tmp1);
            $tmp2 = $dom->createElement('FinInstnId');


            $tmp1->appendChild($tmp2);
            $tmp2->appendChild($dom->createElement('BIC', $paymentObject->getBic()));

            // Inhaber
            if ($mode == 2) {
                $tmp1 = $dom->createElement('Dbtr');
            } else {
                $tmp1 = $dom->createElement('Cdtr');
            }

            $paymentDOM->appendChild($tmp1);
            $tmp1->appendChild($dom->createElement('Nm', $paymentObject->getName()));

            // IBAN
            if ($mode == 2) {
                $tmp1 = $dom->createElement('DbtrAcct');
            } else {
                $tmp1 = $dom->createElement('CdtrAcct');
            }

            $paymentDOM->appendChild($tmp1);
            $tmp2 = $dom->createElement('Id');

            $tmp1->appendChild($tmp2);
            $tmp2->appendChild($dom->createElement('IBAN', $paymentObject->getIban()));

            if ($mode == 2) {
                $tmp1 = $dom->createElement('UltmtDbtr');
                $tmp1->appendChild($dom->createElement('Nm', $paymentObject->getName()));
                $paymentDOM->appendChild($tmp1);
            }

            // Verwendungszweck
            $transactionInfo = $paymentObject->getTransactionInformation();

            if (!empty($$transactionInfo)) {
                $tmp1 = $dom->createElement('RmtInf');
                $tmp1->appendChild($dom->createElement('Ustrd', $transactionInfo));
                $paymentDOM->appendChild($tmp1);
            }
        }

        // XML exportieren
        return $dom->saveXML();
    }

    /**
     * Get total turnover sum
     * @deprecated since version 0.2
     * @deprecated use getTotalTurnover instead
     * @return double
     */
    public function getUmsatzsumme() {
        return $this->getTotalTurnover();
    }
    
    /**
     * Get total turnover sum
     * @return double
     */
    public function getTotalTurnover(){
        $amount = 0;
        
        foreach($this->getPayments() as $payment){
            $amount += $payment->getAmount();
        }
        return $amount;
    }

    public function validateBasislastschriftXml($xmlfile) {
        return $this->validateXML($xmlfile, 'pain.008.002.02.xsd');
    }

    public function validateUeberweisungXml($xmlfile) {
        return $this->validateXML($xmlfile, 'pain.001.002.03.xsd');
    }

    protected function validateXML($xmlfile, $xsdfile) {
        $blStatus = false;
        libxml_use_internal_errors(true);

        
        
        /**
         * fuck you author, 
         * fix your fucking ugly code!
         */
        $feed = new DOMDocument();

        $result = $feed->load($xmlfile);
        if ($result === false) {
            $this->xmlerrors[] = "Document is not well formed\n";
        }
        if (@($feed->schemaValidate(dirname(__FILE__) . '/' . $xsdfile))) {
            $blStatus = true;
        } else {
            $this->xmlerrors[] = "! Document is not valid:\n";
            $errors = libxml_get_errors();

            foreach ($errors as $error) {
                $this->xmlerrors[] = "---\n" . sprintf("file: %s, line: %s, column: %s, level: %s, code: %s\nError: %s",
                    basename($error->file),
                    $error->line,
                    $error->column,
                    $error->level,
                    $error->code,
                    $error->message
                );
            }
        }
        
        return $blStatus;
    }

    public function printXmlErrors() {
        if (!is_array($this->xmlerrors)) return;
        foreach ($this->xmlerrors as $error) {
            echo $error;
        }
    }
}