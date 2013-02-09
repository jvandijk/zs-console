<?php

namespace ZsConsole\Model\System;

use \DOMDocument, \DOMXPath, \DOMNode;

class Entity
{
    protected $status = null;
    protected $edition = null;
    protected $version = null;
    protected $apiVersions = null;
    protected $phpVersion = null;
    protected $operatingSystem = null;
    protected $deploymentVersion = null;
    protected $serverLicense = null;
    protected $managerLicense = null;

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

        $this->object = $doc->getElementsByTagName('systemInfo')->item(0);
        $this->setXPath($doc);
    }

    public function getStatus()
    {
        if (null === $this->status) {
            $this->status = $this->xpath->evaluate('string(./zs:status[1])', $this->object);
        }
        return $this->status;
    }

    public function getEdition()
    {
        if (null === $this->edition) {
            $this->edition = $this->xpath->evaluate('string(./zs:edition[1])', $this->object);
        }
        return $this->edition;
    }

    public function getVersion()
    {
        if (null === $this->version) {
            $this->version = $this->xpath->evaluate('string(./zs:zendServerVersion[1])', $this->object);
        }
        return $this->version;
    }

    public function getApiVersions()
    {
        if (null === $this->apiVersions) {
            $this->apiVersions = explode(', ', $this->xpath->evaluate('string(./zs:supportedApiVersions[1])', $this->object));
        }
        return $this->apiVersions;
    }

    public function getPhpVersion()
    {
        if (null === $this->phpVersion) {
            $this->phpVersion = $this->xpath->evaluate('string(./zs:phpVersion[1])', $this->object);
        }
        return $this->phpVersion;
    }

    public function getOperatingSystem()
    {
        if (null === $this->operatingSystem) {
            $this->operatingSystem = $this->xpath->evaluate('string(./zs:operatingSystem[1])', $this->object);
        }
        return $this->operatingSystem;
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