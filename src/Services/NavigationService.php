<?php
namespace Poirot\NavMenu\Services;

use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\NavMenu\NavigationMenu;


class NavigationService
    extends aServiceContainer
{
    protected $settings;


    /**
     * Create Service
     *
     * @return NavigationMenu
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
