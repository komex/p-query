<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Processor;

use Perk\Layout\ClassReadInterface;
use Perk\Parser\Stream;

/**
 * Class ClassProcessor
 *
 * @package Perk\Processor
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassProcessor extends AbstractProcessor implements ClassReadInterface
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
     * @return ProcessorInterface
     */
    public function getLayout()
    {
        return $this->controller->getLayout();
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
     * @param array $attributes
     *
     * @return string
     */
    public function takeControl(Stream $stream, array $attributes)
    {
        $content = parent::takeControl($stream, $attributes);
        $this->controller->setLayout($this);

        return $content;
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
