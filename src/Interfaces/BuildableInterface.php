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
     */
    public function build($document, $parentNode = null);

    /**
     * Parses xml document to object
     * @param                  $dom
     * @param \DOMElement|null $elementNode
     * @return mixed
     */
    public function parse($dom, $elementNode = null);

    /**
     * Get Active state of node
     * @return bool
     */
    public function isActive();

    /**
     * Activate node, and all parents
     */
    public function activeNode();

    /**
     * Set attribute to XML element
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value);

    /**
     * Get attribute from XML element
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name);
}