<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Test;

use Perk\Controller;
use Perk\Document;
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
        // @todo Result is not VALID.
        $content = $controller->applyChanges(self::$stream);
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
