<?php

namespace PksGdanskOliwa\OopXml;

use DOMDocument;
use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;
use PksGdanskOliwa\OopXml\Store\MultipleElementsStore;

/**
 * Class BaseDocument
 */
class Document
{
    public $_rootElement;
    protected $_rootNode;

    /**
     * @var \DOMDocument $_dom
     */
    protected $_dom;

    private $_version;
    private $_encoding;
    private $_namespaces = [];

    /**
     * Document constructor.
     * @param string $version
     * @param string $encoding
     */
    public function __construct($version = '1.0', $encoding = 'UTF-8')
    {
        $this->_version = $version;
        $this->_encoding = $encoding;
    }

    /**
     * Create's root node of xml document
     */
    private function createRootNode()
    {
        $this->_rootNode = $this->_dom->createElement($this->_rootElement, '');
        $this->_dom->appendChild($this->_rootNode);
    }

    /**
     * Build XML from Document
     */
    private function build()
    {
        foreach ($this->getDocumentVariables() as $name => $element) {
            if ($element instanceof BuildableInterface) {
                $element->build($this, $this->_rootNode);
            } elseif ($element instanceof MultipleElementsStore) {
                foreach ($element->get() as $storeElement) {
                    $storeElement->build($this, $this->_rootNode);
                }
            }
        }
    }

    /**
     * Mapowanie XML'a na klasy PHP'owe
     */
    private function parse()
    {
        foreach ($this->getDocumentVariables() as $name => $element) {
            if ($element instanceof BuildableInterface) {
                $element->parse($this->_dom, $this->_rootNode->getElementsByTagName($element->_name)->item(0));
            } elseif ($element instanceof MultipleElementsStore) {
                foreach ($this->_rootNode->getElementsByTagName($element->getTagName()) as $foundElementNode) {
                    $item = $element->factory();
                    $item->parse($this->_dom, $foundElementNode);
                    $element->add($item);
                }
            }
        }
    }

    /**
     * Zwraca elementy dokumentu XML z wykluczeniem zarezerwowanych nazw
     * @return array
     */
    private function getDocumentVariables()
    {
        $variables = get_object_vars($this);
        foreach (['_dom', '_rootElement', '_rootNode', '_version', '_encoding'] as $preservedNodes) {
            unset($variables[$preservedNodes]);
        }
        return $variables;
    }

    /**
     * Register new namespace
     * @param string $namespaceURI
     * @param string $prefix
     * @param string $localPart
     * @param string $schema
     */
    public function registerNamespace($namespaceURI, $prefix, $localPart, $schema)
    {
        $this->_namespaces[$this->getQualifiedName($prefix, $localPart)] = ['namespaceURI' => $namespaceURI, 'prefix' => $prefix, 'localPart' => $localPart, 'schema' => $schema];
    }

    /**
     * Get qualified name of an element, attribute, or identifier in an XML document
     * @param string $prefix
     * @param string $localPart
     * @return string
     */
    public function getQualifiedName($prefix, $localPart)
    {
        return $prefix . ':' . $localPart;
    }

    /**
     * Returns XML namespace localPart from registered namespaces
     * @param $value
     * @return mixed
     */
    public function getNamespaceLocalPartBySchema($value)
    {
        foreach ($this->_namespaces as $ns) {
            if ($ns['schema'] === $value) {
                return $ns['localPart'];
            }
        }
        throw new \RuntimeException('Can\'t find namespace: ' . $value . ' in XML document declaration');
    }

    /**
     * Returns xml string
     * @return string
     */
    public function toXML()
    {
        $this->createNewDocument();
        $this->build();
        return $this->_dom->saveXML();
    }

    /**
     * Imports XML string into OOP
     * @param string $xmlString
     */
    public function importXML($xmlString)
    {
        $this->_dom = new \DOMDocument($this->_version, $this->_encoding);
        $this->_dom->loadXML($xmlString);

        $this->_rootNode = $this->_dom->firstChild;

        $this->parse();
    }

    /**
     * Tworzy nowy dokument
     */
    private function createNewDocument()
    {
        $this->_dom = new DOMDocument($this->_version, $this->_encoding);
        $this->createRootNode();
        $this->createNamespaces();
    }

    /**
     * Dodaje zarejestrowane namespace'y do głównego elementu
     */
    private function createNamespaces()
    {
        foreach ($this->_namespaces as $qualifiedName => $namespace) {
            $this->_rootNode->setAttributeNS($namespace['namespaceURI'], $qualifiedName, $namespace['schema']);
        }
    }

    /**
     * Get dom document
     * @return DOMDocument
     */
    public function getDom()
    {
        return $this->_dom;
    }
}