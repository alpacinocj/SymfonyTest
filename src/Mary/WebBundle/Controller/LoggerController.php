<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class LoggerController extends BaseController
{
    public function indexAction()
    {
        $this->container->get('logger')->info('some log message', ['time' => time()]);
        return $this->render('MaryWebBundle:Logger:index.html.twig');
    }
}