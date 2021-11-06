<?php
namespace Project\Frontend\Layout;

use Packaged\Context\ContextAware;
use Packaged\Context\ContextAwareTrait;
use Packaged\Ui\Element;

class HtmlWrap extends Element implements ContextAware
{
  use ContextAwareTrait;

  protected $_content = [];

  public function setContent($content)
  {
    $this->_content = $content;
    return $this;
  }

  public function getContent()
  {
    return $this->_content;
  }
}
