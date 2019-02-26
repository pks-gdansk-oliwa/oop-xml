<?php

namespace PksGdanskOliwa\OopXml\Element;

use PksGdanskOliwa\OopXml\Document;
use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;
use PksGdanskOliwa\OopXml\Interfaces\ItemInterface;
use PksGdanskOliwa\OopXml\Store\MultipleElementsStore;

/**
 * Class Element
 */
class Item extends BaseElement implements ItemInterface
{

    public $_value = null;

    /**
     * @inheritdoc
     */
    public function setValue($_value)
    {
        $this->_value = $_value;
        return $this;
    }
}