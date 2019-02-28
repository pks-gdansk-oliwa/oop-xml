<?php

namespace PksGdanskOliwa\OopXml\Element;

use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;
use PksGdanskOliwa\OopXml\Interfaces\NodeInterface;
use PksGdanskOliwa\OopXml\Store\MultipleElementsStore;

/**
 * Class Node
 */
class Node extends BaseElement implements NodeInterface
{
    protected $_elements = [];


    /**
     * @inheritdoc
     */
    public function addChild($element)
    {
        array_push($this->_elements, $element);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function build($document, $parentNode = null)
    {
        $node = parent::build($document, $parentNode);
        if ($node) {
            foreach ($this->getChildVariables() as $element) {
                if ($element instanceof BuildableInterface) {
                    $this->addChild($element);
                } elseif ($element instanceof MultipleElementsStore) {
                    $this->addChildFromStore($element);
                }
            }

            /** @var Node|Item $element */
            foreach ($this->_elements as $element) {
                $element->build($document, $node);
            }
        }
    }

    /**
     * Add's children element from store
     * @param MultipleElementsStore $store
     */
    private function addChildFromStore($store)
    {
        foreach ($store->get() as $storeElement) {
            if ($storeElement instanceof BuildableInterface) {
                $this->addChild($storeElement);
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function element($index)
    {
        return $this->_elements[$index];
    }

    /**
     * @inheritdoc
     */
    public function elements()
    {
        return $this->_elements;
    }

}