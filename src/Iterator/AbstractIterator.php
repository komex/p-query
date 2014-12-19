<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

use PQuery\Parser\Stream;

/**
 * Class AbstractIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractIterator implements \Iterator, \Countable
{
    /**
     * @var Stream
     */
    protected $stream;
    /**
     * @var \ArrayIterator[]
     */
    protected $elements;

    /**
     * @param Stream $stream
     * @param \ArrayIterator $elements
     */
    public function __construct(Stream $stream, \ArrayIterator $elements)
    {
        $this->stream = $stream;
        $this->elements = $elements;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->getElement()->next();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->getElement()->key();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->getElement()->valid();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->getElement()->rewind();
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->getElement()->count();
    }

    /**
     * @return \ArrayIterator
     */
    abstract protected function getElement();
}
