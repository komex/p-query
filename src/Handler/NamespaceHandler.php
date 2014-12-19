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
 * Class NamespaceHandler
 *
 * @package PQuery\Parser
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceHandler implements EventSubscriberInterface
{
    /**
     * @var int
     */
    private $position;
    /**
     * @var int
     */
    private $level;

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            T_NAMESPACE => 'handle',
        ];
    }

    /**
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function handle(StreamEvent $event, $eventName, Parser $parser)
    {
        $stream = $event->getStream();
        if ($this->position !== null) {
            $this->finish($event, $eventName, $parser);
        }
        $this->position = $stream->key();
        $parser->addListener(';', [$this, 'globalNS']);
        $parser->addListener(ParserEvents::LEVEL_UP, [$this, 'start']);
        $stream->next();
    }

    /**
     * ;
     *
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function globalNS(StreamEvent $event, $eventName, Parser $parser)
    {
        $parser->removeListener(';', [$this, 'globalNS']);
        $parser->removeListener(ParserEvents::LEVEL_UP, [$this, 'start']);
        $event->getStream()->next();
        $parser->addListener(ParserEvents::FINISH, [$this, 'finish']);
    }

    /**
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function finish(StreamEvent $event, $eventName, Parser $parser)
    {
        $parser->dispatch(
            ParserEvents::NEW_ELEMENT,
            new NewElementEvent(T_NAMESPACE, $this->position, $event->getStream()->key())
        );
    }

    /**
     * {
     *
     * @param LevelEvent $event
     * @param string|int $eventName
     * @param Parser $parser
     */
    public function start(LevelEvent $event, $eventName, Parser $parser)
    {
        $parser->removeListener(';', [$this, 'globalNS']);
        $parser->removeListener(ParserEvents::LEVEL_UP, [$this, 'start']);
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
                new NewElementEvent(T_NAMESPACE, $this->position, $event->getPosition())
            );
        }
    }
}
