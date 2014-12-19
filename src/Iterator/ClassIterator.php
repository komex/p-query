<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

use PQuery\Parser\Stream;

/**
 * Class ClassIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassIterator implements \Iterator, \Countable
{
    /**
     * @var Stream
     */
    private $stream;
    /**
     * @var \ArrayIterator[]
     */
    private $elements;

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
     * @return string
     */
    public function getName()
    {
        $this->stream->seek($this->elements[T_CLASS]->key() + 2);
        while ($this->stream->valid() === true) {
            list($code, $value) = $this->stream->current();
            if ($code === T_STRING) {
                return $value;
            }
            $this->stream->next();
        }
        throw new \RuntimeException('Unexpected end of stream.');
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        $this->stream->seek($this->elements[T_CLASS]->key() - 1);
        while ($this->stream->valid() === true) {
            list($code) = $this->stream->current();
            if ($code === T_ABSTRACT) {
                return true;
            } elseif ($code !== T_WHITESPACE && $code !== T_FINAL) {
                break;
            }
            $this->stream->prev();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        $this->stream->seek($this->elements[T_CLASS]->key() - 1);
        while ($this->stream->valid() === true) {
            list($code) = $this->stream->current();
            if ($code === T_FINAL) {
                return true;
            } elseif ($code !== T_WHITESPACE && $code !== T_ABSTRACT) {
                break;
            }
            $this->stream->prev();
        }

        return false;
    }

    /**
     * @return ClassIterator
     */
    public function current()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->elements[T_CLASS]->next();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->elements[T_CLASS]->key();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->elements[T_CLASS]->valid();
    }


    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->elements[T_CLASS]->rewind();
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->elements[T_CLASS]->count();
    }
}
