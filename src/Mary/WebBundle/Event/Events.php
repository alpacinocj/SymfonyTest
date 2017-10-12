<?php

namespace Mary\WebBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class Events extends Event
{
    const USER_REGISTER_EVENT   = 'user.register';
    const USER_LOGIN_EVENT      = 'user.login';
}