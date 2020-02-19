<?php
namespace Project;

use Cubex\Console\Events\ConsolePrepareEvent;
use Cubex\Context\Context;
use Cubex\Cubex;
use Cubex\Routing\LazyHandler;
use Packaged\Routing\HealthCheckCondition;
use Packaged\Routing\RequestCondition;
use Packaged\Routing\Route;
use Project\Api\ApiApplication;
use Project\Frontend\FrontendApplication;
use Symfony\Component\HttpFoundation\Response;

class DefaultApplication extends SkeletonApplication
{
  protected function _generateRoutes()
  {
    //Handle common health check calls.
    //TODO: Create a health check method for your application
    yield Route::with(new HealthCheckCondition())->setHandler(function () { return Response::create('OK'); });

    //Route API Requests
    yield self::_route(
      '/',
      new LazyHandler(function (Context $c) { return (new ApiApplication($c->getCubex()))->setContext($c); })
    )
      ->add(RequestCondition::i()->subDomain('api'));

    //Route Frontend Requests
    yield self::_route(
      '/',
      new LazyHandler(function (Context $c) { return (new FrontendApplication($c->getCubex()))->setContext($c); })
    )
      ->add(RequestCondition::i()->subDomain(''));
    yield self::_route(
      '/',
      new LazyHandler(function (Context $c) { return (new FrontendApplication($c->getCubex()))->setContext($c); })
    )
      ->add(RequestCondition::i()->subDomain('www'));

    //Let the parent application handle routes from here
    return parent::_generateRoutes();
  }

  public function __construct(Cubex $cubex)
  {
    parent::__construct($cubex);

    // Convert errors into exceptions
    set_error_handler(
      function ($errno, $errstr, $errfile, $errline) {
        if((error_reporting() & $errno) && !($errno & E_NOTICE))
        {
          throw new \ErrorException($errstr, 0, $errno, str_replace(dirname(__DIR__), '', $errfile), $errline);
        }
      }
    );

    //Setup Database connections for Console Commands
    $cubex->listen(
      ConsolePrepareEvent::class,
      function (ConsolePrepareEvent $e) {
        $this->setContext($e->getContext());
        $this->_configureConnections();
      }
    );
  }
}
