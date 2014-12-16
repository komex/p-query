<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

use PQuery\Block\AbstractBlock;
use PQuery\Block\NamespaceBlock;

/**
 * Class Parser
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Parser implements \IteratorAggregate
{
    /**
     * @var AbstractBlock[]
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
     * @var AbstractBlockParser
     */
    private $namespaceBlockParser;
    /**
     * @var AbstractBlockParser
     */
    private $classBlockParser;
    /**
     * @var AbstractBlockParser
     */
    private $functionBlockParser;

    /**
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->namespaceBlockParser = new NamespaceBlockParser();
        $this->classBlockParser = new ClassBlockParser();
        $this->functionBlockParser = new FunctionBlockParser();
        $stream->rewind();
        $this->layoutTree = new \SplStack;
        while ($stream->valid() === true) {
            list($code, $value) = $stream->current();
            if ($code === null) {
                $this->levelControl($stream->key(), $value);
            } else {
                $this->elementsRegister($stream, $code);
            }
//            $token = $stream->current();
//            if (is_array($token) === true) {
//                $this->elementsRegister($stream, $token[0]);
//            } else {
//                $this->levelControl($stream->key(), $token);
//            }
            $stream->next();
        }
        $this->currentNamespace->setFinish($stream->count());
    }

    /**
     * Retrieve an external iterator
     *
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return AbstractBlock[]|\ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param Stream $stream
     * @param int $token
     */
    private function elementsRegister(Stream $stream, $token)
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
     * @param Stream $stream
     */
    private function registerFunction(Stream $stream)
    {
        $block = $this->functionBlockParser->extract($stream);
        $this->registerBlockFinishListener($block);
        $this->registerBlock($block);
    }

    /**
     * @param Stream $stream
     */
    private function registerClass(Stream $stream)
    {
        $block = $this->classBlockParser->extract($stream);
        $this->registerBlockFinishListener($block);
        $this->registerBlock($block);
    }

    /**
     * @param Stream $stream
     */
    private function registerNamespaceBlock(Stream $stream)
    {
        $block = $this->namespaceBlockParser->extract($stream);
        if ($block->isLimited() === true) {
            $this->registerBlockFinishListener($block);
            $this->levelControl($stream->key(), $stream->current());
        }
        if ($this->currentNamespace !== null) {
            $this->currentNamespace->setFinish($block->getPosition());
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
