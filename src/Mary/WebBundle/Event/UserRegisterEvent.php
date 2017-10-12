<?php

namespace Mary\WebBundle\Event;

use Psr\Log\LoggerInterface;
use Mary\WebBundle\Entity\User as UserEntity;

/**
 * 自定义事件
 * Class UserRegisterEvent
 * @package Mary\WebBundle\Event
 */
class UserRegisterEvent extends Events
{

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