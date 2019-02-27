<?php

namespace PksGdanskOliwa\OopXml\Store;

use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;
use PksGdanskOliwa\OopXml\Interfaces\ItemInterface;
use PksGdanskOliwa\OopXml\Interfaces\NodeInterface;

/**
 * Class MultipleElementsStore
 */
class MultipleElementsStore
{
    private $elements = [];
    private $parent;
    private $xmlClassName;
    private $tagName;
    private $namespace;

    /**
     * MultipleElementsStore constructor.
     * @param BuildableInterface $parent
     * @param string             $xmlClassName
     * @param string             $tagName
     * @param string|null        $namespace
     */
    public function __construct($parent, $xmlClassName, $tagName, $namespace = null)
    {
        $this->xmlClassName = $xmlClassName;
        $this->tagName = $tagName;
        if ($namespace) {
            $this->namespace = $namespace;
        }
        $this->parent = $parent;
    }

    /**
     * Adds element
     * @param BuildableInterface $element
     */
    public function add($element)
    {
        $this->elements[] = $element;
    }

    /**
     * Get all elements
     * @return BuildableInterface[]
     */
    public function get()
    {
        return $this->elements;
    }

    /**
     * Get OOP-XML class name
     * @return string
     */
    public function getXmlClassName()
    {
        return $this->xmlClassName;
    }

    /**
     * Get xml tag name
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Factory new oop-xml element
     * @return BuildableInterface
     */
    public function factory()
    {
        /** @var NodeInterface|ItemInterface $object */
        $class = $this->xmlClassName;
        return new $class($this->parent, $this->tagName, $this->namespace);
    }
}