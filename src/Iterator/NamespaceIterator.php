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
        list($position) = $this->getInnerIterator()->current();
        $this->stream->seek($position + 2);
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
     * @param string $namespace
     *
     * @return $this
     */
    public function setName($namespace)
    {
        $namespaceTokens = $this->getNamespaceTokens($namespace);
        $namespaceTokensCount = count($namespaceTokens);
        $currentTokensCount = count($this->getNamespaceTokens($this->getName()));
        list($position) = $this->getInnerIterator()->current();
        $this->stream->seek($position + 2);
        while ($this->stream->valid() === true) {
            list($code) = $this->stream->current();
            if ($code !== T_WHITESPACE) {
                break;
            }
            $this->stream->next();
        }
        $this->stream->replace($this->stream->key(), $currentTokensCount, $namespaceTokens);
        $this->shiftPointers($position, ($namespaceTokensCount - $currentTokensCount));

        return $this;
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
    public function getClasses()
    {
        return new ClassInIterator(
            $this->stream,
            $this->elements,
            $this->getElement()->current()
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
            $this->getElement()->current()
        );
    }

    /**
     * @return \ArrayIterator
     */
    protected function getElement()
    {
        return $this->elements[T_NAMESPACE];
    }

    /**
     * @param string $namespace
     *
     * @return array
     */
    private function getNamespaceTokens($namespace)
    {
        $parts = explode('\\', $namespace);
        $namespaceTokens = [];
        foreach ($parts as $part) {
            if (empty($part) === false) {
                array_push($namespaceTokens, [T_STRING, $part]);
            }
            array_push($namespaceTokens, [T_NS_SEPARATOR, '\\']);
        }
        array_pop($namespaceTokens);

        return $namespaceTokens;
    }
}
