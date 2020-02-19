<?php
define('PHP_START', microtime(true));

use Cubex\Cubex;
use Project\Context\SkeletonContext;
use Project\DefaultApplication;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

$loader = require_once(dirname(__DIR__) . '/vendor/autoload.php');
try
{
  //Create a global Cubex instance, using SkeletonContext
  $cubex = Cubex::withCustomContext(SkeletonContext::class, dirname(__DIR__), $loader);
  //Handle the incoming request through "DefaultApplication"
  $cubex->handle(new DefaultApplication($cubex));
  //Call the shutdown command
  $cubex->shutdown();
}
catch(Throwable $e)
{
  $handler = new Run();
  $handler->pushHandler(new PrettyPageHandler());
  $handler->handleException($e);
}
