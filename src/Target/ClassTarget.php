<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Target;

use Perk\Parser\Stream;

/**
 * Class ClassTarget
 *
 * @package Perk\Target
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassTarget implements TargetInterface
{
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
     * @param \SplStack $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, \SplStack $attributes)
    {
        // TODO: Implement takeControl() method.
    }
}
