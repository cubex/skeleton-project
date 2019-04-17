<?php
namespace Project\Layout;

use Packaged\Dispatch\Component\DispatchableComponent;
use Packaged\Dispatch\ResourceManager;
use Packaged\SafeHtml\ISafeHtmlProducer;
use Packaged\Ui\Element;
use PackagedUi\FontAwesome\FaIcon;

class Layout extends Element implements DispatchableComponent
{
  protected $_content;

  /**
   * Layout constructor.
   *
   * @throws \Exception
   */
  public function __construct()
  {
    ResourceManager::component($this)->requireCss('css/layout.css');
    ResourceManager::external()->requireCss(
      'https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,500,500i,700,700i,900'
    );
    ResourceManager::vendor('packaged-ui', 'fontawesome')->requireCss(FaIcon::CSS_PATH);
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
