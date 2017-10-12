<?php

namespace Mary\WebBundle\Event\Subscriber;

use Mary\WebBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent as Event;

class UserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        /*
         * array('eventName' => 'methodName')
         * array('eventName' => array('methodName', $priority))
         * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
         * */
        return [
            Events::USER_LOGIN_EVENT => ['onLogin', 0],
        ];
    }

    public function onLogin(Event $event)
    {
        $userEntity = $event->getSubject();
        $extra = $event->getArgument('extra');
        dump('trigger user subscriber success');
        dump('username is ' . $userEntity->getUsername());
        dump('extra is ' . $extra);
        die;
    }
}