<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Handler;

use PQuery\Event\LevelEvent;
use PQuery\Event\NewElementEvent;
use PQuery\Event\ParserEvents;
use PQuery\Event\StreamEvent;
use PQuery\Parser\Parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AbstractStructureHandler
 *
 * @package PQuery\Handler
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
abstract class AbstractStructureHandler implements EventSubscriberInterface
{
    /**
     * @var int
     */
    private $level;
    /**
     * @var int
     */
    private $position;

    /**
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function handle(StreamEvent $event, $eventName, Parser $parser)
    {
        $stream = $event->getStream();
        $this->position = $stream->key();
        $stream->next();
        $parser->addListener(ParserEvents::LEVEL_UP, [$this, 'start']);
    }

    /**
     * @param LevelEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function start(LevelEvent $event, $eventName, Parser $parser)
    {
        $this->level = $event->getLevel();
        $parser->removeListener($eventName, [$this, __FUNCTION__]);
        $parser->addListener(ParserEvents::LEVEL_DOWN, [$this, 'end']);
    }

    /**
     * @param LevelEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function end(LevelEvent $event, $eventName, Parser $parser)
    {
        if ($this->level === $event->getLevel()) {
            $event->stopPropagation();
            $parser->removeListener($eventName, [$this, __FUNCTION__]);
            $parser->dispatch(
                ParserEvents::NEW_ELEMENT,
                new NewElementEvent($this->getType(), $this->position, $event->getPosition())
            );
        }
    }

    /**
     * @return int
     */
    abstract protected function getType();
}
