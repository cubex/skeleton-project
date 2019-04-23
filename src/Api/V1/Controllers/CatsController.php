<?php
namespace Project\Api\V1\Controllers;

use Project\Api\V1\AbstractVersionOneApiController;
use Project\Api\V1\Definitions\Cat;

class CatsController extends AbstractVersionOneApiController
{
  protected function _generateRoutes()
  {
    return 'cats';
  }

  public function getCats()
  {
    return [
      'items' => [
        new Cat("Molly", "Russian Blue"),
        new Cat("Polly", "Scottish Fold"),
        new Cat("Milly", "Maine Coon"),
      ],
    ];
  }

}
