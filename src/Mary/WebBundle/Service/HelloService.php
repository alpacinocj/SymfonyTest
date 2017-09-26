<?php

namespace Mary\WebBundle\Service;

use Psr\Log\LoggerInterface;

class HelloService extends BaseService
{
    protected $language;
    protected $logger;

    public function __construct($language, LoggerInterface $logger)
    {
        $this->language = $language;
        $this->logger = $logger;
    }

    public function say($name)
    {
        $greetings = "%s {$name}, %s !";
        switch ($this->language) {
            case 'en' :
                $greetings = sprintf($greetings, 'Hello', 'Welcome');
                break;
            case 'cn' :
                $greetings = sprintf($greetings, '你好', '欢迎');
                break;
            default :
                $greetings = sprintf($greetings, 'Hello', 'Welcome');
                break;
        }
        $this->logger->info($greetings);
        return $greetings;
    }
}