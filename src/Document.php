<?php
/**
 * This file is a part of pQuery project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery;

use PQuery\Collection\NamespaceCollection;
use PQuery\Element\AbstractElement;
use PQuery\Element\ClassElement;
use PQuery\Element\FunctionElement;
use PQuery\Element\NamespaceElement;
use PQuery\Filter\NamespaceFilter;
use PQuery\Provider\ExpressionProvider;
use Symfony\Component\DependencyInjection\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 * Class Document
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Document
{
    /**
     * @var \ArrayIterator
     */
    private $stream;
    /**
     * @var AbstractElement[]|\ArrayIterator
     */
    private $elements;

    /**
     * @param \ArrayIterator $stream
     */
    public function __construct(\ArrayIterator $stream)
    {
        $parser = new Parser($stream);
        $this->elements = $parser->getElements();
        $this->stream = $stream;
    }

    /**
     * @param string|null $mask
     *
     * @return NamespaceCollection
     */
    public function getNamespaces($mask = null)
    {
        return new NamespaceCollection(new NamespaceFilter($this->elements, $mask));
    }

    /**
     * @return ClassElement[]
     */
    public function getClasses()
    {
        return array_values(
            array_filter(
                $this->elements,
                function (AbstractElement $element) {
                    return ($element instanceof ClassElement);
                }
            )
        );
    }

    /**
     * @return FunctionElement[]
     */
    public function getMethods()
    {
        return array_values(
            array_filter(
                $this->elements,
                function (AbstractElement $element) {
                    return ($element instanceof FunctionElement);
                }
            )
        );
    }
}
