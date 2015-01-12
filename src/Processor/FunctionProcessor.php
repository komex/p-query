<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Processor;

use Perk\Parser\Stream;

/**
 * Class FunctionProcessor
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionProcessor extends AbstractProcessor
{
    /**
     * @return int|string
     */
    public function getKeyWord()
    {
        return T_FUNCTION;
    }

    /**
     * @return array
     */
    public function getStopWords()
    {
        return [T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_ABSTRACT, T_FINAL];
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        if ($this->checkAttribute(T_PUBLIC) === true) {
            return true;
        } else {
            return $this->checkAttribute(T_PROTECTED) === false && $this->checkAttribute(T_PRIVATE) === false;
        }
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->checkAttribute(T_PROTECTED);
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->checkAttribute(T_PRIVATE);
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->checkAttribute(T_STATIC);
    }

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
     * @param Stream $stream
     * @param array $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, array $attributes)
    {
        list(, $current) = $stream->current();
        $this->attributes = $attributes;
        $content = '';
        foreach ($attributes as $attribute) {
            $content .= $attribute[1];
        }

        return $content . $current;
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
