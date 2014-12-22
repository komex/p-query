<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Iterator;

/**
 * Class AbstractLayoutIterator
 *
 * @package Perk\Iterator
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
     * @param string $name
     */
    public function setName($name)
    {
        // Move pointer to name position.
        $this->getName();
        $this->stream->replace($this->stream->key(), 1, [[T_STRING, $name]]);
    }

    /**
     * @param bool $final
     *
     * @return $this
     */
    public function setFinal($final = true)
    {
        return $this->attributeManager($final, $this->isFinal(), [T_FINAL, 'final']);
    }

    /**
     * @param bool $abstract
     *
     * @return $this
     */
    public function setAbstract($abstract = true)
    {
        return $this->attributeManager($abstract, $this->isAbstract(), [T_ABSTRACT, 'abstract']);
    }

    /**
     * @return bool
     */
    abstract public function isAbstract();

    /**
     * @return bool
     */
    abstract public function isFinal();

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

    /**
     * @param int $position Stream position
     * @param array $tokens
     */
    protected function insert($position, array $tokens)
    {
        $this->stream->insert($position, $tokens);
        $this->shiftPointers($position, count($tokens));
    }

    /**
     * @param int $position Stream position
     * @param int $length
     */
    protected function remove($position, $length)
    {
        $this->stream->remove($position, $length);
        $this->shiftPointers($position, -$length);
    }

    /**
     * @param bool $need
     * @param bool $exists
     * @param array $token
     *
     * @return $this
     */
    protected function attributeManager($need, $exists, array $token)
    {
        if ($need === true && $exists === false) {
            list($position) = $this->getInnerIterator()->current();
            $this->insert($position, [$token, [T_WHITESPACE, ' ']]);
        } elseif ($need === false && $exists === true) {
            $this->remove($this->stream->key(), 2);
        }

        return $this;
    }
}
