<?php
namespace Project\Frontend\Content;

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
    //Create a view model
    $model = ContentModel::withContext($this);

    /** @var ContentView $page */
    $model->page = ContentPage::collection()->orderBy(CustomSelectExpression::create('RAND()'))->first();

    //Return the model with a preferred view for improved testability
    return $model->setPreferredView(ContentView::class);
  }
}
