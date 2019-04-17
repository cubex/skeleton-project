<?php
namespace Project\Api;

use Cubex\Controller\Controller;
use Project\Api\V1\ApiVersionOne;

class ApiHandler extends Controller
{
  protected function _getConditions()
  {
    yield self::_route('v1', ApiVersionOne::class);
  }
}
