<?php
define('PHP_START', microtime(true));

use Cubex\Context\Context;
use Cubex\Cubex;
use Project\DefaultApplication;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$loader = require_once(dirname(__DIR__) . '/vendor/autoload.php');
try
{
  $cubex = new Cubex(dirname(__DIR__), $loader);
  //Handle the application, throwing exceptions locally only
  $cubex->handle(new DefaultApplication($cubex), true, $cubex->getSystemEnvironment() !== Context::ENV_LOCAL);
}
catch(Throwable $e)
{
  $handler = new Run();
  $handler->pushHandler(new PrettyPageHandler());
  $handler->handleException($e);
}
