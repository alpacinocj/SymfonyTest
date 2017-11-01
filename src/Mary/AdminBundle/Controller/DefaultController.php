<?php

namespace Mary\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MaryAdminBundle:Default:index.html.twig');
    }
}
