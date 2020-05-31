<?php
namespace Project;

use Cubex\Console\Events\ConsolePrepareEvent;
use Cubex\Context\Context;
use Cubex\Cubex;
use Cubex\Routing\LazyHandler;
use Packaged\Dispatch\Resources\ResourceFactory;
use Packaged\Helpers\Path;
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

    //Handle favicon.ico
    yield self::_route(
      "/favicon.ico",
      function (\Packaged\Context\Context $c) {
        return ResourceFactory::fromFile(Path::system($c->getProjectRoot(), 'resources/favicon/favicon.ico'));
      }
    );

    //handle robots.txt
    yield self::_route(
      "/robots.txt",
      function (Context $c) {
        return ResourceFactory::fromFile(Path::system($c->getProjectRoot(), 'public/robots.txt'));
      }
    );

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

  protected function _setupApplication()
  {

  }
}
