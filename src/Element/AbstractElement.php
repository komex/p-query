<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Element;

/**
 * Class AbstractElement
 *
 * @package PQuery\Element
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractElement
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $position;
    /**
     * @var AbstractElement
     */
    protected $parentElement;
    /**
     * @var int
     */
    protected $level;
    /**
     * @var int
     */
    protected $finish;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
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
     * @return AbstractElement
     */
    public function getParentElement()
    {
        return $this->parentElement;
    }

    /**
     * @param AbstractElement $parentElement
     */
    public function setParentElement(AbstractElement $parentElement = null)
    {
        $this->parentElement = $parentElement;
    }

    /**
     * @param \ArrayIterator $stream
     *
     * @return string
     */
    protected function extractName(\ArrayIterator $stream)
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
    protected function getToken($token)
    {
        if (is_array($token) === true) {
            list($code, $value) = $token;
        } else {
            $code = null;
            $value = $token;
        }

        return [$code, $value];
    }
}
