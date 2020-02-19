<?php
namespace Project;

use Cubex\Application\Application;
use Packaged\Config\Provider\Ini\IniConfigProvider;
use Packaged\Dal\DalResolver;
use Packaged\Helpers\Path;

abstract class SkeletonApplication extends Application
{
  //Setup our database connections
  protected function _configureConnections()
  {
    $ctx = $this->getContext();
    $confDir = Path::system($ctx->getProjectRoot(), 'conf');

    $thisonnectionConfig = new IniConfigProvider();
    $thisonnectionConfig->loadFiles(
      [
        $confDir . DIRECTORY_SEPARATOR . 'defaults' . DIRECTORY_SEPARATOR . 'connections.ini',
        $confDir . DIRECTORY_SEPARATOR . $ctx->getEnvironment() . DIRECTORY_SEPARATOR . 'connections.ini',
      ]
    );
    $datastoreConfig = new IniConfigProvider();
    $datastoreConfig->loadFiles(
      [
        $confDir . DIRECTORY_SEPARATOR . 'defaults' . DIRECTORY_SEPARATOR . 'datastores.ini',
        $confDir . DIRECTORY_SEPARATOR . $ctx->getEnvironment() . DIRECTORY_SEPARATOR . 'datastores.ini',
      ]
    );
    $resolver = new DalResolver($thisonnectionConfig, $datastoreConfig);
    $this->getCubex()->share(DalResolver::class, $resolver);
    $resolver->boot();
  }
}
