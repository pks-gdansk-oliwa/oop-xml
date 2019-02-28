<?php
/**
 * Class ChildVariables
 * Created in project OOP-XML (2019/02/28)
 */

namespace PksGdanskOliwa\OopXml\Element\Traits;

use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;
use PksGdanskOliwa\OopXml\Store\MultipleElementsStore;

/**
 * Trait ChildVariables
 */
trait ParserHelper
{
    /**
     * Return declared xml child nodes
     * @return array
     */
    protected function getChildVariables()
    {
        $variables = get_object_vars($this);
        foreach (array_keys($variables) as $variable) {
            if (strpos($variable, '_') === 0) {
                unset($variables[$variable]);
            }
        }
        return $variables;
    }

    /**
     * Parse child nodes
     * @param \DOMDocument     $dom
     * @param \DOMElement|null $elementNode
     */
    protected function parseChildrenNodes($dom, $elementNode)
    {
        foreach ($this->getChildVariables() as $name => $oopXmlItem) {
            if ($oopXmlItem instanceof BuildableInterface) {
                $nodes = $this->getDomElementsChildByTagName($elementNode, $oopXmlItem->_name);
                //variable can be declared only once, we can fetch only first element from xml
                $oopXmlItem->parse($dom, array_key_exists(0, $nodes) ? $nodes[0] : null);
            } elseif ($oopXmlItem instanceof MultipleElementsStore) {
                foreach ($this->getDomElementsChildByTagName($elementNode, $oopXmlItem->getTagName()) as $nodes) {
                    $item = $oopXmlItem->factory();
                    $item->parse($dom, $nodes);
                    $oopXmlItem->add($item);
                }
            }
        }
    }

    /**
     * Get child elements by tag name
     * @param \DOMElement $elementNode
     * @param string      $tagName
     * @return array
     */
    private function getDomElementsChildByTagName($elementNode, $tagName)
    {
        $nodes = [];
        foreach ($elementNode->childNodes as $node) {
            if ($node->localName == $tagName) {
                $nodes[] = $node;
            }
        }
        return $nodes;
    }
}