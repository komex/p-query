<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Handler;

/**
 * Class FunctionHandler
 *
 * @package Perk\Handler
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionHandler extends AbstractStructureHandler
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [T_FUNCTION => 'handle'];
    }

    /**
     * @return int
     */
    protected function getType()
    {
        return T_FUNCTION;
    }
}
