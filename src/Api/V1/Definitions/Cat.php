<?php
namespace Project\Api\V1\Definitions;

class Cat
{
  public $name;
  public $breed;

  public function __construct($name, $breed)
  {
    $this->name = $name;
    $this->breed = $breed;
  }
}
