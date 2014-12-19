<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

use PQuery\Block\NamespaceBlock;

/**
 * Class NamespaceBlockParser
 *
 * @package PQuery\Parser
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceBlockParser extends AbstractBlockParser
{
    /**
     * @param Stream $stream
     *
     * @return NamespaceBlock
     */
    public function extract(Stream $stream)
    {
        $position = $stream->key();
        $stream->next();
        $block = new NamespaceBlock($this->extractName($stream));
        $block->setPosition($position);
        $block->setStart($position);
        while ($stream->valid() === true) {
            list(, $value) = $stream->current();
            if ($value === ';') {
                break;
            } elseif ($value === '{') {
                $block->setLimited(true);
                break;
            }
            $stream->next();
        }

        return $block;
    }
}
