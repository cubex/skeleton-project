<?php
namespace Project\Frontend\Layout;

use Packaged\Context\ContextAware;
use Packaged\Context\ContextAwareTrait;
use Packaged\Context\WithContext;
use Packaged\Context\WithContextTrait;
use Packaged\Dispatch\Component\DispatchableComponent;
use Packaged\Dispatch\ResourceManager;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\Ui\Element;

class Layout extends Element implements DispatchableComponent, ContextAware, WithContext
{
  use ContextAwareTrait;
  use WithContextTrait;

  protected $_content;

  public function __construct()
  {
    ResourceManager::componentClass(self::class)->requireCss('css/layout.css');
  }

  public function setContent($content)
  {
    if($content instanceof ISafeHtmlProducer)
    {
      $this->_content = $content->produceSafeHTML()->getContent();
    }
    else
    {
      $this->_content = $content;
    }
    return $this;
  }

  public function getContent()
  {
    return $this->_content;
  }
}
