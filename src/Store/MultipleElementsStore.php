<?php

namespace PksGdanskOliwa\OopXml\Store;

use PksGdanskOliwa\OopXml\Interfaces\ItemInterface;
use PksGdanskOliwa\OopXml\Interfaces\NodeInterface;

class MultipleElementsStore
{
    public function __construct($xmlClassName, $tagName, $namespace)
    {
        $this->xmlClassName = $xmlClassName;
        $this->tagName = $tagName;
        $this->namespace = $namespace;
    }

    public function add($element)
    {
        $this->elements[] = $element;
    }

    public function get()
    {
        return $this->elements;
    }

    /**
     * @return mixed
     */
    public function getXmlClassName()
    {
        return $this->xmlClassName;
    }

    /**
     * @return mixed
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    public function factory()
    {
        /** @var NodeInterface|ItemInterface $object */
        $class = $this->xmlClassName;
        return new $class($this->tagName, $this->namespace);
    }
}