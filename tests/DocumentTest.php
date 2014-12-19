<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Test\PQuery;

use PQuery\Document;
use PQuery\Parser\Stream;

/**
 * Class DocumentTest
 *
 * @package Test\PQuery
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
        self::$stream = new Stream(token_get_all(file_get_contents(__FILE__)));
    }

    /**
     * Test getNamespace
     */
    public function testNamespace()
    {
        $document = new Document(self::$stream);
        $namespaces = $document->getNamespaces();
        $this->assertCount(1, $namespaces);
        foreach ($namespaces as $namespace) {
            $this->assertSame(__NAMESPACE__, $namespace->getName());
            $classes = $namespace->getClasses();
            $this->assertCount(1, $classes);
            foreach ($classes as $class) {
                $this->assertSame(__CLASS__, $namespace->getName() . '\\' . $class->getName());
                $functions = $class->getFunctions();
                $this->assertCount(3, $functions);
                foreach ($functions as $function) {
                    $this->assertSame('setUpBeforeClass', $function->getName());
                    $this->assertTrue($function->isStatic());
                    $this->assertTrue($function->isPublic());
                    $this->assertFalse($function->isProtected());
                    $this->assertFalse($function->isPrivate());
                    $this->assertFalse($function->isAbstract());
                    break;
                }
            }
        }
    }

    /**
     * Test save method
     */
    public function testSave()
    {
        $document = new Document(self::$stream);
        $this->assertSame(file_get_contents(__FILE__), $document->save());
    }
}
