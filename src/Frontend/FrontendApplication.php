<?php
namespace Project\Frontend;

use Cubex\Events\Handle\ResponsePreSendHeadersEvent;
use Packaged\Context\Context;
use Packaged\Dispatch\Dispatch;
use Packaged\Http\Response;
use Packaged\Routing\Handler\FuncHandler;
use Project\Frontend\Content\ContentController;
use Project\Frontend\Homepage\HomepageController;
use Project\SkeletonApplication;

class FrontendApplication extends SkeletonApplication
{
  const DISPATCH_PATH = '/_r';

  protected function _initialize()
  {
    parent::_initialize();

    //Setup our asset/resource handler
    $dispatch = new Dispatch($this->getContext()->getProjectRoot(), self::DISPATCH_PATH);
    //Add any aliases for namespaces we wish to reduce
    $dispatch->addComponentAlias('\\Project\\Layout', 'PL');
    //Make this instance of dispatch, globally available
    Dispatch::bind($dispatch);
  }

  protected function _setupApplication()
  {
    //Send debug headers locally
    $this->getCubex()->listen(
      ResponsePreSendHeadersEvent::class,
      function (ResponsePreSendHeadersEvent $e) {
        $r = $e->getResponse();
        if($r instanceof Response && $e->getContext()->isEnv(\Cubex\Context\Context::ENV_LOCAL))
        {
          $r->enableDebugHeaders();
        }
      }
    );

    //Setup connections to the database if we are not handling static resources
    $this->_configureConnections();
  }

  protected function _generateRoutes()
  {
    yield self::_route(
      self::DISPATCH_PATH,
      new FuncHandler(
        function (Context $c): \Symfony\Component\HttpFoundation\Response {
          return Dispatch::instance()->handleRequest($c->request());
        }
      )
    );

    $this->_setupApplication();

    //Frontend L1 Routes
    yield self::_route("/content", ContentController::class);
    yield self::_route("/", HomepageController::class);
  }
}
