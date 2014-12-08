<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Provider;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

/**
 * Class ExpressionProvider
 *
 * @package PQuery\Provider
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class ExpressionProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @return ExpressionFunction[] An array of Function instances
     */
    public function getFunctions()
    {
        return [
            new ExpressionFunction('ns', [$this, 'namespaceCompiler'], [$this, 'namespaceEvaluator']),
        ];
    }

    public function namespaceCompiler($f)
    {
        return 'compiled: ' . $f;
    }

    public function namespaceEvaluator(array $arguments, \ArrayIterator $collection)
    {
        return 'e';
    }
}
