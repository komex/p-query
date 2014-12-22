<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Handler;

use Perk\Event\LevelEvent;
use Perk\Event\NewElementEvent;
use Perk\Event\ParserEvents;
use Perk\Event\StreamEvent;
use Perk\Parser\Parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AbstractStructureHandler
 *
 * @package Perk\Handler
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
     * @var int
     */
    private $start;

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
        $this->start = $event->getPosition();
        $parser->removeListener($eventName, [$this, __FUNCTION__]);
        $parser->addListener(ParserEvents::LEVEL_DOWN, [$this, 'end'], $this->level);
    }

    /**
     * @param LevelEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function end(LevelEvent $event, $eventName, Parser $parser)
    {
        if ($this->level === $event->getLevel()) {
            $parser->removeListener($eventName, [$this, __FUNCTION__]);
            $parser->dispatch(
                ParserEvents::NEW_ELEMENT,
                new NewElementEvent($this->getType(), $this->position, $this->start, $event->getPosition())
            );
        }
    }

    /**
     * @return int
     */
    abstract protected function getType();
}
