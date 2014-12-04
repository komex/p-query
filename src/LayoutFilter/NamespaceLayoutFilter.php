<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\LayoutFilter;

/**
 * Class NamespaceLayoutFilter
 *
 * @package PQuery\LayoutFilter
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceLayoutFilter extends \FilterIterator
{
    /**
     * @var \ArrayIterator
     */
    private $stream;
    /**
     * @var string
     */
    private $mask;

    /**
     * @param \ArrayIterator $stream
     * @param FileLayoutFilter $iterator
     * @param string $mask
     */
    public function __construct(\ArrayIterator $stream, FileLayoutFilter $iterator, $mask)
    {
        parent::__construct($iterator);
        $this->stream = $stream;
        $this->mask = $mask;
    }

    /**
     * Check whether the current element of the iterator is acceptable
     *
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    public function accept()
    {
        list($code, $value) = $this->getToken();

        return true;
    }

    /**
     * @return array [$code, $value]
     */
    private function getToken()
    {
        $token = $this->current();
        if (is_array($token) === true) {
            list($code, $value) = $token;
        } else {
            $code = null;
            $value = $token;
        }

        return [$code, $value];
    }
}
