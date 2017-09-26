<?php
namespace Mary\Common\Response;

interface ResponseFormatterInterface
{
    public function success($data);

    public function error($error, $code = 0);
}