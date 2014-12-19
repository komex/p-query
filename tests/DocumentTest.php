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
//        self::$stream = new Stream(
//            token_get_all(file_get_contents('/home/komex/Projects/communal/app/cache/dev/classes.php'))
//        );
    }

    /**
     * Test namespaces
     */
    public function testFunctions()
    {
        $document = new Document(self::$stream);
        $namespaces = $document->getNamespaces();
        foreach ($namespaces as $namespace) {
            foreach ($namespace->classes() as $class) {
                foreach ($class->functions() as $function) {
                    $a = $namespace->getName() . '\\' . $class->getName() . '::' . $function->getName() . '()';
                }
            }
        }
    }
}
