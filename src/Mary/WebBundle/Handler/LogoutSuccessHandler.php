<?php

namespace Mary\WebBundle\Handler;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;

class LogoutSuccessHandler extends DefaultLogoutSuccessHandler
{
    public function onLogoutSuccess(Request $request)
    {
        // 用户登出成功之后的一些处理
        // some code here ...

        // set custom targetUrl if you need, default is '/'
        $this->targetUrl = '/login';

        return parent::onLogoutSuccess($request);
    }
}