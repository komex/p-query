<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;

/**
 * Class ClassParser
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ClassParser implements ParserInterface
{
    /**
     * @var callable
     */
    private $handler;
    /**
     * @var array
     */
    private $attributes = [];
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $extends = '';
    /**
     * @var array
     */
    private $implements = [];
    /**
     * @var string
     */
    private $tmpImplement = '';

    /**
     * ClassParser constructor.
     */
    public function __construct()
    {
        $this->handler = [$this, 'init'];
    }

    /**
     * @param array|string $token
     *
     * @return int
     */
    public function init($token)
    {
        if (is_array($token) === false) {
            return self::TOKEN_UNKNOWN;
        }
        $token = $token[0];
        if ($token === T_CLASS) {
            $this->handler = [$this, 'name'];
        } elseif ($token === T_ABSTRACT || $token === T_FINAL) {
            $this->attributes[] = $token;
            $this->handler = [$this, 'attributes'];
        } else {
            return self::TOKEN_UNKNOWN;
        }

        return self::TOKEN_ACCEPTED;
    }

    /**
     * @param array $token
     *
     * @return int
     */
    public function attributes(array $token)
    {
        $this->handler = [$this, 'name'];

        return $token[0] === T_CLASS ? self::TOKEN_ACCEPTED : self::TOKEN_UNKNOWN;
    }

    /**
     * @param array $token
     *
     * @return int
     */
    public function name(array $token)
    {
        if ($token[0] === T_STRING) {
            $this->className = $token[1];
            $this->handler = [$this, 'hierarchy'];

            return self::TOKEN_ACCEPTED;
        } else {
            return self::TOKEN_UNKNOWN;
        }
    }

    /**
     * @param array|string $token
     *
     * @return int
     */
    public function hierarchy($token)
    {
        if (is_array($token) === true) {
            $token = $token[0];
            if ($token === T_EXTENDS) {
                $this->handler = [$this, 'extend'];

                return self::TOKEN_ACCEPTED;
            } elseif ($token === T_IMPLEMENTS) {
                $this->handler = [$this, 'implement'];

                return self::TOKEN_ACCEPTED;
            }
        } elseif ($token === '{') {
            $this->handler = [$this, 'levelUp'];
        }

        return self::TOKEN_UNKNOWN;
    }

    /**
     * @param array|string $token
     *
     * @return int
     */
    public function extend($token)
    {
        if (is_array($token)) {
            list($token, $value) = $token;
            if ($token === T_STRING || $token === T_NS_SEPARATOR) {
                $this->extends .= $value;
            } elseif ($token === T_IMPLEMENTS) {
                $this->handler = [$this, 'implement'];
            } else {
                return self::TOKEN_UNKNOWN;
            }
        } elseif ($token === '{') {
            $this->handler = [$this, 'levelUp'];
        } else {
            return self::TOKEN_UNKNOWN;
        }

        return self::TOKEN_ACCEPTED;
    }

    /**
     * @param array|string $token
     *
     * @return int
     */
    public function implement($token)
    {
        if (is_array($token)) {
            list($token, $value) = $token;
            if ($token === T_STRING || $token === T_NS_SEPARATOR) {
                $this->tmpImplement .= $value;
            } else {
                return self::TOKEN_UNKNOWN;
            }
        } elseif ($token === '{') {
            $this->implements[] = $this->tmpImplement;
            $this->tmpImplement = '';
            $this->handler = [$this, 'levelUp'];
        } elseif ($token === ',') {
            $this->implements[] = $this->tmpImplement;
            $this->tmpImplement = '';
        } else {
            return self::TOKEN_UNKNOWN;
        }

        return self::TOKEN_ACCEPTED;
    }

    /**
     * @return int
     */
    public function levelUp()
    {
        return self::TOKEN_UNKNOWN;
    }

    /**
     * @param array|string $token
     *
     * @return int
     */
    public function parse($token)
    {
        return call_user_func($this->handler, $token);
    }
}
