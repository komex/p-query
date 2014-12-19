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
     * @param bool $abstract
     *
     * @return $this
     */
    public function setAbstract($abstract = true)
    {
        if ($abstract === true) {
            $this->attributes = T_ABSTRACT;
        } elseif ($this->attributes === T_ABSTRACT) {
            $this->attributes = null;
        }

        return $this;
    }

    /**
     * @param bool $final
     *
     * @return $this
     */
    public function setFinal($final = true)
    {
        $this->attributes = ($final ? T_FINAL : null);

        return $this;
    }
}
