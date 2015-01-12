<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Test;

use Perk\Controller;
use Perk\Parser\Stream;
use Perk\Processor\ClassProcessor;
use Perk\Processor\FunctionProcessor;
use Perk\Processor\NamespaceProcessor;

/**
 * Class DocumentTest
 *
 * @package Test\Perk
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Stream
     */
    private static $stream;

    /**
     * Init test
     */
    public static function setUpBeforeClass()
    {
        self::$stream = new Stream(file_get_contents(__FILE__));
    }

    public function testController()
    {
        $controller = new Controller();
        $controller->bind(new NamespaceProcessor());
        $controller->bind(new ClassProcessor());
        $controller->bind(new FunctionProcessor());
        $content = $controller->applyChanges(self::$stream);
    }
}
