<?php

namespace Mary\WebBundle\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;
use Mary\WebBundle\Entity\User as UserEntity;

class UserRegisterEvent extends Event
{
    const EVENT_NAME = 'user.register';

    protected $user;
    protected $logger;

    public function __construct(UserEntity $user, LoggerInterface $logger)
    {
        $this->user = $user;
        $this->logger = $logger;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getLogger()
    {
        return $this->logger;
    }
}