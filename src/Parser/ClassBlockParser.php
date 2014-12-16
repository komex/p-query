<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

use PQuery\Block\ClassBlock;

/**
 * Class ClassBlockParser
 *
 * @package PQuery\Parser
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassBlockParser extends AbstractBlockParser
{
    /**
     * @param \ArrayIterator $stream
     *
     * @return ClassBlock
     */
    public function extract(\ArrayIterator $stream)
    {
        $position = $stream->key();
        $stream->next();
        $block = new ClassBlock($this->extractName($stream));
        $block->setPosition($position);
        $block->setStart($position);

        return $block;
    }
}
