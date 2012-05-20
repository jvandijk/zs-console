<?php

namespace ZsConsole\Model\Event\ServerParam;

use \DOMDocument, \DOMXPath, \DOMNode;

class Entity
{
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

    protected $name = null;

    protected $value = null;

    public function __construct(\DOMNode $param = null)
    {
        if (null !== $param) {
            $this->object = $param;
            $this->setXPath($param->ownerDocument);
        }
    }

    protected function setXPath(DOMDocument $doc)
    {
        $this->xpath = new DOMXPath($doc);
        $rootNamespace = $doc->lookupNamespaceUri($doc->namespaceURI);
        $this->xpath->registerNamespace('zs', $rootNamespace);
    }

    public function getName()
    {
        if (null === $this->name) {
            $this->name = $this->xpath->evaluate('string(./zs:name[1])', $this->object);
        }
        return $this->name;
    }

    public function getValue()
    {
        if (null === $this->value) {
            $this->value = $this->xpath->evaluate('string(./zs:value[1])', $this->object);
        }
        return $this->value;
    }
}