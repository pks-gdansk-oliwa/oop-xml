<?php

namespace PksGdanskOliwa\OopXml\Interfaces;

/**
 * Interface ElementInterface
 */
interface ElementInterface
{
    /**
     * @param      $dom
     * @param null $parentNode
     * @return mixed
     */
    public function render($dom, $parentNode = null);

    /**
     * @param      $dom
     * @param      $parentNode
     * @param null $elementNode
     * @return mixed
     */
    public function deserialize($dom, $parentNode, $elementNode = null);
}