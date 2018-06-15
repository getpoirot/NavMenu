<?php
namespace Poirot\NavMenu\Builder;

use Poirot\Ioc\Container;
use Poirot\Ioc\instance;

/*
$navigation = BuildMenu::of([
    'menus' => [
        [
            'class' => 'uri',
            'order' => 10,
            'settings' => [
                'href' => 'http://google.com',
            ],
        ],
    ],
    'default_settings' => [
        'class' => 'menu-item',
    ],
], new NavigationMenu );
*/

class BuildMenuContainerAware
    extends BuildMenu
{
    /** @var Container */
    protected $sc;


    /**
     * Construct
     *
     * @param Container          $locator
     * @param array|\Traversable $options
     */
    function __construct(Container $locator, $options = null)
    {
        $this->sc = $locator;

        parent::__construct($options);
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
