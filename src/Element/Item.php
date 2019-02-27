<?php

namespace PksGdanskOliwa\OopXml\Element;

use PksGdanskOliwa\OopXml\Interfaces\ItemInterface;

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
        $this->activeNode();
        return $this;
    }
}