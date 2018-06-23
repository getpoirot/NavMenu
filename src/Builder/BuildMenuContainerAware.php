<?php
namespace Poirot\NavMenu\Builder;

use Poirot\Ioc\Container;
use Poirot\Ioc\instance;

/*
$navigation = BuildMenu::of([
    'menus' => [
        [
            '_class' => MenuUri::class,
            '_order' => 10,
            'title' => 'Google',
            'href'  => 'http://google.com',
            'menus' => [
                [
                    '_class' => MenuUri::class,
                    'title' => 'Mail',
                    'href'  => 'http://mail.google.com',
                ]
            ],
        ],
    ],
    'default_settings' => [
        'class' => 'menu-item',
    ],
], new Navigation );
*/

class BuildMenuContainerAware
    extends BuildMenu
{
    /** @var Container */
    protected $sc;


    // Options:

    /**
     * Set Container
     *
     * @param Container $sc
     *
     * @return $this
     */
    function setContainer(Container $sc)
    {
        $this->sc = $sc;
        return $this;
    }


    // ..

    protected function _newMenuFromName($class, $settings)
    {
        if (! $this->sc->has($class) )
            return \Poirot\Ioc\newInitIns( new instance($class, $settings), $this->sc );


        $menu = $this->sc->get($class, $settings);
        return $menu;
    }
}
