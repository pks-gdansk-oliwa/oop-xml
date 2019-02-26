<?php

namespace PksGdanskOliwa\OopXml\Interfaces;

/**
 * Interface NodeInerface
 */
interface NodeInterface extends BuildableInterface
{
    /**
     * Add child element to node
     * @param ItemInterface|NodeInterface $element
     * @return self;
     */
    public function addChild($element);

    /**
     * Returns nth element
     * @param int $index
     * @return ItemInterface|NodeInterface
     */
    public function element($index);

    /**
     * Returns elements
     * @return array[ElementInterface|NodeInterface]
     */
    public function elements();

    /**
     * Set attribute to XML element
     * @param string $name
     * @param string $value
     */
    public function setAttribute($name, $value);
}