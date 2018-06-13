<?php
namespace Poirot\NavMenu\Services;

use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\NavMenu\Navigation;


class NavigationService
    extends aServiceContainer
{
    protected $settings;


    /**
     * Create Service
     *
     * @return Navigation
     */
    function newService()
    {

    }


    // ..

    /**
     * Factory Settings
     *
     * @param string|\Traversable $config
     */
    function setSettings($config)
    {
        $this->settings = $config;
    }
}
