<?php
namespace Project\Frontend\Controllers;

use Packaged\Helpers\Strings;

class DefaultController extends ThemedController
{
  protected function _getConditions()
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
