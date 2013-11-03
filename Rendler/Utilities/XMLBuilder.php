<?php

namespace Rendler\Utilities;

/**
 * DOMDocument Wrapper. Useful in building large XML files.
 *
 * Class XMLBuilder
 * @package Rendler\Utilities
 */
class XMLBuilder
{

    /**
     * @var \DOMDocument|null
     */
    private $xmlDom = null;

    /**
     * @var \DOMDocument|null
     */
    private $currentNode = null;

    /**
     * @var array
     */
    private $prevNode = array();

    /**
     * init the class and the DOMDocument object
     *
     * @param string $version  the version of the XML namespace
     * @param string $encoding the XML encoding
     */
    public function __construct($version = '1.0', $encoding = 'UTF-8')
    {
        $this->xmlDom = new \DOMDocument($version, $encoding);
        $this->currentNode = $this->xmlDom;
    }

    /**
     * add a new XML node to the document. this is used as a parent for
     * other elements to be added
     *
     * @param string $name the name of the node
     */
    public function addNode($name)
    {
        $tmpNode = $this->xmlDom->createElement($name);

        $this->currentNode->appendChild($tmpNode);

        $this->prevNode[] = $this->currentNode;

        $this->currentNode = $tmpNode;
    }

    /**
     * add a new XML element and set its value
     *
     * @param string $name  the tag name of the element
     * @param string $value the element value
     */
    public function element($name, $value)
    {
        $this->currentNode->appendChild(
            $this->xmlDom->createElement($name, $value)
        );
    }

    /**
     * end the current element. this will go back N number of levels
     * in the XML document
     *
     * @param int $levels the number the of level to go up
     */
    public function end($levels = 1)
    {
        for ($i = 1; $i <= $levels; $i++) {
            $this->currentNode = array_pop($this->prevNode);
        }
    }

    /**
     * get the element's value by looking up the tag name
     *
     * @param string $name the tag to look for
     *
     * @return \DOMNode the element found
     */
    public function getNode($name)
    {
        $elementXML = $this->xmlDom->getElementsByTagName($name);

        return $elementXML->item(0);
    }

    /**
     * get the XML document as a string.
     *
     * @param DOMNode $data if passed the method return the strin representation
     *                      of the node
     *
     * @return string the XML document or element as string
     */
    public function saveXML($data = null)
    {
        if ($data !== null) {
            return $this->xmlDom->saveXML($data);
        }

        return $this->xmlDom->saveXML();
    }

    /**
     * retrieve an element searching by tag and return it as a string
     *
     * @param string $element the tag to search for
     *
     * @return string the XML's node string representation
     */
    public function xmlText($element)
    {
        $elementXML = $this->xmlDom->getElementsByTagName($element);

        return $this->saveXML($elementXML->item(0));
    }

}
