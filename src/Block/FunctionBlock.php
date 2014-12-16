<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Block;

/**
 * Class FunctionBlock
 *
 * @package PQuery\Block
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionBlock extends AbstractBlock
{
    /**
     * @var int
     */
    private $attributes;
    /**
     * @var int
     */
    private $visibility;

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->attributes === T_ABSTRACT;
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
     * @return bool
     */
    public function isStatic()
    {
        return $this->attributes === T_STRING;
    }

    /**
     * @return $this
     */
    public function setStatic()
    {
        $this->attributes = T_STATIC;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return ($this->visibility === null || $this->visibility === T_PUBLIC);
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->visibility === T_PROTECTED;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->visibility === T_PRIVATE;
    }

    /**
     * @return $this
     */
    public function setPublic()
    {
        $this->visibility = T_PUBLIC;

        return $this;
    }

    /**
     * @return $this
     */
    public function setProtected()
    {
        $this->visibility = T_PROTECTED;

        return $this;
    }

    /**
     * @return $this
     */
    public function setPrivate()
    {
        $this->visibility = T_PRIVATE;

        return $this;
    }
}
