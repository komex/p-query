<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

use PQuery\Handler\ClassHandler;
use PQuery\Handler\FunctionHandler;
use PQuery\Handler\LevelHandler;
use PQuery\Handler\NamespaceHandler;
use PQuery\Iterator\ClassIterator;
use PQuery\Iterator\FunctionIterator;
use PQuery\Iterator\NamespaceIterator;
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
    private $elements;
    /**
     * @var \ArrayIterator
     */
    private $stream;

    /**
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $parser = new Parser();
        $parser->addSubscriber(new NamespaceHandler());
        $parser->addSubscriber(new LevelHandler());
        $parser->addSubscriber(new ClassHandler());
        $parser->addSubscriber(new FunctionHandler());
        $parser->parse($stream);
        $this->elements = new \ArrayIterator(
            [
                T_NAMESPACE => $parser->getNamespaces(),
                T_CLASS => $parser->getClasses(),
                T_FUNCTION => $parser->getFunctions(),
            ]
        );
        $this->stream = $stream;
    }

    /**
     * @return NamespaceIterator
     */
    public function getNamespaces()
    {
        return new NamespaceIterator($this->stream, $this->elements);
    }

    /**
     * @return ClassIterator
     */
    public function getClasses()
    {
        return new ClassIterator($this->stream, $this->elements);
    }

    /**
     * @return FunctionIterator
     */
    public function getFunctions()
    {
        return new FunctionIterator($this->stream, $this->elements);
    }

    /**
     * @return string
     */
    public function save()
    {
        $content = '';
        foreach ($this->stream as $token) {
            $content .= $token[1];
        }

        return $content;
    }
}
