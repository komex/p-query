<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;

use Perk\Parser\Stream;

/**
 * Class Document
 *
 * @package Perk
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Document
{
    /**
     * @var Stream
     */
    private $stream;

    /**
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * @return string
     */
    public function save()
    {
        $content = '';
        foreach ($this->stream as $token) {
            $content .= $token[1];
        }

        return $content;
    }
}
