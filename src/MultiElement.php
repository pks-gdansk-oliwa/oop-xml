<?php

namespace PksGdanskOliwa\OopXml;

use DOMDocument;
use PksGdanskOliwa\OopXml\Interfaces\ElementInterface;

/**
 * Class MultiElement
 */
class MultiElement extends BaseElement implements ElementInterface
{
    protected $elementClass;
    protected $elements = [];

    /**
     * @param ElementInterface $element
     */
    protected function addElement(ElementInterface $element)
    {
        array_push($this->elements, $element);
    }

    /**
     * @param DOMDocument $dom
     * @param null        $parentNode
     */
    public function render($dom, $parentNode = null)
    {
        foreach ($this->elements as $element) {
            $element->render($dom, $parentNode);
        }
    }

    public function deserialize($dom, $parentNode, $elementNode = null)
    {
        $vars = get_class_vars($this->elementClass);

        $elementNodes = $parentNode->getElementsByTagName($vars['name']);

        foreach ($elementNodes as $elementNode) {
            $element = new $this->elementClass();
            $element->deserialize($dom, null, $elementNode);
            $this->addElement($element);
        }
    }

    /**
     * @param $index
     * @return mixed
     */
    public function element($index)
    {
        return $this->elements[$index];
    }

    /**
     * @return array
     */
    public function elements()
    {
        return $this->elements;
    }
}