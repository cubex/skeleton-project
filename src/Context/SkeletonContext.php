<?php
namespace Project\Context;

use Cubex\Context\Context;
use Cubex\Context\Events\ConsoleCreatedEvent;
use Cubex\Cubex;
use Project\Cli\CliApplication;

class SkeletonContext extends Context
{
  protected function _construct()
  {
    parent::_construct();
    //Setup Database connections for Console Commands
    $this->events()->listen(
      ConsoleCreatedEvent::class,
      function () {
        $this->getCubex()->share(CliApplication::class, CliApplication::launch($this), Cubex::MODE_IMMUTABLE);
      }
    );
  }

}
