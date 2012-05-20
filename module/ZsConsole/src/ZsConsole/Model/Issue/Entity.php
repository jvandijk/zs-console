<?php

namespace ZsConsole\Model\Issue;

use \DOMDocument, \DOMXPath, \DOMNode;

class Entity
{
    protected $id = null;
    protected $occurences = null;
    protected $date = null;
    protected $rule = null;
    protected $origin = null;

    /**
     *
     * @var \DOMNode
     */
    protected $object;

    /**
     *
     * @var \DOMXPath
     */
    protected $xpath;

    public function __construct(\DOMNode $issue = null)
    {
        if (null !== $issue) {
            $this->object = $issue;
            $this->setXPath($issue->ownerDocument);
        }
    }

    protected function setXPath(DOMDocument $doc)
    {
        $this->xpath = new DOMXPath($doc);
        $rootNamespace = $doc->lookupNamespaceUri($doc->namespaceURI);
        $this->xpath->registerNamespace('zs', $rootNamespace);
    }

    public function setXml($xml)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml);

        $this->object = $doc->getElementsByTagName('issue')->item(0);
        $this->setXPath($doc);
    }

    public function getId()
    {
        if (null === $this->id) {
            $this->id = $this->xpath->evaluate('number(./zs:id[1])', $this->object);
        }
        return $this->id;
    }

    public function getDate()
    {
        if (null === $this->date) {
            $this->date = $this->xpath->evaluate('string(./zs:lastOccurance[1])', $this->object);
        }
        return $this->date;
    }

    public function getNrOfOccurences() {
        if (null === $this->occurences) {
            $this->occurences = $this->xpath->evaluate('number(./zs:count[1])', $this->object);
        }
        return $this->occurences;
    }

    public function getRule()
    {
        if (null === $this->rule) {
            $this->rule = $this->xpath->evaluate('string(./zs:rule[1])', $this->object);
        }
        return $this->rule;
    }

    public function getOrigin()
    {
        if (null == $this->origin) {
            $this->origin = $this->xpath->evaluate('string(./zs:generalDetails/zs:url[1])', $this->object);
        }
        return $this->origin;
    }

    public function getEventGroupId()
    {
        $eventNodeList = $this->object->ownerDocument->getElementsByTagName('eventsGroupId');
        if ($eventNodeList->length > 0) {
            return $eventNodeList->item(0)->nodeValue;
        }
        return null;
    }
}