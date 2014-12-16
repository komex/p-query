<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

use PQuery\Block\AbstractBlock;

/**
 * Class AbstractBlockParser
 *
 * @package PQuery\Parser
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractBlockParser
{
    /**
     * @param Stream $stream
     *
     * @return AbstractBlock
     */
    abstract public function extract(Stream $stream);

    /**
     * @param Stream $stream
     *
     * @return string
     */
    protected function extractName(Stream $stream)
    {
        $name = '';
        while ($stream->valid() === true) {
            list($code, $value) = $stream->current();
            if ($code === T_STRING || $code === T_NS_SEPARATOR) {
                $name .= $value;
            } elseif ($code !== T_WHITESPACE) {
                break;
            }
            $stream->next();
        }

        return $name;
    }
}
