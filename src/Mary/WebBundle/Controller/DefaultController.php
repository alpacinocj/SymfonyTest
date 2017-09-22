<?php

namespace Mary\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Default:index.html.twig');
    }
}
