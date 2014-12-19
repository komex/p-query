<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Event;

/**
 * Class ParserEvents
 *
 * @package PQuery\Handler
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
