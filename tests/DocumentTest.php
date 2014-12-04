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
     * Test count of PHP places.
     */
    public function testCount()
    {
        $stream = new \ArrayIterator(token_get_all(file_get_contents(__FILE__)));
        $document = new Document($stream);
        $document->getNamespace();
    }
}
