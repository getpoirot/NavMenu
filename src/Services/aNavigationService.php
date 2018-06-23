<?php
namespace Poirot\NavMenu\Services;

use Poirot\Application\aSapi;
use Poirot\Ioc\Container\Service\aServiceContainer;
use Poirot\NavMenu\Builder\BuildMenuContainerAware;
use Poirot\NavMenu\Interfaces\iMenuAwareMatchedRoute;
use Poirot\NavMenu\Interfaces\iMenuAwareRequest;
use Poirot\NavMenu\Interfaces\iMenuAwareRouter;
use Poirot\NavMenu\Navigation;
use Poirot\NavMenu\Services\Plugins\ContainerMenusCapped;
use Poirot\Std\Struct\DataEntity;


/**
 * !! Note: Navigation Classes Must Extend This
 *
 */
abstract class aNavigationService
    extends aServiceContainer
{
    /** @var array */
    protected $settings;
    /** @var ContainerMenusCapped */
    protected $plugins;


    /**
     * Create Service
     *
     * @return Navigation
     */
    final function newService()
    {
        $plugins    = $this->_getMenusPlugin();
        $settings   = $this->_getSettings();
        $settings   = [ 'menu_plugins' => $plugins ] + $settings ;

        $navigation = BuildMenuContainerAware::of(
            $settings
            , new Navigation
        );


        return $navigation;
    }


    // Options:

    /**
     * Factory Settings
     *
     * @param string|\Traversable $config
     */
    function setSettings($config)
    {
        $this->settings = $config;
    }

    /**
     * Set Plugins Container
     *
     * @param ContainerMenusCapped $capped
     */
    function setMenuPlugins(ContainerMenusCapped $capped)
    {
        $this->plugins = $capped;
    }


    // ..

    /**
     * Menu Plugins
     *
     * @return ContainerMenusCapped
     */
    protected function _getMenusPlugin()
    {
        if ( $this->plugins )
            return $this->plugins;


        $plugins = new ContainerMenusCapped;


        ## Initialize service dependencies
        #
        $self = $this;
        $plugins->initializer()->addCallable(function($serviceInstance) use ($plugins, $self)
        {
            if ($serviceInstance instanceof iMenuAwareRequest)
                $serviceInstance->setRequest( $self->services()->get('/HttpRequest') );

            if ($serviceInstance instanceof iMenuAwareMatchedRoute)
                $serviceInstance->setRouteMatch( $self->services()->get('/router.match') );

            if ($serviceInstance instanceof iMenuAwareRouter)
                $serviceInstance->setRouter( $self->services()->get('/Router') );
        });


        return $plugins;
    }


    /**
     * Get Settimgs
     *
     * - Define In Merged Config:
     *   NavigationService::class => include __DIR__.'/inc.navigation.conf.php',
     *
     * @return array
     */
    protected function _getSettings()
    {
        if ( $this->settings )
            return $this->settings;


        return $this->_getConf();
    }

    /**
     * Get Config Values
     *
     * @return mixed|null
     * @throws \Exception
     */
    protected function _getConf()
    {
        // retrieve and cache config
        $services = $this->services();

        /** @var aSapi $config */
        $config = $services->get('/sapi');
        $config = $config->config();
        /** @var DataEntity $config */
        if (! $config->has( $this->_getKeyConf() ) )
            throw new \Exception(sprintf(
                'Settings For Navigation(%s) Not Found In Merged Configs.'
                , $this->_getKeyConf()
            ));


        return $config->get( $this->_getKeyConf() );
    }

    protected function _getKeyConf()
    {
        return get_class($this);
    }
}
