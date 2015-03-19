<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;


/**
 * Interface ParserInterface
 *
 * @package Perk
 */
interface ParserInterface
{
    const TOKEN_UNKNOWN = 1;
    const TOKEN_ACCEPTED = 2;

    /**
     * @param $token
     *
     * @return int
     */
    public function parse($token);
}