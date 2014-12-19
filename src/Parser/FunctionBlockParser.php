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
     * @param Stream $stream
     *
     * @return FunctionBlock
     */
    public function extract(Stream $stream)
    {
        $block = new FunctionBlock();
        $block->setPosition($stream->key());
        $this->extractAttributes($stream, $block);

        $stream->seek($block->getPosition() + 1);
        $name = $this->extractName($stream);
        if (empty($name) === false) {
            $block->setName($name);
        }

        return $block;
    }

    /**
     * @param Stream $stream
     * @param FunctionBlock $block
     */
    private function extractAttributes(Stream $stream, FunctionBlock $block)
    {
        $stream->prev();
        while ($stream->valid() === true) {
            list($code, $value) = $stream->current();
            switch ($code) {
                case T_PUBLIC:
                    $block->setPublic();
                    break;
                case T_PROTECTED:
                    $block->setProtected();
                    break;
                case T_PRIVATE:
                    $block->setPrivate();
                    break;
                case T_STATIC:
                    $block->setStatic();
                    break;
                case T_ABSTRACT:
                    $block->setAbstract();
                    break;
                case T_DOC_COMMENT:
                    $block->setDocBlock($value);
                    break;
                case T_FINAL:
                    $block->setFinal();
                    break;
                case T_WHITESPACE:
                    break;
                default:
                    break 2;
            }
            $stream->prev();
        }
        if ($block->getPosition() - $stream->key() > 2) {
            $block->setStart($stream->key());
        } else {
            $block->setStart($block->getPosition());
        }
    }
}
