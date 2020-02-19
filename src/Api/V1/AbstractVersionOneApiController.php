<?php
namespace Project\Api\V1;

use Cubex\Controller\Controller;
use Packaged\Context\Context;
use Packaged\Http\Responses\JsonResponse;

abstract class AbstractVersionOneApiController extends Controller
{
  protected function _prepareResponse(Context $c, $result, $buffer = null)
  {
    if(is_array($result) || is_scalar($result))
    {
      $result = JsonResponse::create($result);
    }
    return parent::_prepareResponse($c, $result, $buffer);
  }
}
