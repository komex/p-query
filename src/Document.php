<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

use PQuery\Parser\Parser;
use PQuery\Parser\Stream;

/**
 * Class Document
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Document
{
    /**
     * @var \ArrayIterator
     */
    private $stream;
    /**
     * @var \ArrayIterator
     */
    private $elements;

    /**
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $parser = new Parser($stream);
        $this->elements = $parser->getIterator();
        $this->stream = $stream;
    }
}
