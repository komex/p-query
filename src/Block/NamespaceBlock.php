<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Block;

/**
 * Class NamespaceBlock
 *
 * @package PQuery\Block
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceBlock extends AbstractBlock
{
    /**
     * @var bool
     */
    private $limited = false;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @return boolean
     */
    public function isLimited()
    {
        return $this->limited;
    }

    /**
     * @param boolean $limited
     */
    public function setLimited($limited)
    {
        $this->limited = (bool)$limited;
    }
}
