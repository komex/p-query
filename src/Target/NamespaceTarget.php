<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Target;

use Perk\Parser\Stream;

/**
 * Class NamespaceTarget
 *
 * @package Perk\Target
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceTarget implements TargetInterface
{
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
        // TODO: Implement takeControl() method.
    }
}
