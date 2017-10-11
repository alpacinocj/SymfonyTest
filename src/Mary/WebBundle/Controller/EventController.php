<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class EventController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Event:index.html.twig');
    }
}