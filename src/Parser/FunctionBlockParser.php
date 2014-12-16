<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

use PQuery\Block\FunctionBlock;

/**
 * Class FunctionBlockParser
 *
 * @package PQuery\Parser
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionBlockParser extends AbstractBlockParser
{
    /**
     * @param \ArrayIterator $stream
     *
     * @return FunctionBlock
     */
    public function extract(\ArrayIterator $stream)
    {
        $block = new FunctionBlock();
        $position = $stream->key();
        $block->setPosition($position);
        $block->setStart($position);
        $stream->seek($position + 1);
        $name = $this->extractName($stream);
        if (empty($name) === false) {
            $block->setName($name);
        }

        return $block;
    }
}
