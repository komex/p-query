<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Element;

/**
 * Class FunctionElement
 *
 * @package PQuery\Element
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionElement extends AbstractElement
{
    /**
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        assert('$this->getToken($stream->current())[0] === T_FUNCTION');
        $position = $stream->key();
        $stream->seek($position + 2);
        $this->name = $this->extractName($stream);
        $this->position = $position;
    }
}
