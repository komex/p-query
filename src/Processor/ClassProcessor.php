<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Processor;

use Perk\Parser\Stream;

/**
 * Class ClassProcessor
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassProcessor extends AbstractProcessor
{
    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->checkAttribute(T_ABSTRACT);
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->checkAttribute(T_FINAL);
    }

    /**
     * @return int|string
     */
    public function getKeyWord()
    {
        return T_CLASS;
    }

    /**
     * @return array
     */
    public function getStopWords()
    {
        return [T_ABSTRACT, T_FINAL];
    }

    /**
     * @param Stream $stream
     * @param \SplQueue $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, \SplQueue $attributes)
    {
        $this->attributes = $this->extractAttributes($attributes);
        $content = '';
        foreach ($this->attributes as $attribute) {
            $content .= $attribute[1];
        }

        return $content . 'class';
    }

    /**
     * @return bool
     */
    public function trackLevel()
    {
        return true;
    }

    /**
     * @param Stream $stream
     *
     * @return string
     */
    public function onSameLevel(Stream $stream)
    {
        return '';
    }
}
