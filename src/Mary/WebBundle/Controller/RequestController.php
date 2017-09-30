<?php

namespace Mary\WebBundle\Controller;

class RequestController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Request:index.html.twig');
    }

    /*
     * Custome Curl Service Usage
     * */
    public function curlAction()
    {
        $curl = $this->getCurlService();
        $result = $curl->get('http://www.baidu.com');
        echo $result;
        exit;
    }
}