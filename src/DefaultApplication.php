<?php
namespace Project;

use Cubex\Application\Application;
use Cubex\Console\Events\ConsolePrepareEvent;
use Cubex\Context\Context;
use Cubex\Cubex;
use Cubex\Events\Handle\ResponsePreSendHeadersEvent;
use Cubex\Http\FuncHandler;
use Cubex\Http\Handler;
use Cubex\Http\LazyHandler;
use Cubex\Routing\RequestConstraint;
use Packaged\Config\Provider\Ini\IniConfigProvider;
use Packaged\Dal\DalResolver;
use Packaged\Dispatch\Dispatch;
use Packaged\Dispatch\Resources\ResourceFactory;
use Packaged\Helpers\Path;
use Packaged\Http\Response;
use Project\Api\ApiHandler;
use Project\Frontend\Controllers\DefaultController;

class DefaultApplication extends Application
{
  const DISPATCH_PATH = '/_r';

  protected function _generateRoutes()
  {
    //Handle our favicon
    yield self::_route(
      '/favicon.ico',
      new FuncHandler(
        function () { return ResourceFactory::fromFile(Path::system(dirname(__DIR__), 'public', 'favicon.ico')); }
      )
    );

    //Handle dispatched resources
    yield self::_route(
      self::DISPATCH_PATH,
      new FuncHandler(function (Context $c) { return Dispatch::instance()->handleRequest($c->request()); })
    );

    //Setup connections to the database if we are not handling static resources
    $this->_configureConnections();

    //Route API Requests
    yield self::_route("/", new LazyHandler(function () { return new ApiHandler(); }))
      ->add(RequestConstraint::i()->subDomain('api'));

    //Let the parent application handle routes from here
    return parent::_generateRoutes();
  }

  protected function _defaultHandler(): Handler
  {
    //Default route handler
    return new DefaultController();
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

    //Resource Handler
    $this->_configureDispatch();

    //Send debug headers locally
    $cubex->listen(
      ResponsePreSendHeadersEvent::class,
      function (ResponsePreSendHeadersEvent $e) {
        $r = $e->getResponse();
        if($r instanceof Response && $e->getContext()->isEnv(Context::ENV_LOCAL))
        {
          $r->enableDebugHeaders();
        }
      }
    );
  }

  //Setup our asset/resource handler
  private function _configureDispatch()
  {
    //Bind & configure dispatch
    $dispatch = new Dispatch(dirname(__DIR__), self::DISPATCH_PATH);
    Dispatch::bind($dispatch);

    //Add any aliases for namespaces we wish to reduce
    $dispatch->addComponentAlias('\\Project\\Layout', 'PL');
  }

  //Setup our database connections
  private function _configureConnections()
  {
    $ctx = $this->getContext();
    $confDir = Path::system($ctx->getProjectRoot(), 'conf');

    $thisonnectionConfig = new IniConfigProvider();
    $thisonnectionConfig->loadFiles(
      [
        $confDir . DIRECTORY_SEPARATOR . 'defaults' . DIRECTORY_SEPARATOR . 'connections.ini',
        $confDir . DIRECTORY_SEPARATOR . $ctx->getEnvironment() . DIRECTORY_SEPARATOR . 'connections.ini',
      ]
    );
    $datastoreConfig = new IniConfigProvider();
    $datastoreConfig->loadFiles(
      [
        $confDir . DIRECTORY_SEPARATOR . 'defaults' . DIRECTORY_SEPARATOR . 'datastores.ini',
        $confDir . DIRECTORY_SEPARATOR . $ctx->getEnvironment() . DIRECTORY_SEPARATOR . 'datastores.ini',
      ]
    );
    $resolver = new DalResolver($thisonnectionConfig, $datastoreConfig);
    $this->getCubex()->share(DalResolver::class, $resolver);
    $resolver->boot();
  }
}
