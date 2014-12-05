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
     * Test count of PHP places.
     */
    public function testNamespaces()
    {
        $document = new Document(self::$stream);
        $namespaces = $document->getNamespaces();
        $this->assertCount(1, $namespaces);
        $this->assertSame(__NAMESPACE__, $namespaces[0]['name']);
    }

    /**
     *
     */
    public function testClasses()
    {
        $document = new Document(self::$stream);
        $classes = $document->getClasses();
        $this->assertCount(1, $classes);
        $this->assertSame(__NAMESPACE__, $classes[0]['namespace']);
        $this->assertSame(__CLASS__, $classes[0]['namespace'] . '\\' . $classes[0]['name']);
    }

    /**
     *
     */
    public function testMethods()
    {
        $document = new Document(self::$stream);
        $methods = $document->getMethods();
        $this->assertCount(4, $methods);
    }
}
