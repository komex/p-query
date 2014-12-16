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
     * @var bool
     */
    private $static = false;
    /**
     * @var bool
     */
    private $abstract = false;
    /**
     * @var bool
     */
    private $final = false;
    /**
     * @var int
     */
    private $visibility;

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param bool $abstract
     *
     * @return $this
     */
    public function setAbstract($abstract = true)
    {
        $this->abstract = $abstract;
        if ($abstract === true) {
            $this->final = false;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->static;
    }

    /**
     * @param bool $static
     *
     * @return $this
     */
    public function setStatic($static = true)
    {
        $this->static = (bool)$static;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->final;
    }

    /**
     * @param bool $final
     *
     * @return $this
     */
    public function setFinal($final = true)
    {
        $this->final = (bool)$final;
        if ($this->final === true) {
            $this->abstract = false;
        }

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
