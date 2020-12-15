<?php
namespace App;

use DOMDocument;
use DOMNode;
use DOMXPath;

class CurlGrabber implements IGrabber
{
    const URI = 'https://www.czc.cz/';

    const SUFFIX = '/hledat';

    const PRODUCT_DETAIL_LINK = '//*[@id="tiles"]/div/div/div[2]/div[1]/h5/a';

    const PRODUCT_PRICE_ALONE = '//span[@class = "price alone"]';
    const PRODUCT_PRICE_DISCOUNT =  '//span[@class = "price action"]';


    public function getPrice($productId) : ?float
    {

        $handle = NULL;

        $fullUrl = self::URI . $productId . self::SUFFIX;

        $dom =  $this->getPageSource($fullUrl);
        if(FALSE !== $dom)
        {
            return $this->getFromSearch($dom);
        }
        return NULL;
    }

    /**
     * Parse search page, if is founded parse product detail page
     * @param DOMDocument $dom
     * @return float|null
     */
    private function getFromSearch(DOMDocument $dom): ?float
    {
        $xpath = new DOMXPath($dom);
        /** @var \DOMNodeList|false $foundLink */
        $foundElement = $xpath->query(self::PRODUCT_DETAIL_LINK);

        $indexOfElement = 0;
        if($foundElement->length !== 1)
        {
            $indexOfElement = 1;
        }
        /** @var DOMNode $r */
        $node = $foundElement->item($indexOfElement);
        if(NULL !== $node)
        {
            $attribute = $node->attributes->getNamedItem('href');
            if($attribute)
            {
                $domDetail = $this->getPageSource(self::URI . $attribute->value);
                return $this->parseDetailPage($domDetail);
            }
        }
        return NULL;
    }

    private function parseDetailPage(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        /** @var \DOMNodeList|false $foundLink */
        $foundElements = $xpath->query(self::PRODUCT_PRICE_ALONE);
        if($foundElements->length === 0)
        {
            $foundElements = $xpath->query(self::PRODUCT_PRICE_DISCOUNT);
        }

        return $this->parsePriceBlock($foundElements->item(0));
    }

    /**
     * parse Total price block
     * get price from field with the price with vat
     *
     * @param DOMNode $node
     * @return float
     */
    private function parsePriceBlock(DOMNode $node): float
    {
        $amountText = trim($node->childNodes->item(3)->nodeValue);
        $amountText = str_replace(["\\n",' ',"\u{00a0}","&nbsp;","&NBSP;","NBSP;","Kč"], "", $amountText);
        return floatval($amountText);
    }

    /**
     * get html page source
     * @param string $fullUrl
     * @return DOMDocument|null
     */
    private function getPageSource(string $fullUrl): ?DOMDocument
    {
        $handle = curl_init($fullUrl);

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($handle);

        if(!empty($output)) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $output = str_replace("&nbsp;","", $output);
            $dom->loadHTML($output);
            libxml_use_internal_errors(false);
            curl_close($handle);
            return $dom;
        }
        curl_close($handle);
        return NULL;
    }
}