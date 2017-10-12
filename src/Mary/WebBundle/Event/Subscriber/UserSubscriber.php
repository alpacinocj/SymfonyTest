<?php

namespace Mary\WebBundle\Event\Subscriber;

use Mary\WebBundle\Event\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent as Event;

/*
 * 事件订阅
 * */
class UserSubscriber implements EventSubscriberInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        /*
         * array('eventName' => 'methodName')
         * array('eventName' => array('methodName', $priority))
         * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
         * */
        return [
            Events::USER_REGISTER_EVENT => ['onRegister', 0],
            Events::USER_LOGIN_EVENT => ['onLogin', 1],
        ];
    }

    public function onRegister(Event $event)
    {
        $userEntity = $event->getSubject();
        $extra = $event->getArgument('extra');
        $this->logger->info('trigger user subscriber success', ['username' => $userEntity->getUsername(), 'extra' => $extra]);
    }

    public function onLogin(Event $event)
    {
        $userEntity = $event->getSubject();
        $extra = $event->getArgument('extra');
        $this->logger->info('trigger user subscriber success', ['username' => $userEntity->getUsername(), 'extra' => $extra]);
    }
}