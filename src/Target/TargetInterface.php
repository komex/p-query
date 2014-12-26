<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Target;

use Perk\Parser\Stream;


/**
 * Interface TargetInterface
 *
 * @package Perk\Target
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface TargetInterface
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
     * @param \SplStack $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, \SplStack $attributes);
}
