<?php
namespace Project\Frontend\Layout;

use Cubex\Controller\Controller;
use Packaged\Context\Context;
use Packaged\Glimpse\Core\HtmlTag;
use Packaged\Ui\Element;

abstract class LayoutController extends Controller
{
  protected function _prepareResponse(Context $c, $result, $buffer = null)
  {
    if($result instanceof Element || $result instanceof HtmlTag || is_scalar($result))
    {
      $layout = new Layout();
      $this->_bindContext($layout);
      $layout->setContent($result);
      return parent::_prepareResponse($c, (new HtmlWrap())->setContent($layout), $buffer);
    }

    return parent::_prepareResponse($c, $result, $buffer);
  }
}
