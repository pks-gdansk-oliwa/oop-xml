<?php

namespace PksGdanskOliwa\OopXml\Element;

use PksGdanskOliwa\OopXml\Document;
use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;
use PksGdanskOliwa\OopXml\Interfaces\ItemInterface;
use PksGdanskOliwa\OopXml\Store\MultipleElementsStore;

/**
 * Class BaseElement
 */
abstract class BaseElement
{
    public $_name;
    protected $_schema;
    protected $_attributes = [];
    /** @var bool Is node active (ex. to print) */
    protected $_isActive = false;
    protected $_parent;

    /**
     * Element constructor.
     * @param BuildableInterface $parent
     * @param string|null        $name
     * @param string|null        $schema
     */
    public function __construct($parent, $name = null, $schema = null)
    {
        $this->_parent = $parent;
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
     * Get Active state of node
     * @return bool
     */
    public function isActive()
    {
        return $this->_isActive;
    }

    /**
     * Activate node, and all parents
     */
    public function activeNode()
    {
        $this->_isActive = true;
        $this->_parent->activeNode();
    }

    /**
     * Deactivate node
     */
    public function deactivateNode()
    {
        $this->_isActive = false;
    }

    /**
     * Return declared xml nodes
     * @return array
     */
    protected function getElementVariables()
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
     * @inheritdoc
     */
    public function setAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
        $this->activeNode();
    }

    /**
     * @inheritdoc
     */
    public function getAttribute($name)
    {
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }


    /**
     * Build's a xml document
     * @param Document         $document
     * @param \DOMElement|null $parentNode
     * @return \DOMElement|\DOMDocument
     */
    public function build($document, $parentNode = null)
    {
        $dom = $document->getDom();

        if ($this->isActive()) {
            $elementNodeValue = isset($this->_value) ? $this->_value : null;

            /** @var \DOMElement $elementNode */
            $elementNode = $dom->createElement($this->getNamespacedName($document), $elementNodeValue);

            if ($this->_attributes && count($this->_attributes)) {
                foreach ($this->_attributes as $an => $av) {
                    if ($av) {
                        // prevent rendering empty attributes
                        $elementNode->setAttribute($an, $av);
                    }
                }
            }

            if ($parentNode) {
                $parentNode->appendChild($elementNode);
            } else {
                $dom->appendChild($elementNode);
            }
            return $elementNode;
        }
        return $dom;
    }

    /**
     * Parses Xml document into OOP-XML classes
     * @param \DOMDocument     $dom
     * @param \DOMElement|null $elementNode
     */
    public function parse($dom, $elementNode = null)
    {
        if ($elementNode) {
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
                        $nodes = $this->getDomElementsChildByTagName($elementNode, $oopXmlItem->_name);
                        $oopXmlItem->parse($dom, array_key_exists(0, $nodes) ? $nodes[0] : null);
                    }
                    if ($oopXmlItem instanceof MultipleElementsStore) {
                        foreach ($this->getDomElementsChildByTagName($elementNode, $oopXmlItem->getTagName()) as $nodes) {
                            $item = $oopXmlItem->factory();
                            $item->parse($dom, $nodes);
                            $oopXmlItem->add($item);
                        }
                    }
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