<?php
/**
 * This file is a part of p-query project.
 *
 * (c) Andrey Kolchenko <andrey@kolchenko.me>
 */

namespace PQuery\Handler;

use PQuery\Event\LevelEvent;
use PQuery\Event\ParserEvents;
use PQuery\Event\StreamEvent;
use PQuery\Parser\Parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LevelHandler
 *
 * @package PQuery\Handler
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
     * @param $eventName
     * @param Parser $parser
     */
    public function levelUp(StreamEvent $event, $eventName, Parser $parser)
    {
        $stream = $event->getStream();
        $parser->dispatch(ParserEvents::LEVEL_UP, $this->level->setPosition($stream->key()));
        $this->level->up();
        $stream->next();
    }

    /**
     * @param StreamEvent $event
     * @param $eventName
     * @param Parser $parser
     */
    public function levelDown(StreamEvent $event, $eventName, Parser $parser)
    {
        $stream = $event->getStream();
        $parser->dispatch(ParserEvents::LEVEL_DOWN, $this->level->down()->setPosition($stream->key()));
        $stream->next();
    }
}
