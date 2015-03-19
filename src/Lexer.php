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
}
