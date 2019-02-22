<?php

namespace PksGdanskOliwa\OopXml;

use PksGdanskOliwa\OopXml\Interfaces\ElementInterface;
use PksGdanskOliwa\OopXml\Interfaces\ValueElementInterface;

/**
 * Class BaseElement
 * @package Framework\Lib\Service\Xml
 */
class BaseElement implements ElementInterface
{
    public $namespace = null;
    public $value = null;
    protected $attributes = [];

    /**
     * @param \DOMDocument $dom
     * @param null         $parentNode
     * @return mixed|void
     */
    public function render($dom, $parentNode = null)
    {
        $elementNodeValue = isset($this->value) ? $this->value : '';

        /** @var \DOMElement $elementNode */
        $elementNode = $dom->createElement($this->getName($this->name, $this->namespace), $elementNodeValue);

        if ($this->attributes && count($this->attributes)) {
            foreach ($this->attributes as $an => $av) {
                $elementNode->setAttribute($an, $av);
            }
        }

        if ($parentNode) {
            $parentNode->appendChild($elementNode);

        } else {
            $dom->appendChild($elementNode);
        }

        foreach ($this->getElementVariables() as $name => $value) {
            if (is_string($value)) {
                /** @var \DOMElement $subNode */
                $subNode = $dom->createElement($this->getName($name, $this->namespace), $value);


                $elementNode->appendChild($subNode);
            }
            if (is_object($value) && $value instanceof ElementInterface) {
                $value->render($dom, $elementNode);
            }
        }
    }

    /**
     * @param      $dom
     * @param null $parentNode
     * @param null $elementNode
     */
    public function deserialize($dom, $parentNode, $elementNode = null)
    {
        if (!isset($elementNode)) {
            $elementNode = $parentNode->getElementsByTagName($this->name)->item(0);
        }

        if ($this->attributes && count($this->attributes)) {
            foreach ($this->attributes as $an => $av) {
                $attrValue = $elementNode->getAttribute($an);
                $this->setAttribute($an, $attrValue);
            }
        }

        if ($this instanceof ValueElementInterface && $elementNode->nodeValue) {
            $this->value = $elementNode->nodeValue;
        }

        foreach ($this->getElementVariables() as $name => $value) {
            if (!is_object($value)) {
                $subNode = $elementNode->getElementsByTagName($name)->item(0);

                if ($subNode instanceof \DOMElement) {
                    $this->{$name} = $subNode->nodeValue;
                }
            }
            if (is_object($value) && $value instanceof ElementInterface) {
                $value->deserialize($dom, $elementNode);
            }
        }
    }

    protected function getElementVariables()
    {
        $elements = get_object_vars($this);
        unset($elements['name']);
        unset($elements['namespace']);
        unset($elements['attributes']);
        unset($elements['value']);
        unset($elements['elementClass']);
        unset($elements['elements']);
        return $elements;
    }

    protected function getName($name, $namespace = null)
    {
        if (isset($namespace)) {
            return $namespace . ':' . $name;
        }
        return $name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }
}