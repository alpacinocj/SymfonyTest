<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/*
 * 事件
 * */
class EventController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Event:index.html.twig');
    }
}