<?php
/**
 * Created by PhpStorm.
 * User: Szymon Beringer
 * Date: 26.02.2019
 * Time: 09:21
 */

namespace PksGdanskOliwa\OopXml\Element;


use PksGdanskOliwa\OopXml\Document;
use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;
use PksGdanskOliwa\OopXml\Interfaces\ItemInterface;
use PksGdanskOliwa\OopXml\Store\MultipleElementsStore;

abstract class BaseElement
{
    public $_name;
    protected $_schema;
    protected $_attributes = [];

    /**
     * Element constructor.
     * @param string|null $name
     * @param string|null $schema
     */
    public function __construct($name = null, $schema = null)
    {
        $this->setName($name);
        $this->setSchema($schema);
    }

    /**
     * Sets name of element
     * @param string|null $name
     */
    public function setName($name = null)
    {
        if ($name) {
            $this->_name = $name;
        }
    }

    /**
     * Set's namespace
     * @param string $namespace
     */
    public function setSchema($namespace = null)
    {
        if ($namespace) {
            $this->_schema = $namespace;
        }
    }

    /**
     * Return declared xml nodes
     * @return array
     */
    protected function getElementVariables()
    {
        $variables = get_object_vars($this);
        foreach ($variables as $variable) {
            if (strpos('_', $variable) === 0) {
                unset($variables[$variable]);
            }
        }
        return $variables;

    }

    /**
     * Returns name with XML namespace
     * @param  Document $document
     * @return string
     */
    protected function getNamespacedName($document)
    {
        if ($this->_schema) {
            return $document->getNamespaceLocalPartBySchema($this->_schema) . ':' . $this->_name;
        }
        return $this->_name;
    }

    /**
     * Set attribute to XML element
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * Build's a xml document
     * @param Document         $document
     * @param \DOMElement|null $parentNode
     * @return \DOMElement
     */
    public function build($document, $parentNode = null)
    {
        $dom = $document->getDom();

        $elementNodeValue = isset($this->_value) ? $this->_value : null;

        /** @var \DOMElement $elementNode */
        $elementNode = $dom->createElement($this->getNamespacedName($document), $elementNodeValue);

        if ($this->_attributes && count($this->_attributes)) {
            foreach ($this->_attributes as $an => $av) {
                $elementNode->setAttribute($an, $av);
            }
        }

        if ($parentNode) {
            $parentNode->appendChild($elementNode);
        } else {
            $dom->appendChild($elementNode);
        }

        return $elementNode;
    }

    /**
     * Parses Xml document into OOP-XML classes
     * @param \DOMDocument     $dom
     * @param \DOMElement      $parentNode
     * @param \DOMElement|null $elementNode
     */
    public function parse($dom, $parentNode, $elementNode = null)
    {
        if ($this->_attributes && count($this->_attributes)) {
            foreach ($this->_attributes as $attributeName => $attributeValue) {
                $attrValue = $elementNode->getAttribute($attributeName);
                $this->setAttribute($attributeName, $attrValue);
            }
        }

        if ($this instanceof ItemInterface && $elementNode->nodeValue) {
            $this->setValue($elementNode->nodeValue);
        }

        foreach ($this->getElementVariables() as $name => $oopXmlItem) {
            //variable can be declared only once, we can fetch only first element from xml
            if (is_object($oopXmlItem)) {
                if ($oopXmlItem instanceof BuildableInterface) {
                    $oopXmlItem->parse($dom, $elementNode, $elementNode->getElementsByTagName($oopXmlItem->_name)->item(0));
                }
                if ($oopXmlItem instanceof MultipleElementsStore) {
                    foreach ($elementNode->getElementsByTagName($oopXmlItem->getTagName()) as $foundElementNode) {
                        $item = $oopXmlItem->factory();
                        $item->parse($dom, $elementNode, $foundElementNode);
                        $oopXmlItem->add($item);
                    }
                }
            }
        }
    }
}