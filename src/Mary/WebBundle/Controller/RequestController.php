<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function errorAction()
    {
        try {
            echo 1 / 0;
        } catch (\Exception $e) {
            return $this->createBadRequestException($e->getMessage());
        }
    }
}