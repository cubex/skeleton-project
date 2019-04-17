<?php
namespace Project\Frontend\Controllers;

use Cubex\Context\Context;
use Cubex\Controller\Controller;
use Packaged\Ui\Renderable;
use Project\Frontend\Layout\Layout;

abstract class ThemedController extends Controller
{
  /**
   * @param Context $c
   * @param         $obj
   *
   * @return mixed|Layout
   * @throws \Exception
   */
  protected function _prepareResponse(Context $c, $obj)
  {
    $obj = parent::_prepareResponse($c, $obj);
    if($obj instanceof Renderable || is_scalar($obj))
    {
      $theme = new Layout();
      $theme->setContent($obj);
      return $theme;
    }
    return $obj;
  }
}
