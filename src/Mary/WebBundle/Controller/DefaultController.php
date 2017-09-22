<?php

namespace Mary\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Default:index.html.twig');
    }

    public function helloAction(Request $request, $name)
    {
        return $this->render('MaryWebBundle:Default:hello.html.twig', [
            'name' => $name
        ]);
    }
}
