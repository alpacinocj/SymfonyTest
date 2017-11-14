<?php

use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../app/config/constants.php';
require __DIR__ . '/../app/autoload.php';
include_once __DIR__ . '/../app/bootstrap.php.cache';

$kernel = new AppKernel(APP_ENV, APP_DEBUG);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
try {
    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Exception $e) {
    // 如果定义了KernelExceptionListener并且返回了一个Response实例, 则此处不会运行
    $error = [
        'error_file' => $e->getFile(),
        'error_line' => $e->getLine(),
        'error_code' => $e->getCode(),
        'error_info' => $e->getMessage()
    ];
    $errorInfo = json_encode($error);
    file_put_contents($kernel->getLogDir() . '/exception.log', $errorInfo . "\r\n", FILE_APPEND);
    print !APP_ENV_PROD ? $errorInfo : 'Server Error';
    exit;
}
