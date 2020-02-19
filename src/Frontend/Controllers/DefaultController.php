<?php
namespace Project\Frontend\Controllers;

use Packaged\Helpers\Strings;
use Project\Frontend\Layout\LayoutController;

class DefaultController extends LayoutController
{
  protected function _generateRoutes()
  {
    yield self::_route("/hello/{who}", "hello");
    yield self::_route("/content", ContentController::class);
    yield self::_route("/", "homepage");
  }

  public function getHello()
  {
    return "Hello " . Strings::titleize($this->getContext()->routeData()->getAlpha('who'));
  }

  public function getHomepage()
  {
    return "Homepage";
  }
}
