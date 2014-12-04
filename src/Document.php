<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

use PQuery\LayoutFilter\FileLayoutFilter;

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
     * @var FileLayoutFilter
     */
    private $filter;

    /**
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        $this->stream = $stream;
        $this->filter = new FileLayoutFilter($stream);
    }

    /**
     * @param string|null $mask
     *
     * @return NamespaceLayout
     */
    public function getNamespace($mask = null)
    {
        return new NamespaceLayout($this->stream, $this->filter, $mask);
    }
}
