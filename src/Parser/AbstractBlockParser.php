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
     * @param \ArrayIterator $stream
     *
     * @return AbstractBlock
     */
    abstract public function extract(\ArrayIterator $stream);

    /**
     * @param \ArrayIterator $stream
     *
     * @return string
     */
    protected function extractName(\ArrayIterator $stream)
    {
        $name = '';
        while ($stream->valid() === true) {
            list($code, $value) = $this->getToken($stream->current());
            if ($code === T_STRING || $code === T_NS_SEPARATOR) {
                $name .= $value;
            } elseif ($code !== T_WHITESPACE) {
                break;
            }
            $stream->next();
        }

        return $name;
    }

    /**
     * @param array|string $token
     *
     * @return array
     */
    protected function getToken($token)
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
