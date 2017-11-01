<?php

namespace Mary\WebBundle\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Templating\EngineInterface;

/*
 * 请求异常监听
 * */
class KernelExceptionListener
{
    private $logger;
    private $mailer;

    public function __construct(LoggerInterface $logger, \Swift_Mailer $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // 忽略开发环境
        if (!APP_ENV_PROD) {
            return;
        }

        $exception = $event->getException();

        // 日志跟邮件也可以通过monolog配置实现, 见app/config/config_prod.yml 二选一

        /*// 记录日志
        $this->logger->info($exception->getTraceAsString());

        // 发送邮件
        $message = $this->mailer->createMessage()
            ->setSubject('Kernel Exception')
            ->setFrom('alpacino_cj@163.com')
            ->setTo('279278253@qq.com')
            ->setBody(
                $exception->getTraceAsString(),
                'text/plain'
            );

        $this->mailer->send($message);*/

        // 根据实际需要设置响应内容
        $errorFormat = 'some error occured: %s, with code: %s';
        $error = sprintf($errorFormat, $exception->getMessage(), $exception->getCode());
        $response = new Response();
        $response->setContent($error);
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}