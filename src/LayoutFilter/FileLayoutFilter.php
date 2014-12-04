<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\LayoutFilter;

/**
 * Class FileLayoutFilter
 *
 * @package PQuery\LayoutFilter
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FileLayoutFilter extends \FilterIterator
{
    /**
     * @var bool
     */
    private $opened = false;

    /**
     * Check whether the current element of the iterator is acceptable
     *
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    public function accept()
    {
        $token = $this->current();
        if (is_array($token) === false) {
            return false;
        }
        $token = $token[0];
        if ($this->opened) {
            if ($token === T_CLOSE_TAG) {
                $this->opened = false;

                return false;
            }

            return true;
        } else {
            $this->opened = true;

            return false;
        }
    }
}
