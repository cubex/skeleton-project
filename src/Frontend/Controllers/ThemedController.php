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
   * @param         $result
   * @param null    $buffer
   *
   * @return mixed|Layout
   * @throws \Exception
   */
  protected function _prepareResponse(Context $c, $result, $buffer = null)
  {
    if($result instanceof Renderable || is_scalar($result))
    {
      $theme = new Layout();
      $theme->setContent($result);
      return parent::_prepareResponse($c, $theme, $buffer);
    }
    return parent::_prepareResponse($c, $result, $buffer);
  }
}
