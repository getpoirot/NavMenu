<?php
namespace Poirot\NavMenu\Builder;

use Poirot\Ioc\Container;

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

    protected function _newMenuFromName($class)
    {
        if (! $this->sc->has($class) )
            return parent::_newMenuFromName($class);


        $menu = $this->sc->get($class);
        return $menu;
    }
}
