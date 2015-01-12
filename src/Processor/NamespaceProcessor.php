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
     * @var \Closure
     */
    private $handler;

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
     * @param \Closure $handler
     *
     * @return $this
     */
    public function setHandler(\Closure $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Stream $stream
     * @param array $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, array $attributes)
    {
        list(, $current) = $stream->current();
        $this->controller->setLayout($this);
        $content = '';
        foreach ($attributes as $attribute) {
            $content .= $attribute[1];
        }

        return $content . $current;
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
     * @return string
     */
    public function onSameLevel(Stream $stream)
    {
        return '';
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
