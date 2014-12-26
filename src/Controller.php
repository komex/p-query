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
    private $stopWords = [];

    /**
     * @param int $keyword
     *
     * @return bool
     */
    public function hasProcessorFor($keyword)
    {
        return isset($this->keywords[$keyword]);
    }

    /**
     * @param int $keyword
     *
     * @return null|ProcessorInterface
     */
    public function getProcessorFor($keyword)
    {
        if ($this->hasProcessorFor($keyword)) {
            return $this->keywords[$keyword];
        } else {
            return null;
        }
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
        unset($this->keywords[$processor->getKeyWord()]);
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
                    if (isset($this->stopWords[$code]) === true) {
                        $queue->enqueue($token);
                        $expected = $this->stopWords[$code];
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
