<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Event;

/**
 * Class ParserEvents
 *
 * @package Perk\Handler
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
final class ParserEvents
{
    /**
     * Increase level
     */
    const LEVEL_UP = 'parser.level.up';
    /**
     * Decrease level
     */
    const LEVEL_DOWN = 'parser.level.down';
    /**
     * Add new element event
     */
    const NEW_ELEMENT = 'parser.add.element';
    /**
     * Finish parsing event
     */
    const FINISH = 'parser.finish';
}
