<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Element;

/**
 * Class FunctionElement
 *
 * @package PQuery\Element
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class FunctionElement extends AbstractElement
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $attribute;
    /**
     * @var int
     */
    private $visibility;
    /**
     * @var string
     */
    private $content;

    /**
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        $this->stream = $stream;
        $this->position = $stream->key();
        $this->loadAttributes();
        $this->loadName();
        $this->loadContent();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->attribute === T_STATIC;
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->attribute === T_ABSTRACT;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->visibility === T_PUBLIC;
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
     * Load content of function.
     */
    private function loadContent()
    {
        $content = '';
        $this->stream->seek($this->position + 1);
        $isBracketOpen = false;
        while ($this->stream->valid() && $this->stream->key() < $this->finish) {
            list(, $value) = $this->getToken($this->stream->current());
            if ($isBracketOpen === true) {
                $content .= $value;
            } elseif ($value === '{') {
                $isBracketOpen = true;
            }
            $this->stream->next();
        }
        $this->content = $content;
    }

    /**
     * Load name of function.
     */
    private function loadName()
    {
        $this->stream->seek($this->position + 2);
        $this->name = $this->extractName($this->stream);
    }

    /**
     * Load attributes of function.
     */
    private function loadAttributes()
    {
        $offset = ($this->position - 1);
        $this->stream->seek($offset);
        while ($this->stream->valid()) {
            list($code) = $this->getToken($this->stream->current());
            switch ($code) {
                case T_PUBLIC:
                case T_PROTECTED:
                case T_PRIVATE:
                    $this->visibility = $code;
                    break;
                case T_STATIC:
                case T_ABSTRACT:
                    $this->attribute = $code;
                    break;
                case T_WHITESPACE:
                    break;
                default:
                    break 2;
            }
            $this->stream->seek(--$offset);
        }
    }
}
