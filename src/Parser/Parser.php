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
     * @var array
     */
    private $namespaces;
    /**
     * @var array
     */
    private $classes;
    /**
     * @var array
     */
    private $methods;

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
            list($code, $value) = $stream->currentToken();
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
     * @param NewElementEvent $event
     */
    protected function onNewElement(NewElementEvent $event)
    {
        switch ($event->getType()) {
            case T_FUNCTION:
                array_push($this->methods, [$event->getPosition(), $event->getFinish()]);
                break;
            case T_CLASS:
                array_push($this->classes, [$event->getPosition(), $event->getFinish()]);
                break;
            case T_NAMESPACE:
                array_push($this->namespaces, [$event->getPosition(), $event->getFinish()]);
                break;
        }
    }

    /**
     * @param Stream $stream
     */
    private function prepare(Stream $stream)
    {
        $this->namespaces = [];
        $this->classes = [];
        $this->methods = [];
        $stream->rewind();
    }
}
