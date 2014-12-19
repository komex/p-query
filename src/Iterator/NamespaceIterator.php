<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

use PQuery\Iterator\In\ClassInIterator;
use PQuery\Iterator\In\FunctionInIterator;

/**
 * Class NamespaceIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceIterator extends AbstractIterator
{
    /**
     * @return string
     */
    public function getName()
    {
        $this->stream->seek($this->getElement()->key() + 2);
        $name = '';
        while ($this->stream->valid() === true) {
            list($code, $value) = $this->stream->current();
            if ($code === T_STRING || $code === T_NS_SEPARATOR) {
                $name .= $value;
            } elseif ($code !== T_WHITESPACE) {
                break;
            }
            $this->stream->next();
        }

        return $name;
    }

    /**
     * @return NamespaceIterator
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
     * @return ClassIterator
     */
    public function classes()
    {
        return new ClassInIterator(
            $this->stream,
            $this->elements,
            [$this->getElement()->key(), $this->getElement()->current()]
        );
    }

    /**
     * @return FunctionIterator
     */
    public function functions()
    {
        return new FunctionInIterator(
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
        return $this->elements[T_NAMESPACE];
    }
}
