<?php
namespace Project\Controllers;

use Packaged\QueryBuilder\SelectExpression\CustomSelectExpression;
use Project\Storage\ContentPage;

class ContentController extends ThemedController
{
  protected function _getConditions()
  {
    return 'content';
  }

  protected function processContent()
  {
    /** @var ContentPage $page */
    $page = ContentPage::collection()->orderBy(CustomSelectExpression::create('RAND()'))->first();
    if($page)
    {
      return $page->content;
    }
    return 'No page content found';
  }
}
