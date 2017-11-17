<?php

namespace Mary\Common\Util;

class Debugger
{
    public static function log($msg, $context = [], $level = 'INFO')
    {
        $logFile = APP_LOGS_PATH . 'debug.log';
        $logFormat = "[%s] %s %s %s" . PHP_EOL;
        $fp = fopen($logFile, 'a+');
        fwrite($fp, sprintf($logFormat, date('Y-m-d H:i:s'), $level, $msg, json_encode($context)));
        fclose($fp);
    }
}