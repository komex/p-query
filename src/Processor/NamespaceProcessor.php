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
 * Class NamespaceProcessor
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceProcessor implements ProcessorInterface
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
        return T_NAMESPACE;
    }

    /**
     * @return array
     */
    public function getStopWords()
    {
        return [];
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
        // TODO: Implement trackLevel() method.
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
