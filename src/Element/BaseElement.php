<?php

namespace PksGdanskOliwa\OopXml\Element;

use PksGdanskOliwa\OopXml\Document;
use PksGdanskOliwa\OopXml\Element\Traits\ParserHelper;
use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;

/**
 * Class BaseElement
 */
abstract class BaseElement
{
    use ParserHelper;

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
     * @return \DOMElement|\DOMDocument|null
     */
    public function build($document, $parentNode = null)
    {
        $dom = $document->getDom();

        if ($this->isActive()) {
            /** @var \DOMElement $elementNode */
            $elementNode = $dom->createElement($this->getNamespacedName($document));

            $this->buildAttributes($elementNode);

            if ($parentNode) {
                $parentNode->appendChild($elementNode);
                return $elementNode;
            }
            $dom->appendChild($elementNode);
            return $dom;
        }
        return null;
    }

    /**
     * @inheritdoc
     * @param \DOMDocument     $dom
     * @param \DOMElement|null $elementNode
     */
    public function parse($dom, $elementNode = null)
    {
        if ($elementNode) {
            $this->parseAttributes($elementNode);
            $this->parseChildrenNodes($dom, $elementNode);
        }
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
     * Build Attributes on Node
     * @param \DOMElement $elementNode
     */
    private function buildAttributes($elementNode)
    {
        if ($this->_attributes && count($this->_attributes)) {
            foreach ($this->_attributes as $attributeName => $attributeValue) {
                // prevent rendering empty attributes
                if ($attributeValue) {
                    $elementNode->setAttribute($attributeName, $attributeValue);
                }
            }
        }
    }

    /**
     * Parses Attributes on Node
     * @param \DOMElement $elementNode
     */
    private function parseAttributes($elementNode)
    {
        if ($this->_attributes && count($this->_attributes)) {
            foreach ($this->_attributes as $attributeName => $attributeValue) {
                $this->setAttribute($attributeName, $elementNode->getAttribute($attributeName));
            }
        }
    }
}