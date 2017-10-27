<?php

namespace Mary\WebBundle\Controller;

class UserController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:User:index.html.twig', []);
    }
}