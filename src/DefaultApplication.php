<?php
namespace Project;

use Cubex\Context\Context;
use Cubex\Cubex;
use Cubex\Routing\LazyHandler;
use Packaged\Dispatch\Resources\ResourceFactory;
use Packaged\Helpers\ValueAs;
use Packaged\Http\Request;
use Packaged\Http\Response;
use Packaged\Routing\HealthCheckCondition;
use Packaged\Routing\RequestCondition;
use Packaged\Routing\Route;
use Packaged\Routing\Routes\InsecureRequestUpgradeRoute;
use Project\Api\ApiApplication;
use Project\Frontend\FrontendApplication;

class DefaultApplication extends SkeletonApplication
{
  protected function _generateRoutes()
  {
    //Handle common health check calls.
    yield Route::with(new HealthCheckCondition())->setHandler(
      function () {
        //TODO: Create a health check method for your application
        return Response::create('OK');
      }
    );

    //Handle approved static resources from the public folder
    foreach(['favicon.ico', 'robots.txt'] as $publicFile)
    {
      yield self::_route(
        "/" . $publicFile,
        function (\Packaged\Context\Context $c) use ($publicFile) {
          return ResourceFactory::fromFile($c->getProjectRoot() . '/public/' . $publicFile);
        }
      );
    }

    if(ValueAs::bool($this->getContext()->config()->getItem('serve', 'redirect_https')))
    {
      yield InsecureRequestUpgradeRoute::i();
    }

    $proxies = $this->getContext()->config()->getItem('serve', 'trusted_proxies');
    if($proxies !== null)
    {
      Request::setTrustedProxies(ValueAs::arr($proxies), Request::HEADER_X_FORWARDED_ALL);
    }

    //Run any generic setup here
    $this->_setupApplication();

    //Route API Requests
    yield self::_route(
      '/',
      new LazyHandler(function (Context $c) { return (ApiApplication::withContext($c, $c->getCubex())); })
    )->add(RequestCondition::i()->subDomain('api'));

    //Route Frontend Requests
    $frontendHandler = new LazyHandler(
      function (Context $c) { return (FrontendApplication::withContext($c, $c->getCubex())); }
    );
    yield self::_route('/', $frontendHandler)->add(RequestCondition::i()->subDomain(''));
    yield self::_route('/', $frontendHandler)->add(RequestCondition::i()->subDomain('www'));

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
  }

  protected function _setupApplication()
  {
  }
}
