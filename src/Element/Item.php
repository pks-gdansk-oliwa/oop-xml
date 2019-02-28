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

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @inheritDoc
     */
    public function build($document, $parentNode = null)
    {
        $elementNode = parent::build($document, $parentNode);
        if ($elementNode) {
            $elementNode->nodeValue = $this->_value;
        }
        return $elementNode;
    }

    /**
     * @inheritDoc
     * @param \DOMElement|null $elementNode
     */
    public function parse($dom, $elementNode = null)
    {
        parent::parse($dom, $elementNode);
        if ($elementNode) {
            $this->setValue($elementNode->nodeValue);
        }
    }


}