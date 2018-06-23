<?php
namespace Poirot\NavMenu\Builder;

use Poirot\Ioc\Container;
use Poirot\Ioc\instance;
use Poirot\NavMenu\Navigation;
use Poirot\NavMenu\Services\Plugins\ContainerMenusCapped;
use Poirot\Std\Type\StdTravers;

/*
$navigation = BuildMenu::of([
    'menus' => [
        [
            'instance' => 'route',
            'label'     => 'دشبورد',
            'route_name' => 'main/dgfadmin.admin/dashboard',
        ],
        [
            'instance' => 'uri',
            'label'     => 'وضعیت سیستم',
            'href'      => '#',
            'menus' => [
                [
                    'instance' => 'route',
                    'label' => 'وضعیت Queue Workers',
                    'route_name' => 'main/dgfadmin.admin/dashboard',
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
     * @param ContainerMenusCapped $sc
     *
     * @return $this
     */
    function setMenuPlugins(ContainerMenusCapped $sc)
    {
        $this->sc = $sc;
        return $this;
    }


    // ..

    /**
     * Build Menus
     *
     * @param Navigation $navigation
     */
    protected function _buildMenus($navigation)
    {
        $defaultSettings = $this->defaultSettings;
        $keepSettings    = [
            'default_settings' => $defaultSettings,
            'menu_plugins'     => $this->sc,
        ];


        foreach ($this->menus as $m)
        {
            $class    = $m;
            $order    = null;

            if (! $m instanceof Navigation )
            {
                if ($m instanceof \Traversable)
                    $m = StdTravers::of($m)->toArray(null, true);


                $m = array_merge($defaultSettings, $m);

                if (! isset($m['instance']) )
                    throw new \InvalidArgumentException(
                        '"_instance" field on menu setting is required. given:(%s).'
                        , \Poirot\Std\flatten($m)
                    );


                // Order
                (!isset($m['order'])) ?: $order = $m['order'];
                unset($m['order']);

                // Class
                $class = $m['instance'];
                unset($m['instance']);

                // Menus
                $settings = $m;
                if ( isset($settings['menus']) ) {
                    $menus = $settings['menus'];
                    $keepSettings['menus'] = $menus;
                    unset($settings['menus']);
                }

                if (! is_object($class) )
                    $class = $this->_newMenuFromName($class, $settings);


                $class = static::of($keepSettings, $class);
            }


            $navigation->addMenu($class, $order);
        }
    }

    protected function _newMenuFromName($class, $settings)
    {
        if (! $this->sc->has($class) )
            return \Poirot\Ioc\newInitIns( new instance($class, $settings), $this->sc );


        $menu = $this->sc->get($class, $settings);
        return $menu;
    }
}
