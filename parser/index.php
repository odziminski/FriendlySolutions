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

    public function getData($id): ?string
    {
        $dom = $this->loadHtmlDom('wo_for_parse.html');
        $element = $dom->getElementById($id);
        if ($element) {
            return $element?->nodeValue;
        } else return false;
    }

    public function main()
    {
        $woNumber = $this->getData('wo_number');
        $poNumber = $this->getData('po_number');
        $scheduledDate = $this->prepareDatetime($this->getData('scheduled_date'));
        var_dump($scheduledDate);
    }

    public function prepareDatetime($scheduledDate): string
    {
        $scheduledDate = preg_replace('#\s+#', ' ', $scheduledDate);
        $carbon = new Carbon($scheduledDate, 'America/New_York');
        return $carbon->format('Y-m-d H:i');
    }
}

$parser = new Parser();
$parser -> main();