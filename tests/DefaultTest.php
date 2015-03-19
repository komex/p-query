<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Test;

use Perk\Lexer;
use Perk\MethodParser;

/**
 * Class DocumentTest
 *
 * @package Test\Perk
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class DefaultTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $tokens = token_get_all(file_get_contents('vendor/phpunit/phpunit/src/Framework/TestSuite.php'));
//        $tokens = token_get_all(file_get_contents('vendor/phpmd/phpmd/src/main/php/PHPMD/AbstractNode.php'));
        $lexer = new Lexer();
        $lexer->addParser(new MethodParser());
        $lexer->process($tokens);
        $a = $lexer->getContent();
    }
}
