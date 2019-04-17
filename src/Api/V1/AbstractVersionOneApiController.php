<?php
namespace Project\Api\V1;

use Cubex\Context\Context;
use Cubex\Controller\Controller;
use Packaged\Http\Responses\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractVersionOneApiController extends Controller
{
  protected function _handleResponse(Context $c, $response, ?string $buffer = null): Response
  {
    if(is_array($response) || is_scalar($response))
    {
      $response = JsonResponse::create($response);
    }
    return parent::_handleResponse($c, $response, $buffer);
  }
}
