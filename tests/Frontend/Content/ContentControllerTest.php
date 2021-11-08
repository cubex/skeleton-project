<?php
namespace ProjectTests\Frontend\Content;

use Cubex\Cubex;
use PHPUnit\Framework\TestCase;
use Project\Context\SkeletonContext;
use Project\Frontend\Content\ContentController;
use Project\Frontend\Content\ContentModel;
use Project\Storage\ContentPage;
use ProjectTests\TestApplication;

class ContentControllerTest extends TestCase
{
  public function testProcessContent()
  {
    //Configure DB
    $ctx = new SkeletonContext();
    $app = new TestApplication(new Cubex(dirname(__DIR__, 3), null, false));
    $app->setContext($ctx);
    $app->configureConnections();

    //Test
    $controller = new ContentController();
    $controller->setContext($ctx);

    $processResult = $controller->processContent();
    self::assertInstanceOf(ContentModel::class, $processResult);
    self::assertInstanceOf(ContentPage::class, $processResult->page);
  }
}
