<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

use PQuery\Iterator\Out\ClassOutIterator;
use PQuery\Iterator\Out\NamespaceOutIterator;

/**
 * Class FunctionIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionIterator extends AbstractLayoutIterator
{
    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->isAttributeExists(T_ABSTRACT, $this->getAllowedAttributes(T_ABSTRACT));
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->isAttributeExists(T_FINAL, $this->getAllowedAttributes(T_FINAL));
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->isAttributeExists(T_STATIC, $this->getAllowedAttributes(T_STATIC));
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->isAttributeExists(T_PUBLIC, $this->getAllowedAttributes(T_PUBLIC));
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->isAttributeExists(T_PROTECTED, $this->getAllowedAttributes(T_PROTECTED));
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->isAttributeExists(T_PRIVATE, $this->getAllowedAttributes(T_PRIVATE));
    }

    /**
     * @return FunctionIterator
     */
    public function current()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function accept()
    {
        return true;
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
     * @return ClassIterator
     */
    public function getClass()
    {
        return new ClassOutIterator(
            $this->stream,
            $this->elements,
            [$this->getElement()->key(), $this->getElement()->current()]
        );
    }

    /**
     * @return \ArrayIterator
     */
    protected function getElement()
    {
        return $this->elements[T_FUNCTION];
    }

    /**
     * @param int $exclude
     *
     * @return array
     */
    private function getAllowedAttributes($exclude)
    {
        static $attributes = [T_WHITESPACE, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_ABSTRACT, T_FINAL];
        $position = array_search($exclude, $attributes, true);
        assert('$position !== false');

        return array_merge(array_slice($attributes, 0, $position), array_slice($attributes, $position + 1));
    }
}
