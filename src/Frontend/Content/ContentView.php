<?php
namespace Project\Frontend\Content;

use Cubex\Mv\AbstractView;
use Cubex\Mv\Model;
use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Text\HeadingOne;
use Packaged\Glimpse\Tags\Text\Paragraph;
use Packaged\SafeHtml\ISafeHtmlProducer;

class ContentView extends AbstractView
{
  /**
   * @var \Cubex\Mv\Model|null|\Project\Frontend\Content\ContentModel
   */
  protected ?Model $_model;

  protected function _render(): ?ISafeHtmlProducer
  {
    if(!$this->_model->page)
    {
      return Paragraph::create('No page content found');
    }

    return Div::create(
      HeadingOne::create($this->_model->page->title),
      Paragraph::create($this->_model->page->content)
    );
  }
}
