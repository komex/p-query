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
     * @param bool $final
     *
     * @return $this
     */
    public function setFinal($final = true)
    {
        $isFinal = $this->isFinal();
        if ($final === true && $isFinal === false) {
            list($position, $finish) = $this->getElement()->current();
            $this->stream->insert($position, [[T_FINAL, 'final'], [T_WHITESPACE, ' ']]);
            $this->getElement()->offsetSet($this->getInnerIterator()->key(), [$position + 2, $finish + 2]);
            // @todo Recount positions of all elements.
            $this->setAbstract(false);
        } elseif ($final === false && $isFinal === true) {
            $this->stream->remove($this->stream->key(), 2);
        }

        return $this;
    }

    /**
     * @param bool $abstract
     *
     * @return $this
     */
    public function setAbstract($abstract = true)
    {
        $isAbstract = $this->isAbstract();
        if ($abstract === true && $isAbstract === false) {
            list($position, $finish) = $this->getInnerIterator()->current();
            $this->stream->insert($position, [[T_ABSTRACT, 'abstract'], [T_WHITESPACE, ' ']]);
            $this->getElement()->offsetSet($this->getInnerIterator()->key(), [$position + 2, $finish + 2]);
            $this->setFinal(false);
        } elseif ($abstract === false && $isAbstract === true) {
            $this->stream->remove($this->stream->key(), 2);
        }

        return $this;
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
