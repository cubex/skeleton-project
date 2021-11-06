<?php
namespace Project\Frontend\Layout;

use Cubex\Controller\Controller;
use Cubex\Mv\View;
use Cubex\Mv\ViewModel;
use Packaged\Context\Context;
use Packaged\Ui\Renderable;

abstract class LayoutController extends Controller
{
  protected function _prepareResponse(Context $c, $result, $buffer = null)
  {
    if($result instanceof ViewModel)
    {
      $result = $result->createView();
      if($result instanceof View)
      {
        $result = $result->render();
      }
    }

    if($result instanceof Renderable || is_scalar($result))
    {
      $layout = Layout::withContext($this)->setContent($result);
      return parent::_prepareResponse($c, (new HtmlWrap())->setContent($layout), $buffer);
    }

    return parent::_prepareResponse($c, $result, $buffer);
  }
}
