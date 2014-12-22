<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Iterator;

use PQuery\Parser\Stream;

/**
 * Class AbstractIterator
 *
 * @package PQuery\Iterator
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractIterator extends \FilterIterator
{
    /**
     * @var Stream
     */
    protected $stream;
    /**
     * @var \ArrayIterator[]
     */
    protected $elements;
    /**
     * @var array
     */
    protected $range;

    /**
     * @param Stream $stream
     * @param \ArrayIterator $elements
     * @param array $range
     */
    public function __construct(Stream $stream, \ArrayIterator $elements, array $range = [])
    {
        $this->stream = $stream;
        $this->elements = $elements;
        $this->range = $range;
        parent::__construct($this->elements[$this->getKey()]);
    }

    /**
     * @param int $position Stream position
     * @param int $length
     */
    protected function shiftPointers($position, $length)
    {
        foreach ($this->elements as $list) {
            $count = ($list->count() - 1);
            // Without reset().
            for ($index = $count; $index >= 0; $index--) {
                list($positionPointer, $finish) = $list[$index];
                if ($positionPointer >= $position) {
                    $list[$index] = [$positionPointer + $length, $finish + $length];
                }
            }
        }
    }

    /**
     * @return int
     */
    abstract protected function getKey();
}
