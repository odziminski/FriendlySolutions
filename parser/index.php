<?php

class Parser
{

    public function loadHtmlDom($path)
    {
        $dom = new DOMDocument();
        $html = file_get_contents($path);
        $dom->validateOnParse = true;
        $dom->loadHTML($html, LIBXML_NOERROR);

        $dom->preserveWhiteSpace = false;
        return $dom;
    }
}