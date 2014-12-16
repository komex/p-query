<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Block;

/**
 * Class ClassBlock
 *
 * @package PQuery\Block
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassBlock extends AbstractBlock
{
    /**
     * @var int
     */
    private $attributes;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->attributes === T_ABSTRACT;
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->attributes === T_FINAL;
    }

    /**
     * @return $this
     */
    public function setAbstract()
    {
        $this->attributes = T_ABSTRACT;

        return $this;
    }

    /**
     * @return $this
     */
    public function setFinal()
    {
        $this->attributes = T_FINAL;

        return $this;
    }
}
