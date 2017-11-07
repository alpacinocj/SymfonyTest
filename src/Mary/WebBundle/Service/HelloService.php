<?php

namespace Mary\WebBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class HelloService extends BaseService
{
    protected $language;
    protected $logger;
    protected $requestStack;

    public function __construct($language, LoggerInterface $logger = null, RequestStack $requestStack)
    {
        $this->language = $language;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $clientIp = $requestStack->getCurrentRequest()->getClientIp();
        if (null !== $this->logger) {
            $this->logger->debug($clientIp);
        }
    }

    public function say($name)
    {
        $greetings = "%s {$name}, %s !";

        switch ($this->language) {
            case 'en' :
                $greetings = sprintf($greetings, 'Hello', 'Welcome');
                break;
            case 'zh_CN' :
                $greetings = sprintf($greetings, '你好', '欢迎');
                break;
            default :
                $greetings = sprintf($greetings, 'Hello', 'Welcome');
                break;
        }

        if (null !== $this->logger) {
            $this->logger->info($greetings);
        }

        return $greetings;
    }
}