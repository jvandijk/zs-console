<?php

namespace ZsConsole\Model\Issue;

use \DOMDocument, \Iterator, \Countable,
    ZsConsole\Model\Issue\Entity;

class ResultSet implements Iterator, Countable
{
    /**
     *
     * @var \DOMDocument
     */
    protected $object;

    /**
     *
     * @var \DOMNodeList
     */
    protected $issues;

    protected $position = 0;

    protected $length = null;

    public function __construct()
    {
        $this->object = new DOMDocument();
    }

    public function setData($data)
    {
        $this->object->loadXML($data);
        $this->issues = $this->object->getElementsByTagName('issue');
        return $this;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function current()
    {
        return new Entity($this->issues->item($this->key()));
    }

    public function valid()
    {
        if ($this->key() < $this->count()) {
            return true;
        }
        return false;
    }

    public function count()
    {
        // length is always calculated, so lazy load it!
        if (null == $this->length) {
            $this->length = $this->issues->length;
        }
        return $this->length;
    }
}