<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator;

use Perk\Iterator\In\FunctionInIterator;
use Perk\Iterator\Out\NamespaceOutIterator;

/**
 * Class ClassIterator
 *
 * @package Perk\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 * @method ClassIterator current()
 */
class ClassIterator extends AbstractLayoutIterator
{
    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->isAttributeExists(T_ABSTRACT, [T_WHITESPACE, T_FINAL]);
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->isAttributeExists(T_FINAL, [T_WHITESPACE, T_ABSTRACT]);
    }

    /**
     * @return NamespaceOutIterator
     */
    public function getNamespace()
    {
        return new NamespaceOutIterator(
            $this->stream,
            $this->elements,
            $this->getInnerIterator()->current()
        );
    }

    /**
     * @return FunctionInIterator
     */
    public function getFunctions()
    {
        return new FunctionInIterator(
            $this->stream,
            $this->elements,
            $this->getInnerIterator()->current()
        );
    }

    /**
     * @return int
     */
    protected function getKey()
    {
        return T_CLASS;
    }
}
