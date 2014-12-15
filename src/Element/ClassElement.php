<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Element;

/**
 * Class ClassElement
 *
 * @package PQuery\Element
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassElement extends AbstractElement
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $attribute;

    /**
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        $this->stream = $stream;
        $this->position = $stream->key();
        $this->loadAttributes();
        $this->loadName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->attribute === T_ABSTRACT;
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->attribute === T_FINAL;
    }

    /**
     * Load name of function.
     */
    private function loadName()
    {
        $this->stream->seek($this->position + 2);
        $this->name = $this->extractName($this->stream);
    }

    /**
     * @return $this
     */
    private function loadAttributes()
    {
        $offset = ($this->position - 1);
        $this->stream->seek($offset);
        while ($this->stream->valid()) {
            list($code) = $this->getToken($this->stream->current());
            if ($code === T_ABSTRACT || $code === T_FINAL) {
                $this->attribute = $code;
            } elseif ($code !== T_WHITESPACE) {
                break;
            }
            $this->stream->seek(--$offset);
        }
    }
}
