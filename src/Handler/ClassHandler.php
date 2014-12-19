<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Handler;

/**
 * Class ClassHandler
 *
 * @package PQuery\Handler
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
