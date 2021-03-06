<?php

namespace PksGdanskOliwa\OopXml\Interfaces;

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
     * Get value of XML element
     */
    public function getValue();
}