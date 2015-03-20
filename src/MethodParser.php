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
     * @var Lexer
     */
    private $lexer;
    /**
     * @var callable[]
     */
    private $tokensMap = [];
    /**
     * @var callable[]
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
     * @var callable
     */
    private $onParsed;

    /**
     * MethodParser constructor.
     *
     * @param callable $onParsed
     */
    public function __construct(callable $onParsed = null)
    {
        $this->reset();
        $this->onParsed = $onParsed;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'method';
    }

    /**
     * @param Lexer $lexer
     */
    public function setLexer(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * @param array|string $token
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
        if ($handler !== null) {
            return call_user_func($handler, $token);
        } else {
            $this->reset();

            return self::ABSTAIN;
        }
    }

    /**
     * Reset state.
     */
    public function reset()
    {
        $this->tokensMap = array_fill_keys(
            [T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_ABSTRACT, T_FINAL],
            [$this, 'attributes']
        );
        $this->tokensMap[T_FUNCTION] = [$this, 'keyWord'];
        $this->valuesMap = [];
        $this->defaultHandler = null;
        $this->attributes = [];
        $this->name = null;
        $this->level = 0;
    }

    /**
     * @param array $token
     *
     * @return int
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

        return self::ACCEPTED;
    }

    /**
     * @param array $token
     *
     * @return int
     */
    public function name(array $token)
    {
        $this->name = $token[1];
        unset($this->tokensMap[T_STRING]);

        return self::ACCEPTED;
    }

    /**
     * Found function key word. Update maps.
     *
     * @return int
     */
    public function keyWord()
    {
        $this->tokensMap = [T_STRING => [$this, 'name']];
        $this->valuesMap = ['(' => [$this, 'startArgumentsBlock']];

        return self::ACCEPTED;
    }

    /**
     * Set default handler for arguments block.
     *
     * @return int
     */
    public function startArgumentsBlock()
    {
        $this->tokensMap = [];
        $this->valuesMap = [];
        $this->level = 0;
        $this->defaultHandler = [$this, 'arguments'];

        return self::ACCEPTED;
    }

    /**
     * @param array|string $token
     *
     * @return int
     */
    public function arguments($token)
    {
        if ($token === '(') {
            $this->level++;
        } elseif ($token === ')') {
            if ($this->level === 0) {
                if ($this->onParsed !== null) {
                    $state = call_user_func($this->onParsed, $this->attributes, $this->name, $this->lexer);
                    if ($state === self::PARSED) {
                        return $state;
                    }
                }
                $this->reset();

                return self::ABSTAIN;
            } else {
                $this->level--;
            }
        }

        return self::ACCEPTED;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '<< function ' . $this->name . ' declaration >>';
    }
}
