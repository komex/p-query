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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class NamespaceHandler
 *
 * @package Perk\Parser
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
     * @var int
     */
    private $start;

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
     * @param EventDispatcherInterface $dispatcher
     */
    public function handle(StreamEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $stream = $event->getStream();
        if ($this->position !== null) {
            $this->finish($event, $eventName, $dispatcher);
        }
        $this->position = $stream->key();
        $dispatcher->addListener(';', [$this, 'globalNS']);
        $dispatcher->addListener(ParserEvents::LEVEL_UP, [$this, 'start']);
        $stream->next();
    }

    /**
     * ;
     *
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function globalNS(StreamEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->removeListener(';', [$this, 'globalNS']);
        $dispatcher->removeListener(ParserEvents::LEVEL_UP, [$this, 'start']);
        $event->getStream()->next();
        $dispatcher->addListener(ParserEvents::FINISH, [$this, 'finish']);
    }

    /**
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function finish(StreamEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->dispatch(
            ParserEvents::NEW_ELEMENT,
            new NewElementEvent(T_NAMESPACE, $this->position, $this->position, $event->getStream()->key())
        );
    }

    /**
     * {
     *
     * @param LevelEvent $event
     * @param string|int $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function start(LevelEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $this->level = $event->getLevel();
        $this->start = $event->getPosition();
        $dispatcher->removeListener(';', [$this, 'globalNS']);
        $dispatcher->removeListener(ParserEvents::LEVEL_UP, [$this, 'start']);
        $dispatcher->addListener(ParserEvents::LEVEL_DOWN, [$this, 'end']);
    }

    /**
     * @param LevelEvent $event
     * @param string|int $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function end(LevelEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        if ($this->level === $event->getLevel()) {
            $dispatcher->removeListener($eventName, [$this, __FUNCTION__]);
            $dispatcher->dispatch(
                ParserEvents::NEW_ELEMENT,
                new NewElementEvent(T_NAMESPACE, $this->position, $this->start, $event->getPosition())
            );
        }
    }
}
