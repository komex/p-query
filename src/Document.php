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
        $this->stream = $stream;
    }
}
