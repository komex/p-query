<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\PQuery;

use PQuery\Document;

/**
 * Class DocumentTest
 *
 * @package Test\PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ArrayIterator
     */
    private static $stream;

    /**
     * Init test
     */
    public static function setUpBeforeClass()
    {
        self::$stream = new \ArrayIterator(token_get_all(file_get_contents(__FILE__)));
    }

    /**
     * Test namespaces
     */
    public function testFunctions()
    {
        $document = new Document(self::$stream);
        $functions = $document->getFunctions();
        foreach ($functions as $function) {
            $this->assertTrue($function->isPublic());
            $this->assertFalse($function->isAbstract());
        }
    }

    public function testClass()
    {
        $document = new Document(self::$stream);
        $classes = $document->getClasses();
        foreach ($classes as $class) {
            $this->assertFalse($class->isAbstract());
            $this->assertFalse($class->isFinal());
        }
    }
}
