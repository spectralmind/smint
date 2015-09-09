<?php

require_once dirname(__FILE__).'/../../symfony_libs/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfGuardPlugin');
    $this->enablePlugins('sfPropelPlugin');
  }
}
