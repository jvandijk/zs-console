<?php

namespace ZsConsole\Model\Event;

use \DOMDocument, \DOMXPath,
    ZsConsole\Model\Event\ServerParam\ResultSet as ServerParams,
    ZsConsole\Model\Event\Backtrace\ResultSet as Backtrace;

class Entity
{
    protected $description = null;
    protected $serverParams = null;
    protected $backtrace = null;
    protected $codetrace = null;

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
        //$doc->preserveWhiteSpace = false;

        $this->object = $doc->getElementsByTagName('eventsGroupDetails')->item(0);
        $this->setXPath($doc);
    }

    public function getDescription()
    {
        if (null === $this->description) {
            $this->description = $this->xpath->evaluate('string(./zs:event[1]/zs:description[1])', $this->object);
        }
        return $this->description;
    }

    public function getServerParams()
    {
        if (null === $this->serverParams) {
            $list = $this->xpath->evaluate('./zs:event[1]/zs:superGlobals[1]/zs:server/zs:parameter', $this->object);
            $this->serverParams = new ServerParams($list);
        }
        return $this->serverParams;
    }

    public function getBacktrace()
    {
        if (null === $this->backtrace) {
            $list = $this->xpath->evaluate('./zs:event[1]/zs:backtrace[1]/zs:step', $this->object);
            $this->backtrace = new Backtrace($list);
        }
        return $this->backtrace;
    }

    public function getCodetrace()
    {
        if (null === $this->codetrace) {
            $this->codetrace = $this->xpath->evaluate('./zs:codeTracing[1]', $this->object)->length;
        }
        return $this->codetrace;
    }
}