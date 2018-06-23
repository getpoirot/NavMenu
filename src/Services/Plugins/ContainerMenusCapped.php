<?php
namespace Poirot\NavMenu\Services\Plugins;

use Poirot\Ioc\Container\aContainerCapped;
use Poirot\Ioc\Container\BuildContainer;
use Poirot\Ioc\Container\Exception\exContainerInvalidServiceType;
use Poirot\Ioc\Container\Service\ServicePluginLoader;
use Poirot\Loader\LoaderMapResource;
use Poirot\NavMenu\MenuRoute;
use Poirot\NavMenu\MenuUri;
use Poirot\NavMenu\Navigation;


class ContainerMenusCapped
    extends aContainerCapped
{
    protected $_map_resolver_options = [
        'uri'   => MenuUri::class,
        'route' => MenuRoute::class,
    ];


    /**
     * Construct
     *
     * @param BuildContainer $cBuilder
     *
     * @throws \Exception
     */
    function __construct(BuildContainer $cBuilder = null)
    {
        $this->_attachDefaults();

        parent::__construct($cBuilder);
    }

    /**
     * Validate Plugin Instance Object
     *
     * @param mixed $pluginInstance
     *
     * @throws \Exception
     */
    function validateService($pluginInstance)
    {
        if (! is_object($pluginInstance) )
            throw new \Exception(sprintf('Can`t resolve to (%s) Instance.', $pluginInstance));

        if (! $pluginInstance instanceof Navigation )
            throw new exContainerInvalidServiceType(sprintf(
                'Invalid Plugin Of Menus Provided; given: (%s).'
                , \Poirot\Std\flatten($pluginInstance)
            ));

    }


    // ..

    protected function _attachDefaults()
    {
        $service = new ServicePluginLoader([
            'resolver_options' => [
                LoaderMapResource::class => $this->_map_resolver_options
            ],
        ]);


        $this->set($service);
    }
}
