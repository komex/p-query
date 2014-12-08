<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

use PQuery\Element\AbstractElement;
use PQuery\Element\ClassElement;
use PQuery\Element\FunctionElement;
use PQuery\Element\NamespaceElement;

/**
 * Class Parser
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Parser
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
     * @var AbstractElement[]
     */
    private $levelSubscribers = [];

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
    }

    /**
     * @return AbstractElement[]|\ArrayIterator
     */
    public function getElements()
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
                $this->registerElement(new FunctionElement($stream));
                break;
            case T_CLASS:
                $this->registerElement(new ClassElement($stream));
                break;
            case T_NAMESPACE:
                $this->registerElement(new NamespaceElement($stream));
                break;
        }
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
                if (isset($this->levelSubscribers[$this->treeLevel]) === true) {
                    $this->levelSubscribers[$this->treeLevel]->setFinish($key);
                    unset($this->levelSubscribers[$this->treeLevel]);
                    $this->layoutTree->pop();
                }
                break;
        }
    }

    /**
     * @param AbstractElement $element
     */
    private function registerElement(AbstractElement $element)
    {
        $element->setLevel($this->treeLevel);
        if ($this->layoutTree->isEmpty() === false) {
            $element->setParentElement($this->layoutTree->top());
        }
        array_push($this->elements, $element);
        $this->layoutTree->push($element);
        $this->levelSubscribers[$this->treeLevel] = $element;
    }
}
