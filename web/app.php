<?php

use Symfony\Component\HttpFoundation\Request;

defined('APP_ENV') or define('APP_ENV', 'dev');
defined('APP_DEBUG') or define('APP_DEBUG', true);


require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../app/bootstrap.php.cache';

$kernel = new AppKernel(APP_ENV, APP_DEBUG);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
