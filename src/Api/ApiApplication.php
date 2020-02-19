<?php
namespace Project\Api;

use Project\Api\V1\ApiVersionOne;
use Project\SkeletonApplication;

class ApiApplication extends SkeletonApplication
{
  protected function _generateRoutes()
  {
    $this->_configureConnections();
    yield self::_route('v1', ApiVersionOne::class);
  }
}
