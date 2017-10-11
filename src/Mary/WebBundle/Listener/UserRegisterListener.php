<?php

namespace Mary\WebBundle\Listener;

use Mary\WebBundle\Event\UserRegisterEvent;

class UserRegisterListener
{
    public function onUserRegister(UserRegisterEvent $event)
    {
        $user = $event->getUser();
        $logger = $event->getLogger();

        $logger->info('trigger user register event success', ['username' => $user->getUsername()]);

    }
}