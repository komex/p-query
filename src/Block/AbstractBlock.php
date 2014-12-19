<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Block;

/**
 * Class AbstractBlock
 *
 * @package PQuery\Block
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractBlock
{
    /**
     * @var int
     */
    private $position;
    /**
     * @var int
     */
    private $start;
    /**
     * @var int
     */
    private $finish;
    /**
     * @var string
     */
    private $name;
    /**
     * @var AbstractBlock
     */
    private $parent;
    /**
     * @var AbstractBlock[]
     */
    private $children = [];
    /**
     * @var string
     */
    private $docBlock;

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return int
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * @param int $finish
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return AbstractBlock
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param AbstractBlock $parent
     */
    public function setParent(AbstractBlock $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return AbstractBlock[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param AbstractBlock $children
     */
    public function addChildren(AbstractBlock $children)
    {
        array_push($this->children, $children);
    }

    /**
     * @return string
     */
    public function getDocBlock()
    {
        return $this->docBlock;
    }

    /**
     * @param string $docBlock
     */
    public function setDocBlock($docBlock)
    {
        $this->docBlock = $docBlock;
    }
}
