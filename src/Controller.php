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
    private $keyWords = [];
    /**
     * @var ProcessorInterface[][]
     */
    private $stopWords = [];

    /**
     * @param ProcessorInterface $processor
     *
     * @return $this
     */
    public function bind(ProcessorInterface $processor)
    {
        if (empty($this->keyWords[$processor->getKeyWord()]) === false) {
            $this->unbind($this->keyWords[$processor->getKeyWord()]);
        }
        $processor->setController($this);
        $this->keyWords[$processor->getKeyWord()] = $processor;
        foreach ($processor->getStopWords() as $word) {
            if (empty($this->stopWords[$word]) === true) {
                $this->stopWords[$word] = [];
            }
            if (in_array($processor, $this->stopWords[$word], true) === false) {
                array_push($this->stopWords[$word], $processor);
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
        unset($this->keyWords[$processor->getKeyWord()]);
        foreach ($processor->getStopWords() as $word) {
            if (empty($this->stopWords[$word]) === false) {
                $position = array_search($processor, $this->stopWords[$word], true);
                if ($position !== false) {
                    if (count($this->stopWords[$word]) === 1) {
                        unset($this->stopWords[$word]);
                    } else {
                        unset($this->stopWords[$word][$position]);
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
        $stack = new \SplStack();
        foreach ($stream as $token) {
            if (is_array($token) === true) {
                list($code, $value) = $token;
                if (isset($this->keyWords[$code]) === true) {
                    $content .= $this->keyWords[$code]->takeControl($stream, $stack);
                    if ($this->keyWords[$code]->trackLevel() === true) {
                        if (empty($levelTracks[$level]) === true) {
                            $levelTracks[$level] = [];
                        }
                        array_push($levelTracks[$level], [$this->keyWords[$code], 'onSameLevel']);
                    }
                    assert('$stack->isEmpty() === true');
                } elseif (isset($this->stopWords[$code]) === true) {
                    $stack->push($code);
                } else {
                    $content .= $value;
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
