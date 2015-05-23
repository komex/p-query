<?php
/**
 * Created by PhpStorm.
 * User: komex
 * Date: 5/23/15
 * Time: 3:04 PM
 */

namespace Komex\Perk;

/**
 * Interface ParserInterface
 *
 * @package Komex\Perk
 */
interface ParserInterface
{
    /**
     * The token is unknown
     */
    const UNKNOWN = 0;
    /**
     * The token is matched
     */
    const MATCH = 1;
    /**
     * Bad sequence
     */
    const RESET = 2;
    /**
     * The sequence is done
     */
    const DONE = 3;

    /**
     * @return string
     */
    public function getName();

    /**
     * @return callable[]
     */
    public function getTokensMap();

    /**
     * @return callable[]
     */
    public function getValuesMap();

    /**
     * @param callable $handler
     *
     * @return string
     */
    public function onMatch(callable $handler = null);
}
