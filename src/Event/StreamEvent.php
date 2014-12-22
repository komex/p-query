<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Event;

use Perk\Parser\Stream;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class StreamEvent
 *
 * @package Perk\Event
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class StreamEvent extends Event
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
     * @return Stream
     */
    public function getStream()
    {
        return $this->stream;
    }
}
