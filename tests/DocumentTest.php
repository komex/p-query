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
    public function testNamespaces()
    {
        $document = new Document(self::$stream);
        $namespaces = $document->getNamespaces();
        $this->assertCount(1, $namespaces);
        foreach ($namespaces as $namespace) {
            $this->assertSame(__NAMESPACE__, $namespace->getName());
        }
    }

    /**
     * Test classes
     */
    public function testClasses()
    {
        $document = new Document(self::$stream);
        $classes = $document->getClasses();
        $this->assertCount(1, $classes);
        foreach ($classes as $class) {
            $this->assertSame(__CLASS__, $class->getParentElement()->getName() . '\\' . $class->getName());
        }
    }
}
