<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;

use Perk\Handler\ClassHandler;
use Perk\Handler\FunctionHandler;
use Perk\Handler\LevelHandler;
use Perk\Handler\NamespaceHandler;
use Perk\Iterator\ClassIterator;
use Perk\Iterator\FunctionIterator;
use Perk\Iterator\NamespaceIterator;
use Perk\Parser\Parser;
use Perk\Parser\Stream;

/**
 * Class Document
 *
 * @package Perk
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
