<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

/**
 * Class Document
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Document
{
    /**
     * @var \ArrayIterator
     */
    private $stream;
    /**
     * @var array
     */
    private $namespaces = [];
    /**
     * @var array
     */
    private $classes = [];
    /**
     * @var array
     */
    private $methods = [];

    /**
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        $this->initDocument($stream);
        $this->stream = $stream;
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param \ArrayIterator $stream
     */
    private function initDocument(\ArrayIterator $stream)
    {
        $stream->rewind();
        while ($stream->valid() === true) {
            if (is_array($stream->current()) === true) {
                list($code) = $this->getToken($stream->current());
                switch ($code) {
                    case T_FUNCTION:
                        $this->registerMethod($stream);
                        break;
                    case T_CLASS:
                        $this->registerClass($stream);
                        break;
                    case T_NAMESPACE:
                        $this->registerNamespace($stream);
                        break;
                }
            }
            $stream->next();
        }
    }

    /**
     * @param \ArrayIterator $stream
     */
    private function registerMethod(\ArrayIterator $stream)
    {
        assert('$this->getToken($stream->current())[0] === T_FUNCTION');
        $position = $stream->key();
        $currentNamespace = empty($this->namespaces) === true ? '\\' : $this->namespaces[0]['name'];
        $stream->seek($position + 1);

        $name = $this->extractName($stream);
        if (empty($name) === true || empty($this->classes) === true) {
            return;
        }
        $methodDefinition = [
            'position' => $position,
            'namespace' => $currentNamespace,
            'class' => $this->classes[0]['name'],
            'name' => $name,
        ];
        array_unshift($this->methods, $methodDefinition);
    }

    /**
     * @param \ArrayIterator $stream
     */
    private function registerClass(\ArrayIterator $stream)
    {
        assert('$this->getToken($stream->current())[0] === T_CLASS');
        $position = $stream->key();
        $stream->seek($position + 1);
        $currentNamespace = empty($this->namespaces) === true ? '\\' : $this->namespaces[0]['name'];
        $definition = [
            'position' => $position,
            'namespace' => $currentNamespace,
            'name' => $this->extractName($stream),
        ];
        array_unshift($this->classes, $definition);
    }

    /**
     * @param \ArrayIterator $stream
     */
    private function registerNamespace(\ArrayIterator $stream)
    {
        assert('$this->getToken($stream->current())[0] === T_NAMESPACE');
        $position = $stream->key();
        $stream->seek($position + 2);
        array_unshift($this->namespaces, ['position' => $position, 'name' => $this->extractName($stream)]);
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
}
