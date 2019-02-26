<?php

namespace PksGdanskOliwa\OopXml\Interfaces;

use PksGdanskOliwa\OopXml\Document;

/**
 * Interface ElementInterface
 */
interface ItemInterface extends BuildableInterface
{
    /**
     * Set value to XML element
     * @param string $_value
     */
    public function setValue($_value);
    
    /**
     * Set attribute to XML element
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value);
}