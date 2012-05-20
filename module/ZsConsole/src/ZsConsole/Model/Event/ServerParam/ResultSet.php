<?php

namespace ZsConsole\Model\Event\ServerParam;

use \DOMNodeList, \Iterator, \Countable,
    ZsConsole\Model\Event\ServerParam\Entity;

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

    public function __construct(\DOMNodeList $data)
    {
        $this->object = $data;
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
        return new Entity($this->object->item($this->key()));
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
        if (null === $this->length) {
            $this->length = $this->object->length;
        }
        return $this->length;
    }

    public function toString()
    {
        $this->rewind();
        $output = '';
        foreach ($this as $param) {
            $output .= '["'.$param->getName() .'"] => '. $param->getValue() . '<br />';
        }
        return $output;
    }
}