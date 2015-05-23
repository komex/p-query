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
     * The token is matched
     */
    const MATCH = 0;
    /**
     * The sequence is done
     */
    const DONE = 1;

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
     * @return string
     */
    public function onMatch();
}
