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
            $this->stream->insert($this->getElement()->key(), [[T_FINAL, 'final'], [T_WHITESPACE, ' ']]);
            // @todo Recount positions of all elements.
            $newPosition = $this->getElement()->key() + 2;
            $this->getElement()->offsetSet($newPosition, $this->getElement()->current());
            $this->getElement()->offsetUnset($this->getElement()->key());
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
            $this->stream->insert($this->getElement()->key(), [[T_ABSTRACT, 'abstract'], [T_WHITESPACE, ' ']]);
            $newPosition = $this->getElement()->key() + 2;
            $this->getElement()->offsetSet($newPosition, $this->getElement()->current());
            $this->getElement()->offsetUnset($this->getElement()->key());
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
            [$this->getElement()->key(), $this->getElement()->current()]
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
            [$this->getElement()->key(), $this->getElement()->current()]
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
