<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Processor;

use Perk\Controller;
use Perk\Parser\Stream;

/**
 * Class FunctionProcessor
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionProcessor implements ProcessorInterface
{
    /**
     * @var Controller
     */
    private $controller;

    /**
     * @return int|string
     */
    public function getKeyWord()
    {
        return T_FUNCTION;
    }

    /**
     * @return array
     */
    public function getStopWords()
    {
        return [T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_ABSTRACT, T_FINAL];
    }

    /**
     * @param Stream $stream
     * @param \SplStack $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, \SplStack $attributes)
    {
        // TODO: Implement keepControl() method.
    }

    /**
     * @return bool
     */
    public function trackLevel()
    {
        return true;
    }

    /**
     * @param Stream $stream
     *
     * @return $this
     */
    public function onSameLevel(Stream $stream)
    {
        // TODO: Implement onSameLevel() method.
    }

    /**
     * @param Controller $controller
     *
     * @return $this
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;

        return $this;
    }
}
