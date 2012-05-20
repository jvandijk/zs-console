<?php

namespace ZsConsole\Model\Event\Backtrace;

use \DOMDocument, \DOMXPath, \DOMNode;

class Entity
{
    /**
     *
     * @var \DOMNode
     */
    protected $step;

    /**
     *
     * @var \DOMXPath
     */
    protected $xpath;

    protected $number = null;

    protected $object = null;

    protected $class = null;

    protected $function = null;

    protected $file = null;

    protected $line = null;

    public function __construct(\DOMNode $step = null)
    {
        if (null !== $step) {
            $this->step = $step;
            $this->setXPath($step->ownerDocument);
        }
    }

    protected function setXPath(DOMDocument $doc)
    {
        $this->xpath = new DOMXPath($doc);
        $rootNamespace = $doc->lookupNamespaceUri($doc->namespaceURI);
        $this->xpath->registerNamespace('zs', $rootNamespace);
    }

    public function getNumber()
    {
        if (null === $this->number) {
            $this->number = $this->xpath->evaluate('number(./zs:number[1])', $this->step);
        }
        return $this->number;
    }

    public function getObject()
    {
        if (null === $this->object) {
            $this->object = $this->xpath->evaluate('string(./zs:object[1])', $this->step);
        }
        return $this->object;
    }

    public function getClass()
    {
        if (null === $this->class) {
            $this->class = $this->xpath->evaluate('string(./zs:class[1])', $this->step);
        }
        return $this->class;
    }

    public function getFunction()
    {
        if (null === $this->function) {
            $this->function = $this->xpath->evaluate('string(./zs:function[1])', $this->step);
        }
        return $this->function;
    }

    public function getFile()
    {
        if (null === $this->file) {
            $this->file = $this->xpath->evaluate('string(./zs:file[1])', $this->step);
        }
        return $this->file;
    }

    public function getLine()
    {
        if (null === $this->line) {
            $this->line = $this->xpath->evaluate('number(./zs:line[1])', $this->step);
        }
        return $this->line;
    }
}