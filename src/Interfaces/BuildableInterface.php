<?php

namespace PksGdanskOliwa\OopXml\Interfaces;

use PksGdanskOliwa\OopXml\Document;

/**
 * Interface BuildableInterface
 */
interface BuildableInterface
{
    /**
     * Build's a xml document
     * @param Document         $document
     * @param \DOMElement|null $parentNode
     * @return \DOMElement
     */
    public function build($document, $parentNode = null);

    /**
     * Parses xml document to object
     * @param                  $dom
     * @param \DOMElement      $parentNode
     * @param \DOMElement|null $elementNode
     * @return mixed
     */
    public function parse($dom, $parentNode, $elementNode = null);
}