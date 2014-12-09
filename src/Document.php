<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

use PQuery\Collection\ClassCollection;
use PQuery\Collection\FunctionCollection;
use PQuery\Collection\NamespaceCollection;
use PQuery\Element\ClassElement;
use PQuery\Element\FunctionElement;
use PQuery\Element\NamespaceElement;

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
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        $parser = new Parser($stream);
        $this->elements = $parser->getIterator();
        $this->stream = $stream;
    }

    /**
     * @return NamespaceCollection|NamespaceElement[]
     */
    public function getNamespaces()
    {
        return new NamespaceCollection($this->elements);
    }

    /**
     * @return ClassCollection|ClassElement[]
     */
    public function getClasses()
    {
        return new ClassCollection($this->elements);
    }

    /**
     * @return FunctionCollection|FunctionElement[]
     */
    public function getFunctions()
    {
        return new FunctionCollection($this->elements);
    }
}
