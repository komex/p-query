<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

/**
 * Class AbstractLayoutIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractLayoutIterator extends AbstractIterator
{
    /**
     * @return string
     */
    public function getName()
    {
        list($position) = $this->getInnerIterator()->current();
        $this->stream->seek($position + 2);
        while ($this->stream->valid() === true) {
            list($code, $value) = $this->stream->current();
            if ($code === T_STRING) {
                return $value;
            }
            $this->stream->next();
        }
        throw new \RuntimeException('Unexpected end of stream.');
    }

    /**
     * @param int $attribute
     * @param array $allowed
     *
     * @return bool
     */
    protected function isAttributeExists($attribute, array $allowed = [])
    {
        list($position) = $this->getInnerIterator()->current();
        $this->stream->seek($position - 1);
        while ($this->stream->valid() === true) {
            list($code) = $this->stream->current();
            if ($code === $attribute) {
                return true;
            } elseif (in_array($code, $allowed) === false) {
                break;
            }
            $this->stream->prev();
        }

        return false;
    }
}
