<?php
namespace Project\Api\V1;

use Project\Api\V1\Controllers\CatsController;

class ApiVersionOne extends AbstractVersionOneApiController
{
  protected function _generateRoutes()
  {
    yield self::_route('version', 'version');
    yield self::_route('cats', CatsController::class);
  }

  public function getVersion()
  {
    return '1.0';
  }
}
