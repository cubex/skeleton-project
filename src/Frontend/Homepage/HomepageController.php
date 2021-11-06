<?php
namespace Project\Frontend\Homepage;

use Project\Frontend\Layout\LayoutController;

class HomepageController extends LayoutController
{
  protected function _generateRoutes()
  {
    return 'homepage';
  }

  public function processHomepage()
  {
    return "This is a basic homepage";
  }
}
