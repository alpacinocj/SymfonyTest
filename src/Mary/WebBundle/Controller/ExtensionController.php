<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ExtensionController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Extension:index.html.twig');
    }

    /*
     * 常用模板扩展(functions, filters, tags)
     * */
    public function commonAction()
    {
        return $this->render('MaryWebBundle:Extension:common.html.twig');
    }

    /*
     * 自定义模板扩展
     * */
    public function customAction()
    {
        return $this->render('MaryWebBundle:Extension:custom.html.twig');
    }

    public function fragmentAction()
    {
        return $this->render('MaryWebBundle:Extension:fragment.html.twig');
    }

}