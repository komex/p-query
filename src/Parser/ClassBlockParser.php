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
     * @param Stream $stream
     *
     * @return ClassBlock
     */
    public function extract(Stream $stream)
    {
        $block = new ClassBlock(null);
        $block->setPosition($stream->key());
        $block->setStart($stream->key());
        $stream->prev();
        while ($stream->valid() === true) {
            list($code, $value) = $stream->current();
            if ($code === T_ABSTRACT) {
                $block->setAbstract();
                $block->setStart($stream->key());
            } elseif ($code === T_FINAL) {
                $block->setFinal();
                $block->setStart($stream->key());
            } elseif ($code === T_DOC_COMMENT) {
                $block->setDocBlock($value);
                $block->setStart($stream->key());
                break;
            } elseif ($code !== T_WHITESPACE) {
                break;
            }
            $stream->prev();
        }
        $stream->seek($block->getPosition() + 1);
        $block->setName($this->extractName($stream));

        return $block;
    }
}
