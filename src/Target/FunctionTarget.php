<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */

namespace Perk\Target;

use Perk\Parser\Stream;

/**
 * Class FunctionTarget
 *
 * @package Perk\Target
 * @author Andrey Kolchenko <a.j.kolchenko@baltsoftservice.ru>
 */
class FunctionTarget implements TargetInterface
{
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
        // TODO: Implement takeControl() method.
    }
}
