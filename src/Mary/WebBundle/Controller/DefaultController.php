<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
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

    public function showMessageAction(Request $request, $message)
    {
        return $this->render('MaryWebBundle:Default:show_message.html.twig', [
            'message' => $message
        ]);
    }
}
