<?php
/**
 * Class Root
 * Created in project promy24.com (2019/02/27)
 */

namespace PksGdanskOliwa\OopXml\Element;

use PksGdanskOliwa\OopXml\Document;
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

}