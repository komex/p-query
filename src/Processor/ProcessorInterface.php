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
 * Interface ProcessorInterface
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface ProcessorInterface
{
    /**
     * @return int|string
     */
    public function getKeyWord();

    /**
     * @return array
     */
    public function getStopWords();

    /**
     * @param Stream $stream
     * @param array $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, array $attributes);

    /**
     * @return bool
     */
    public function trackLevel();

    /**
     * @param Stream $stream
     *
     * @return string
     */
    public function onSameLevel(Stream $stream);

    /**
     * @param Controller $controller
     *
     * @return $this
     */
    public function setController(Controller $controller);

    /**
     * @param \Closure $handler
     *
     * @return $this
     */
    public function setHandler(\Closure $handler);
}
