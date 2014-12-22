<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Handler;

/**
 * Class ClassHandler
 *
 * @package Perk\Handler
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassHandler extends AbstractStructureHandler
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [T_CLASS => 'handle'];
    }

    /**
     * @return int
     */
    protected function getType()
    {
        return T_CLASS;
    }
}
