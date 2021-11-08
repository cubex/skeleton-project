<?php
namespace ProjectTests\Frontend\Content;

use PHPUnit\Framework\TestCase;
use Project\Frontend\Content\ContentModel;
use Project\Frontend\Content\ContentView;
use Project\Storage\ContentPage;

class ContentViewTest extends TestCase
{
  public function testNoContent()
  {
    $model = new ContentModel();
    /** @var \Cubex\Mv\View $view */
    $view = $model->createView(ContentView::class);
    self::assertStringContainsString('No page content found', $view->render());
  }

  public function testContentRendered()
  {
    $model = new ContentModel();
    $model->page = new ContentPage();
    $model->page->title = 'Page Title Y';
    $model->page->content = 'Page Content X';

    /** @var \Cubex\Mv\View $view */
    $view = $model->createView(ContentView::class);
    self::assertInstanceOf(ContentView::class, $view);
    $rendered = $view->render();

    self::assertStringContainsString($model->page->title, $rendered);
    self::assertStringContainsString($model->page->content, $rendered);
  }
}
