<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

use PQuery\Block\AbstractBlock;
use PQuery\Block\ClassBlock;
use PQuery\Block\FunctionBlock;
use PQuery\Block\NamespaceBlock;
use PQuery\Element\AbstractElement;

/**
 * Class Parser
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Parser implements \IteratorAggregate
{
    /**
     * @var AbstractElement[]
     */
    private $elements = [];
    /**
     * @var \SplStack
     */
    private $layoutTree;
    /**
     * @var int
     */
    private $treeLevel = 0;
    /**
     * @var AbstractBlock[]
     */
    private $levelListeners = [];
    /**
     * @var NamespaceBlock
     */
    private $currentNamespace;

    /**
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        $stream->rewind();
        $this->layoutTree = new \SplStack;
        while ($stream->valid() === true) {
            $token = $stream->current();
            if (is_array($token) === true) {
                $this->elementsRegister($stream, $token[0]);
            } else {
                $this->levelControl($stream->key(), $token);
            }
            $stream->next();
        }
        $this->currentNamespace->setFinish($stream->count());
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return AbstractElement[]|\ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param \ArrayIterator $stream
     * @param int $token
     */
    private function elementsRegister(\ArrayIterator $stream, $token)
    {
        switch ($token) {
            case T_FUNCTION:
                $this->registerFunction($stream);
                break;
            case T_CLASS:
                $this->registerClass($stream);
                break;
            case T_NAMESPACE:
                $this->registerNamespaceBlock($stream);
                break;
        }
    }

    /**
     * @param \ArrayIterator $stream
     */
    private function registerFunction(\ArrayIterator $stream)
    {
        $block = new FunctionBlock();
        $position = $stream->key();
        $block->setPosition($position);
        $block->setStart($position);
        $this->registerBlockFinishListener($block);
        $this->registerBlock($block);
    }

    /**
     * @param \ArrayIterator $stream
     */
    private function registerClass(\ArrayIterator $stream)
    {
        $position = $stream->key();
        $stream->next();
        $block = new ClassBlock($this->extractName($stream));
        $block->setPosition($position);
        $block->setStart($position);
        $this->registerBlockFinishListener($block);
        $this->registerBlock($block);
    }

    /**
     * @param \ArrayIterator $stream
     */
    private function registerNamespaceBlock(\ArrayIterator $stream)
    {
        $position = $stream->key();
        $stream->next();
        $block = new NamespaceBlock($this->extractName($stream));
        $block->setPosition($position);
        $block->setStart($position);
        while ($stream->valid() === true) {
            if ($stream->current() === ';') {
                break;
            } elseif ($stream->current() === '{') {
                $this->registerBlockFinishListener($block);
                $this->levelControl($stream->key(), $stream->current());
                break;
            }
            $stream->next();
        }
        if ($this->currentNamespace !== null) {
            $this->currentNamespace->setFinish($position);
        }
        $this->currentNamespace = $block;
        $this->registerBlock($block);
    }

    /**
     * @param AbstractBlock $block
     * @param bool $isLayout
     */
    private function registerBlock(AbstractBlock $block, $isLayout = true)
    {
        $this->linkBlocks($block);
        array_push($this->elements, $block);
        if ($isLayout === true) {
            $this->layoutTree->push($block);
        }
    }

    /**
     * @param AbstractBlock $block
     */
    private function linkBlocks(AbstractBlock $block)
    {
        if ($this->layoutTree->isEmpty() === false) {
            /** @var AbstractBlock $parent */
            $parent = $this->layoutTree->top();
            $block->setParent($parent);
            $parent->addChildren($block);
        }
    }

    /**
     * @param AbstractBlock $block
     */
    private function registerBlockFinishListener(AbstractBlock $block)
    {
        $this->levelListeners[$this->treeLevel] = $block;
    }

    /**
     * @param \ArrayIterator $stream
     *
     * @return string
     */
    private function extractName(\ArrayIterator $stream)
    {
        $name = '';
        while ($stream->valid() === true) {
            list($code, $value) = $this->getToken($stream->current());
            if ($code === T_STRING || $code === T_NS_SEPARATOR) {
                $name .= $value;
            } elseif ($code !== T_WHITESPACE) {
                break;
            }
            $stream->next();
        }

        return $name;
    }

    /**
     * @param array|string $token
     *
     * @return array
     */
    private function getToken($token)
    {
        if (is_array($token) === true) {
            list($code, $value) = $token;
        } else {
            $code = null;
            $value = $token;
        }

        return [$code, $value];
    }

    /**
     * @param int $key
     * @param string $value
     */
    private function levelControl($key, $value)
    {
        switch ($value) {
            case '{':
                assert('$this->treeLevel >= 0');
                $this->treeLevel++;
                break;
            case '}':
                $this->treeLevel--;
                assert('$this->treeLevel >= 0');
                if (isset($this->levelListeners[$this->treeLevel]) === true) {
                    $this->levelListeners[$this->treeLevel]->setFinish($key);
                    unset($this->levelListeners[$this->treeLevel]);
                    $this->layoutTree->pop();
                }
                break;
        }
    }
}
