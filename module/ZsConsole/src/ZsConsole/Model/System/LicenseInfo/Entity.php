<?php

namespace ZsConsole\Model\System\LicenseInfo;

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

    protected $validUntil = null;
    protected $status = null;
    protected $orderNumber = null;
    protected $serverLimit = null;

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

    public function getValidUntil()
    {
        if (null === $this->validUntil) {
            $this->validUntil = $this->xpath->evaluate('string(./zs:validUntil[1])', $this->object);
        }
        return $this->validUntil;
    }

    public function getStatus()
    {
        if (null === $this->status) {
            $this->status = $this->xpath->evaluate('string(./zs:status[1])', $this->object);
        }
        return $this->status;
    }

    public function getOrderNumber()
    {
        if (null === $this->orderNumber) {
            $this->orderNumber = $this->xpath->evaluate('string(./zs:orderNumber[1])', $this->object);
        }
        return $this->orderNumber;
    }

    public function getServerLimit()
    {
        if (null === $this->serverLimit) {
            $this->serverLimit = $this->xpath->evaluate('number(./zs:nodeLimit[1])', $this->object);
        }
        return $this->serverLimit;
    }
}