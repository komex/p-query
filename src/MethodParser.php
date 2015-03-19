<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;

/**
 * Class MethodParser
 *
 * @package Perk
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class MethodParser implements ParserInterface
{
    /**
     * @var array
     */
    private $tokensMap = [];
    /**
     * @var array
     */
    private $valuesMap = [];
    /**
     * @var callable
     */
    private $defaultHandler;
    /**
     * @var array
     */
    private $attributes = [];
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $level = 0;

    /**
     * MethodParser constructor.
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @param $token
     *
     * @return int
     */
    public function parse($token)
    {
        $handler = $this->defaultHandler;
        if (is_array($token) === true) {
            if (isset($this->tokensMap[$token[0]]) === true) {
                $handler = $this->tokensMap[$token[0]];
            }
        } else {
            if (isset($this->valuesMap[$token]) === true) {
                $handler = $this->valuesMap[$token];
            }
        }
        if (is_callable($handler) === true) {
            call_user_func($handler, $token);

            return self::TOKEN_ACCEPTED;
        } else {
            $this->reset();

            return self::TOKEN_UNKNOWN;
        }
    }

    /**
     * @param array $token
     */
    public function attributes(array $token)
    {
        list($attribute) = $token;
        $this->attributes[] = $attribute;
        unset($this->tokensMap[$attribute]);
        if (in_array($attribute, [T_PUBLIC, T_PROTECTED, T_PRIVATE], true) === true) {
            unset($this->tokensMap[T_PUBLIC]);
            unset($this->tokensMap[T_PROTECTED]);
            unset($this->tokensMap[T_PRIVATE]);
            if ($attribute === T_PRIVATE) {
                unset($this->tokensMap[T_ABSTRACT]);
            }
        } elseif ($attribute === T_ABSTRACT) {
            unset($this->tokensMap[T_PRIVATE]);
            unset($this->tokensMap[T_FINAL]);
        } elseif ($attribute === T_FINAL) {
            unset($this->tokensMap[T_ABSTRACT]);
        }
    }

    /**
     * @param array $token
     */
    public function name(array $token)
    {
        $this->name = $token[1];
        unset($this->tokensMap[T_STRING]);
    }

    /**
     * Found function key word. Update maps.
     */
    public function keyWord()
    {
        $this->tokensMap = [
            T_STRING => [$this, 'name'],
        ];
        $this->valuesMap = [
            '(' => [$this, 'startArgumentsBlock'],
        ];
    }

    /**
     * Set default handler for arguments block.
     */
    public function startArgumentsBlock()
    {
        $this->tokensMap = [];
        $this->valuesMap = [];
        $this->level = 0;
        $this->defaultHandler = [$this, 'arguments'];
    }

    /**
     * @param array|int $token
     */
    public function arguments($token)
    {
        if (is_array($token) === false) {
            switch ($token) {
                case '(':
                    $this->level++;
                    break;
                case ')':
                    if ($this->level === 0) {
                        $this->valuesMap = [
                            '{' => [$this, 'startContent'],
                        ];
                        $this->defaultHandler = null;
                    } else {
                        $this->level--;
                    }
                    break;
            }
        }
    }

    /**
     * Found start content token
     */
    public function startContent()
    {
        $this->valuesMap = [];
        $this->tokensMap = [];
        $this->level = 0;
        $this->defaultHandler = [$this, 'content'];
    }

    /**
     * Reset state.
     */
    private function reset()
    {
        $this->tokensMap = [
            T_PUBLIC => [$this, 'attributes'],
            T_PRIVATE => [$this, 'attributes'],
            T_PROTECTED => [$this, 'attributes'],
            T_STATIC => [$this, 'attributes'],
            T_ABSTRACT => [$this, 'attributes'],
            T_FINAL => [$this, 'attributes'],
            T_FUNCTION => [$this, 'keyWord'],
        ];
        $this->valuesMap = [];
        $this->attributes = [];
        $this->name = null;
        $this->level = 0;
    }
}
