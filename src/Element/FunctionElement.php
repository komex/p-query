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
     * @var bool
     */
    private $attrLoaded = false;

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->name === null) {
            $this->stream->seek($this->position + 2);
            $this->name = $this->extractName($this->stream);
        }

        return $this->name;
    }

    /**
     * @return bool
     */
    public function isStatic()
    {
        return $this->loadAttributes()->attribute === T_STATIC;
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->loadAttributes()->attribute === T_ABSTRACT;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->loadAttributes()->visibility === T_PUBLIC;
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->loadAttributes()->visibility === T_PROTECTED;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->loadAttributes()->visibility === T_PRIVATE;
    }

    /**
     * @return $this
     */
    private function loadAttributes()
    {
        if ($this->attrLoaded === false) {
            $this->static = false;
            $this->abstract = false;
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
            $this->attrLoaded = true;
        }

        return $this;
    }
}
