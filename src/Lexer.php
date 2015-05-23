<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Komex\Perk;

/**
 * Class Lexer
 *
 * @package Komex\Perk
 * @author Andrey Kolchenko <andrey@kolhenko.me>
 */
class Lexer
{
    /**
     * @var ParserInterface[]
     */
    private $parsers = [];
    /**
     * @var array
     */
    private $tokensMap = [];
    /**
     * @var array
     */
    private $valuesMap = [];
    /**
     * @var ParserInterface[]
     */
    private $matcherParsers;

    /**
     * @param array $tokens
     * @param ParserInterface[] $parsers
     *
     * @return string
     */
    public function parse(array $tokens, array $parsers)
    {
        $content = '';
        $this->reset();
        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
        foreach ($tokens as $token) {
            if (is_array($token) === true) {
                $value = $this->parseToken($token);
            } else {
                $value = $this->parseValue($token);
            }
            if ($value !== null) {
                $content .= $value;
            }
        }

        return $content;
    }

    /**
     * Reset Lexer
     */
    private function reset()
    {
        $this->parsers = [];
        $this->tokensMap = [];
        $this->valuesMap = [];
        $this->matcherParsers = [];
    }

    /**
     * @param ParserInterface $parser
     */
    private function addParser(ParserInterface $parser)
    {
        if (empty($this->parsers[$parser->getName()]) === true) {
            $this->parsers[$parser->getName()] = $parser;
            $this->tokensMap = array_merge($this->tokensMap, $this->extractTokensMap($parser));
            $this->valuesMap = array_merge($this->valuesMap, $this->extractValuesMap($parser));
        }
    }

    /**
     * @param array $token
     *
     * @return null|string
     */
    private function parseToken(array $token)
    {
        list($code, $value) = $token;
        if ($code === T_WHITESPACE) {
            return (empty($this->matcherParsers) === true) ? $value : null;
        }
        if (isset($this->tokensMap[$code]) === true) {
            $matches = [];
            foreach ($this->tokensMap[$code] as $definition) {
                /** @var callable $handler */
                /** @var ParserInterface $parser */
                list($handler, $parser) = $definition;
                $status = call_user_func($handler, $token);
                switch ($status) {
                    case ParserInterface::MATCH:
                        $matches[$parser->getName()] = $parser;
                        unset($this->matcherParsers[$parser->getName()]);
                        break;
                    case ParserInterface::DONE:
                        // @todo Continue here
                        $content = $parser->onMatch();
                        break;
                }
            }
        }

        return null;
    }

    /**
     * @param string $value
     *
     * @return null|string
     */
    private function parseValue($value)
    {
        return $value;
    }

    /**
     * @param ParserInterface $parser
     *
     * @return array
     */
    private function extractTokensMap(ParserInterface $parser)
    {
        $map = [];
        foreach ($parser->getTokensMap() as $token => $handler) {
            if (is_callable($handler) === false) {
                throw new \RuntimeException(
                    sprintf(
                        'Parser "%s" must return callable handler for token %d (%s)',
                        $parser->getName(),
                        $token,
                        token_name($token)
                    )
                );
            }
            if (isset($map[$token]) === false) {
                $map[$token] = [];
            }
            $map[$token] = [$handler, $parser];
        }

        return $map;
    }

    /**
     * @param ParserInterface $parser
     *
     * @return array
     */
    private function extractValuesMap(ParserInterface $parser)
    {
        $map = [];
        foreach ($parser->getValuesMap() as $value => $handler) {
            if (is_callable($handler) === false) {
                throw new \RuntimeException(
                    sprintf(
                        'Parser "%s" must return callable handler for value "%s"',
                        $parser->getName(),
                        $value
                    )
                );
            }
            if (isset($map[$value]) === false) {
                $map[$value] = [];
            }
            $map[$value] = [$handler, $parser];
        }

        return $map;
    }
}
