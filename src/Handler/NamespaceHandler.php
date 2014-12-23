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

/**
 * Class NamespaceHandler
 *
 * @package Perk\Parser
 * @author Andrey Kolchenko <andrey@kolchenko.me>
 */
class NamespaceHandler extends AbstractStructureHandler
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [T_NAMESPACE => 'handle'];
    }

    /**
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function handle(StreamEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        if ($this->position !== null) {
            $this->finish($event, $eventName, $dispatcher);
        }
        $dispatcher->addListener(';', [$this, 'globalNS']);
        parent::handle($event, $eventName, $dispatcher);
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
        $dispatcher->removeListener($eventName, [$this, __FUNCTION__]);
        $dispatcher->removeListener(ParserEvents::LEVEL_UP, [$this, 'start']);
        $dispatcher->addListener(ParserEvents::FINISH, [$this, 'finish']);
        $event->getStream()->next();
    }

    /**
     * @param StreamEvent $event
     * @param string|int $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function finish(StreamEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $this->createElement($dispatcher, $event->getStream()->key());
        $dispatcher->removeListener($eventName, [$this, __FUNCTION__]);
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
        $dispatcher->removeListener(';', [$this, 'globalNS']);
        parent::start($event, $eventName, $dispatcher);
    }

    /**
     * @return int
     */
    protected function getType()
    {
        return T_NAMESPACE;
    }
}
