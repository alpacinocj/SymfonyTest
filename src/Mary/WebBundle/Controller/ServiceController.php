<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ServiceController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Service:index.html.twig');
    }

    public function callAction()
    {
        echo $this->container->get('mary.webbundle.hello_service')->say('Tom');
        exit;
    }
}