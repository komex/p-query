<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;

use Perk\Parser\Stream;
use Perk\Processor\ClassProcessor;
use Perk\Processor\FunctionProcessor;
use Perk\Processor\NamespaceProcessor;
use Perk\Processor\ProcessorInterface;

/**
 * Class Document
 *
 * @package Perk
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Document
{
    /**
     * @var Controller
     */
    private $controller;

    /**
     * @param Init document
     */
    public function __construct()
    {
        $this->controller = new Controller();
        $this->controller->bind(new NamespaceProcessor());
        $this->controller->bind(new ClassProcessor());
        $this->controller->bind(new FunctionProcessor());
    }

    /**
     * @param \Closure $handler
     *
     * @return $this
     */
    public function setClassHandler(\Closure $handler)
    {
        $processor = $this->controller->getProcessorForKeyWord(T_CLASS);
        if ($processor instanceof ProcessorInterface) {
            $processor->setHandler($handler);
        }

        return $this;
    }

    /**
     * @param \Closure $handler
     *
     * @return $this
     */
    public function setFunctionHandler(\Closure $handler)
    {
        $processor = $this->controller->getProcessorForKeyWord(T_FUNCTION);
        if ($processor instanceof ProcessorInterface) {
            $processor->setHandler($handler);
        }

        return $this;
    }

    /**
     * @param Stream $stream
     *
     * @return string
     */
    public function applyChanges(Stream $stream)
    {
        return $this->controller->applyChanges($stream);
    }
}
