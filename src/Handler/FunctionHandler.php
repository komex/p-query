<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Handler;

/**
 * Class FunctionHandler
 *
 * @package PQuery\Handler
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
