<?php
/**
 * This file is a part of Perk project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace Perk\Handler;

use Perk\Event\LevelEvent;
use Perk\Event\ParserEvents;
use Perk\Event\StreamEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LevelHandler
 *
 * @package Perk\Handler
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class LevelHandler implements EventSubscriberInterface
{
    /**
     * @var LevelEvent
     */
    private $level;

    /**
     * Init
     */
    public function __construct()
    {
        $this->level = new LevelEvent();
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            '{' => 'levelUp',
            '}' => 'levelDown',
        ];
    }

    /**
     * @param StreamEvent $event
     * @param string $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function levelUp(StreamEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $stream = $event->getStream();
        $dispatcher->dispatch(ParserEvents::LEVEL_UP, $this->level->setPosition($stream->key()));
        $this->level->levelUp();
        $stream->next();
    }

    /**
     * @param StreamEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function levelDown(StreamEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $stream = $event->getStream();
        $dispatcher->dispatch(ParserEvents::LEVEL_DOWN, $this->level->levelDown()->setPosition($stream->key()));
        $stream->next();
    }
}
