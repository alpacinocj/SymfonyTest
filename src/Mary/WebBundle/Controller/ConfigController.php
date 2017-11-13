<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use Mary\WebBundle\DependencyInjection\Configuration;

class ConfigController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Config:index.html.twig');
    }

    public function showParameterAction(Request $request, $key)
    {
        try {
            $value = $this->container->getParameter($key);
        } catch (\Exception $e) {
            return $this->redirectToRoute('show_message', [
                'message' => $e->getMessage()
            ]);
        }
        return $this->render('MaryWebBundle:Config:show_parameter.html.twig', [
            'value' => $value
        ]);
    }

    public function loadAction()
    {
        echo 'load test'; die;
    }
}