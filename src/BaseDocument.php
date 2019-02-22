<?php

namespace PksGdanskOliwa\OopXml;

use PksGdanskOliwa\OopXml\Interfaces\ElementInterface;
use DOMDocument;

/**
 * Class BaseDocument
 * @package Framework\Lib\Service\Xml
 */
class BaseDocument
{
    public $rootElement;
    protected $rootNode;
    /**
     * @var DOMDocument $dom
     */
    protected $dom;
    protected $xpath;
    private $_version;
    private $_encoding;
    private $_namespaces = [];

    /**
     * BaseDocument constructor.
     * @param string $version
     * @param string $encoding
     */
    public function __construct($version = '1.0', $encoding = 'UTF-8')
    {
        $this->_version = $version;
        $this->_encoding = $encoding;
    }

    private function createRootNode()
    {
        $this->rootNode = $this->dom->createElement($this->rootElement, '');
        $this->dom->appendChild($this->rootNode);
    }

    /**
     * Renderuje dokument
     */
    private function render()
    {
        foreach ($this->getDocumentVariables() as $name => $element) {
            if ($element instanceof ElementInterface) {
                $element->render($this->dom, $this->rootNode);
            }
        }
    }

    /**
     * Mapowanie XML'a na klasy PHP'owe
     */
    private function deserialize()
    {
        foreach ($this->getDocumentVariables() as $name => $element) {
            if ($element instanceof ElementInterface) {
                $element->deserialize($this->dom, $this->rootNode);
            }
        }
    }

    /**
     * @return array
     */
    private function getDocumentVariables()
    {
        $variables = get_object_vars($this);
        foreach (['dom', 'xpath', 'rootElement', 'rootNode', 'version', 'encoding'] as $preservedNodes) {
            unset($variables[$preservedNodes]);
        }
        return $variables;
    }

    /**
     * @param $namespaceURI
     * @param $qualifiedName
     * @param $value
     */
    public function registerNamespace($namespaceURI, $qualifiedName, $value)
    {
        array_push($this->_namespaces, ['namespaceURI' => $namespaceURI, 'qualifiedName' => $qualifiedName, 'value' => $value]);
    }

    /**
     * @return mixed
     */
    public function toXML()
    {
        $this->createNewDocument();
        $this->render();
        return $this->dom->saveXML();
    }

    /**
     * @param $source
     */
    public function importXML($source)
    {
        $this->dom = new DOMDocument($this->_version, $this->_encoding);
        $this->dom->loadXML($source);

        $this->rootNode = $this->dom->firstChild;

        $this->deserialize();
    }

    /**
     * Tworzy nowy dokument
     */
    private function createNewDocument()
    {
        $this->dom = new DOMDocument($this->_version, $this->_encoding);
        $this->createRootNode();
        $this->createNamespaces();
    }

    /**
     * Dodaje zarejestrowane namespace'y do głównego elementu
     */
    private function createNamespaces()
    {
        foreach ($this->_namespaces as $namespace) {
            $this->rootNode->setAttributeNS($namespace['namespaceURI'], $namespace['qualifiedName'], $namespace['value']);
        }
    }
}