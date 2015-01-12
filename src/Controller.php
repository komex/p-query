<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;

use Perk\Parser\Stream;
use Perk\Processor\ProcessorInterface;

/**
 * Class Controller
 *
 * @package Perk\Controller
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Controller
{
    /**
     * @var ProcessorInterface[]
     */
    private $keywords = [];
    /**
     * @var ProcessorInterface[][]
     */
    private $attributes = [];
    /**
     * @var ProcessorInterface
     */
    private $layout;

    /**
     * @return ProcessorInterface
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param ProcessorInterface|null $layout
     */
    public function setLayout(ProcessorInterface $layout = null)
    {
        $this->layout = $layout;
    }

    /**
     * @param ProcessorInterface $processor
     *
     * @return $this
     */
    public function bind(ProcessorInterface $processor)
    {
        if (empty($this->keywords[$processor->getKeyWord()]) === false) {
            $this->unbind($this->keywords[$processor->getKeyWord()]);
        }
        $processor->setController($this);
        $this->keywords[$processor->getKeyWord()] = $processor;
        foreach ($processor->getStopWords() as $word) {
            if (empty($this->attributes[$word]) === true) {
                $this->attributes[$word] = [];
            }
            if (in_array($processor, $this->attributes[$word], true) === false) {
                array_push($this->attributes[$word], $processor);
            }
        }

        return $this;
    }

    /**
     * @param ProcessorInterface $processor
     *
     * @return $this
     */
    public function unbind(ProcessorInterface $processor)
    {
        unset($this->keywords[$processor->getKeyWord()]);
        foreach ($processor->getStopWords() as $word) {
            if (empty($this->attributes[$word]) === false) {
                $position = array_search($processor, $this->attributes[$word], true);
                if ($position !== false) {
                    if (count($this->attributes[$word]) === 1) {
                        unset($this->attributes[$word]);
                    } else {
                        unset($this->attributes[$word][$position]);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param Stream $stream
     *
     * @return string
     */
    public function applyChanges(Stream $stream)
    {
        $content = '';
        $level = 0;
        $levelTracks = [];
        $queue = new \SplQueue();
        $queue->setIteratorMode(\SplStack::IT_MODE_DELETE);
        /** @var ProcessorInterface[]|null $expected */
        $expected = null;
        foreach ($stream as $token) {
            if (is_array($token) === true) {
                list($code, $value) = $token;
                if (isset($this->keywords[$code]) === true) {
                    $content .= $this->keywords[$code]->takeControl($stream, $queue);
                    if ($this->keywords[$code]->trackLevel() === true) {
                        if (empty($levelTracks[$level]) === true) {
                            $levelTracks[$level] = [];
                        }
                        array_push($levelTracks[$level], [$this->keywords[$code], 'onSameLevel']);
                    }
                    assert('$queue->isEmpty() === true');
                } elseif ($expected === null) {
                    if (isset($this->attributes[$code]) === true) {
                        $queue->enqueue($token);
                        $expected = $this->attributes[$code];
                    } else {
                        $content .= $value;
                    }
                } elseif ($code === T_WHITESPACE) {
                    $queue->enqueue($token);
                } else {
                    $allowed = false;
                    foreach ($expected as $processor) {
                        if (in_array($code, $processor->getStopWords()) === true) {
                            $allowed = true;
                            break;
                        }
                    }
                    $queue->enqueue($token);
                    if ($allowed === false) {
                        foreach ($queue as $attribute) {
                            $content .= $attribute[1];
                        }
                        $expected = null;
                    }
                }
            } else {
                if ($token === '{') {
                    $level++;
                } elseif ($token === '}') {
                    $level--;
                    if (isset($levelTracks[$level]) === true) {
                        foreach ($levelTracks[$level] as $callback) {
                            if (is_callable($callback)) {
                                $content .= call_user_func($callback, $stream);
                            }
                        }
                        unset($levelTracks[$level]);
                    }
                }
                $content .= $token;
            }
        }

        return $content;
    }
}
