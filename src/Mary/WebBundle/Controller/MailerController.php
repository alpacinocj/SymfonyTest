<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class MailerController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Mailer:index.html.twig');
    }

    public function testAction()
    {
        $mailer = $this->getMailerService();
        $message = $mailer->createMessage()
            ->setSubject('Test mail')
            ->setFrom('alpacino_cj@163.com')
            ->setTo('279278253@qq.com')
            ->setBody(
                $this->renderView('emails/test.text.twig'),
                'text/plain'
            );

        $success = $mailer->send($message);

        echo $success ? '发送成功' : '发送失败';
        exit;
    }
}