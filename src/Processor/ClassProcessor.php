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
 * Class ClassProcessor
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassProcessor implements ProcessorInterface
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
        return T_CLASS;
    }

    /**
     * @return array
     */
    public function getStopWords()
    {
        return [T_ABSTRACT, T_FINAL];
    }

    /**
     * @param Stream $stream
     * @param \SplQueue $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, \SplQueue $attributes)
    {
        $content = '';
        foreach ($attributes as $attribute) {
            $content .= $attribute[1];
        }

        return $content . 'class';
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
