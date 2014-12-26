<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Processor;

use Perk\Controller;

/**
 * Class AbstractProcessor
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var Controller
     */
    protected $controller;
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param Controller $controller
     *
     * @return $this
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param int $token
     *
     * @return bool
     */
    protected function checkAttribute($token)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute[0] === $token) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \SplQueue $attributes
     *
     * @return array
     */
    protected function extractAttributes(\SplQueue $attributes)
    {
        $tokens = [];
        foreach ($attributes as $attribute) {
            unset($attribute[2]);
            array_push($tokens, $attribute);
        }

        return $tokens;
    }
}
