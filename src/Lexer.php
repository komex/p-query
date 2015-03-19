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
     * @var ClassParser
     */
    private $parser;
    /**
     * @var string
     */
    private $content = '';
    /**
     * @var string
     */
    private $cachedContent = '';

    /**
     * @param ParserInterface $parser
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
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
        foreach ($tokens as $token) {
            if (is_array($token) === true) {
                $chunk = $token[1];
                if ($token[0] === T_WHITESPACE) {
                    if ($accepted === true) {
                        $this->content .= $chunk;
                    } else {
                        $this->cachedContent .= $chunk;
                    }
                    continue;
                }
            } else {
                $chunk = $token;
            }
            if ($this->parser->parse($token) === ParserInterface::TOKEN_UNKNOWN) {
                $accepted = false;
                $this->content .= $chunk;
                $this->cachedContent = '';
            } else {
                $accepted = true;
                $this->cachedContent .= $chunk;
            }
        }
    }
}
