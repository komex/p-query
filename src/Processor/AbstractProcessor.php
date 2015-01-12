<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Processor;

use Perk\Controller;
use Perk\Parser\Stream;

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
     * @var \Closure
     */
    protected $handler;

    /**
     * @param Stream $stream
     * @param array $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, array $attributes)
    {
        $this->attributes = $attributes;
        list(, $current) = $stream->current();
        if ($this->handler !== null) {
            call_user_func($this->handler, $this);
        }
        $content = '';
        foreach ($this->attributes as $attribute) {
            $content .= $attribute[1];
        }

        return $content . $current;
    }

    /**
     * @param \Closure $handler
     *
     * @return $this
     */
    public function setHandler(\Closure $handler)
    {
        $this->handler = $handler;

        return $this;
    }

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
}
