<?php

namespace Mary\WebBundle\Event\Subscriber;

use Mary\WebBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent as Event;

class UserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::USER_LOGIN_EVENT => 'onLogin'
        ];
    }

    public function onLogin(Event $event)
    {

    }
}