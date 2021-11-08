<?php
namespace ProjectTests;

use Project\SkeletonApplication;

class TestApplication extends SkeletonApplication
{
  public function configureConnections()
  {
    $this->_configureConnections();
    return $this;
  }
}
