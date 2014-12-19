<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

/**
 * Class Stream
 *
 * @package PQuery\Parser
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Stream implements \SeekableIterator, \Countable
{
    /**
     * @var array
     */
    private $tokens;
    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = array_values($tokens);
    }

    /**
     * @param int $position
     * @param int $length
     */
    public function remove($position, $length = 1)
    {
        array_splice($this->tokens, $position, $length);
    }

    /**
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return array [$code, $value]
     */
    public function current()
    {
        return $this->getToken($this->tokens[$this->position]);
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Move backward to prev element
     */
    public function prev()
    {
        $this->position--;
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     */
    public function valid()
    {
        return isset($this->tokens[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    public function count()
    {
        return count($this->tokens);
    }

    /**
     * Seeks to a position
     *
     * @link http://php.net/manual/en/seekableiterator.seek.php
     *
     * @param int $position
     */
    public function seek($position)
    {
        $this->position = $position;
    }

    /**
     * @param array|string $token
     *
     * @return array
     */
    public function getToken($token)
    {
        if (is_array($token) === true) {
            list($code, $value) = $token;
        } else {
            $code = null;
            $value = $token;
        }

        return [$code, $value];
    }
}
