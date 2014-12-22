<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Parser;

use PQuery\Event\StreamEvent;
use PQuery\Event\ParserEvents;
use PQuery\Event\NewElementEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Parser
 *
 * @package PQuery
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class Parser extends EventDispatcher
{
    /**
     * @var \ArrayIterator
     */
    private $namespaces;
    /**
     * @var \ArrayIterator
     */
    private $classes;
    /**
     * @var \ArrayIterator
     */
    private $functions;

    /**
     * Init parser
     */
    public function __construct()
    {
        $this->addListener(ParserEvents::NEW_ELEMENT, [$this, 'onNewElement']);
    }

    /**
     * @param Stream $stream
     */
    public function parse(Stream $stream)
    {
        $this->prepare($stream);
        $event = new StreamEvent($stream);
        while ($stream->valid() === true) {
            list($code, $value) = $stream->current();
            $eventName = ($code === null ? $value : $code);
            if ($this->hasListeners($eventName)) {
                $this->dispatch($eventName, $event);
            } else {
                $stream->next();
            }
        }
        $this->dispatch(ParserEvents::FINISH, $event);
    }

    /**
     * @return \ArrayIterator
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * @return \ArrayIterator
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @return \ArrayIterator
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * @param NewElementEvent $event
     */
    protected function onNewElement(NewElementEvent $event)
    {
        switch ($event->getType()) {
            case T_FUNCTION:
                $this->functions->append([$event->getPosition(), $event->getFinish()]);
                break;
            case T_CLASS:
                $this->classes->append([$event->getPosition(), $event->getFinish()]);
                break;
            case T_NAMESPACE:
                $this->namespaces->append([$event->getPosition(), $event->getFinish()]);
                break;
        }
    }

    /**
     * @param Stream $stream
     */
    private function prepare(Stream $stream)
    {
        $this->namespaces = new \ArrayIterator;
        $this->classes = new \ArrayIterator;
        $this->functions = new \ArrayIterator;
        $stream->rewind();
    }
}
