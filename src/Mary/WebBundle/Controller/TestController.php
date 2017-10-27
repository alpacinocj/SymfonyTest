<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class TestController extends BaseController
{
    public function adminAction()
    {
        // 此方法仅用来测试访问控制, 只有role为ROLE_ADMIN才能访问
        $msg = 'This page is accesiable only by admin ROLE';
        //$this->denyAccessUnlessGranted('ROLE_ADMIN', null, $msg);     // 此方法可以用来保护单一的action访问权限
        echo $msg; die;
    }
}