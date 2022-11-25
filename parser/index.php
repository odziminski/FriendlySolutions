<?php

use Carbon\Carbon;

require 'vendor/autoload.php';

class Parser
{

    public function loadHtmlDom($path): DOMDocument
    {
        $dom = new DOMDocument();
        $html = file_get_contents($path);
        $dom->validateOnParse = true;
        $dom->loadHTML($html, LIBXML_NOERROR);

        $dom->preserveWhiteSpace = false;
        return $dom;
    }

    public function getData($id): string
    {
        $dom = $this->loadHtmlDom('wo_for_parse.html');
        $element = $dom->getElementById($id);
        if ($element) {
            return $element->nodeValue;
        } else return false;
    }

    public function writeToCSV(): void
    {
        $woNumber = $this->getData('wo_number');
        $poNumber = $this->getData('po_number');
        $scheduledDate = $this->prepareDatetime($this->getData('scheduled_date'));
        $customer = $this->getData('customer');
        $trade = $this->getData('trade');
        $nte = preg_replace("/[^\d,.]/", "", $this->getData('nte'));
        $storeID = $this->getData('location_name');
        $address = $this->getAddressFields($this->getData('location_address'));
        $locationPhone = floatval(preg_replace('/\D+/', '', $this->getData('location_phone')));

        $handle = fopen("data.csv", "a");
        $result = fwrite($handle, implode(',', [$woNumber, $poNumber, $scheduledDate, $customer, $trade, $nte, $storeID,
            $address['address'], $address['city'], $address['state'], $address['zip'], $locationPhone]));
        fclose($handle);

        if ($result) {
            echo "Successfully added a CSV line.";
        }

    }




    public function prepareDatetime($scheduledDate): string
    {
        $scheduledDate = preg_replace('#\s+#', ' ', $scheduledDate);
        $carbon = new Carbon($scheduledDate, 'America/New_York');
        return $carbon->format('Y-m-d H:i');
    }

    public function getAddressFields($address)
    {
        if (isset($address) && !empty($address)) {
            $address = preg_replace('#[\s]+#', ' ', trim($address)); //strip more than 1 whitespace
            $numbers = preg_split('/\D/', $address, -1, PREG_SPLIT_NO_EMPTY);
            $letters = preg_split('/[^a-zA-Z]/', $address, -1, PREG_SPLIT_NO_EMPTY);

            $address = $letters[0] . " " . $letters[1] . " " . $numbers[0];
            $city = $letters[2];
            $state = $letters[3];
            $zip = $numbers[1];
            return ['address' => $address, 'city' => $city, 'state' => $state, 'zip' => $zip];
        } else return false;
    }


}

$parser = new Parser();
$parser->writeToCSV();
