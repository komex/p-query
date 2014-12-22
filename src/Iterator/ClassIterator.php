<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

use PQuery\Iterator\In\FunctionInIterator;
use PQuery\Iterator\Out\NamespaceOutIterator;

/**
 * Class ClassIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
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
     * @return ClassIterator
     */
    public function current()
    {
        return $this;
    }

    /**
     * @return NamespaceIterator
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
     * @return FunctionIterator
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
     * @inheritdoc
     */
    public function accept()
    {
        return true;
    }

    /**
     * @return \ArrayIterator
     */
    protected function getElement()
    {
        return $this->elements[T_CLASS];
    }
}
