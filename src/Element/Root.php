<?php
/**
 * Class Root
 * Created in project OOP-XML(2019/02/27)
 */

namespace PksGdanskOliwa\OopXml\Element;

use PksGdanskOliwa\OopXml\Interfaces\BuildableInterface;

/**
 * Class Root
 */
class Root implements BuildableInterface
{
    /**
     * @inheritDoc
     */
    public function build($document, $parentNode = null)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function parse($dom, $elementNode = null)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function isActive()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function activeNode()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function setAttribute($name, $value)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name)
    {
        return null;
    }

}