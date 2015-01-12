<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Layout;

use Perk\Processor\ProcessorInterface;

/**
 * Interface ClassReadInterface
 *
 * @package Perk\Layout
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
interface ClassReadInterface
{
    /**
     * @return bool
     */
    public function isAbstract();

    /**
     * @return bool
     */
    public function isFinal();

    /**
     * @return ProcessorInterface
     */
    public function getLayout();
}
