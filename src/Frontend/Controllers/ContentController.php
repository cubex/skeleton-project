<?php
namespace Project\Frontend\Controllers;

use Packaged\Glimpse\Tags\Div;
use Packaged\Glimpse\Tags\Text\HeadingOne;
use Packaged\Glimpse\Tags\Text\Paragraph;
use Packaged\QueryBuilder\SelectExpression\CustomSelectExpression;
use Project\Frontend\Layout\LayoutController;
use Project\Storage\ContentPage;

class ContentController extends LayoutController
{
  protected function _generateRoutes()
  {
    return 'content';
  }

  protected function processContent()
  {
    /** @var ContentPage $page */
    $page = ContentPage::collection()->orderBy(CustomSelectExpression::create('RAND()'))->first();
    if($page)
    {
      return Div::create(HeadingOne::create($page->title), Paragraph::create($page->content));
    }
    return Paragraph::create('No page content found');
  }
}
