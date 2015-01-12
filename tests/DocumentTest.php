<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Test;

use Perk\Document;
use Perk\Parser\Stream;
use Perk\Processor\ClassProcessor;

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
        $document = new Document();
        $document->setClassHandler(
            function (ClassProcessor $a) {

            }
        );
        $content = $document->applyChanges(self::$stream);
    }
}
