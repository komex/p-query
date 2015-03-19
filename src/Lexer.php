<?php
/**
 * This file is a part of perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk;

/**
 * Class Lexer
 *
 * @package Perk
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Lexer
{
    /**
     * @var string
     */
    private $content = '';

    /**
     * @var ParserInterface[]
     */
    private $parsers = [];
    /**
     * @var int
     */
    private $level = 0;
    /**
     * @var callable
     */
    private $levelUpHandler;
    /**
     * @var callable[]
     */
    private $levelDownHandlers = [];

    /**
     * @param ParserInterface $parser
     */
    public function addParser(ParserInterface $parser)
    {
        $this->parsers[$parser->getName()] = $parser;
        $parser->setLexer($this);
    }

    /**
     * @param string $name
     *
     * @return ParserInterface
     */
    public function getParser($name)
    {
        return $this->parsers[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParser($name)
    {
        return isset($this->parsers[$name]);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param callable $levelUp
     * @param callable $levelDown
     */
    public function registerLevelHandlers(callable $levelUp = null, callable $levelDown = null)
    {
        if ($levelUp !== null) {
            $this->levelUpHandler = $levelUp;
        }
        if ($levelDown !== null) {
            $this->levelDownHandlers[$this->level] = $levelDown;
        }
    }

    /**
     * @param array $tokens
     */
    public function process(array $tokens)
    {
        $accepted = false;
        $cachedContent = '';
        foreach ($tokens as $token) {
            if (is_array($token) === true) {
                $chunk = $token[1];
                if ($token[0] === T_WHITESPACE) {
                    if ($accepted === true) {
                        $this->content .= $chunk;
                    } else {
                        $cachedContent .= $chunk;
                    }
                    continue;
                }
            } else {
                $this->checkLevelChange($token);
                $chunk = $token;
            }
            foreach ($this->parsers as $parser) {
                if ($parser->parse($token) === ParserInterface::ABSTAIN) {
                    $accepted = false;
                    $this->content .= $chunk;
                    $cachedContent = '';
                } else {
                    $accepted = true;
                    $cachedContent .= $chunk;
                }
            }
        }
    }

    /**
     * @param string $token
     */
    private function checkLevelChange($token)
    {
        if ($this->levelUpHandler !== null || empty($this->levelDownHandlers) === false) {
            if ($token === '{') {
                $this->level++;
                if ($this->levelUpHandler !== null) {
                    call_user_func($this->levelUpHandler);
                    $this->levelUpHandler = null;
                }
            } elseif ($token === '}') {
                $this->level--;
                if (isset($this->levelDownHandlers[$this->level]) === true) {
                    call_user_func($this->levelDownHandlers[$this->level]);
                    unset($this->levelDownHandlers[$this->level]);
                }
            }
        }
    }
}
