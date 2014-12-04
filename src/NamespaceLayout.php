<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

use PQuery\LayoutFilter\FileLayoutFilter;
use PQuery\LayoutFilter\NamespaceLayoutFilter;

/**
 * Class NamespaceLayout
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceLayout
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
     * @param FileLayoutFilter $filter
     * @param string|null $mask
     */
    public function __construct(\ArrayIterator $stream, FileLayoutFilter $filter, $mask)
    {
        $this->stream = $stream;
        $this->filter = new NamespaceLayoutFilter($stream, $filter, $mask);
        foreach ($this->filter as $token) {
            $a = 1;
        }
    }
}
