<?php
namespace Poirot\NavMenu\Builder;

use Poirot\NavMenu\Navigation;
use Poirot\Std\ConfigurableSetter;
use Poirot\Std\Interfaces\Pact\ipFactory;
use Poirot\Std\Type\StdTravers;

/*
$navigation = BuildMenu::of([
    'menus' => [
        [
            '_instance' => MenuUri::class,
            '_order' => 10,
            'title' => 'Google',
            'href'  => 'http://google.com',
            'menus' => [
                [
                    '_instance' => MenuUri::class,
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

class BuildMenu
    extends ConfigurableSetter
    implements ipFactory
{
    protected $menus = [];
    protected $defaultSettings = [];


    /**
     * Construct
     *
     * @param array|\Traversable $options
     */
    function __construct($options = null)
    {
        // Arrange Setter Priorities
        $this->putBuildPriority([
            'default_settings',
        ]);

        parent::__construct($options);
    }


    // Implement Factory:

    /**
     * Factory With Valuable Parameter
     *
     * @param mixed          $valuable
     * @param Navigation $navigation
     *
     * @return mixed
     */
    static function of($valuable = null, $navigation = null)
    {
        if ($navigation === null)
            $navigation = new Navigation;

        $self = new static($valuable);
        $self->build($navigation);

        return $navigation;
    }


    // Implement Builder:

    /**
     * Build Given Class and also return that class object
     *
     * @param mixed $navigation
     *
     * @return mixed
     */
    function build(Navigation $navigation)
    {
        $this->_buildMenus($navigation);
    }


    // Options:

    /**
     * Default Settings
     *
     * @param \Traversable $defaultSettings
     */
    function setDefaultSettings($defaultSettings)
    {
        if (! (is_array($defaultSettings) || $defaultSettings instanceof \Traversable) )
            throw new \InvalidArgumentException(sprintf(
                'Menu Builder Give "default_settings" option as Traversable; given: (%s).'
                , \Poirot\Std\flatten($defaultSettings)
            ));


        $this->defaultSettings = $defaultSettings;
    }

    /**
     * @param \Traversable $menus
     */
    function setMenus($menus)
    {
        if (! (is_array($menus) || $menus instanceof \Traversable) )
            throw new \InvalidArgumentException(sprintf(
                'Menu Builder Give "menus" option as Traversable; given: (%s).'
                , \Poirot\Std\flatten($menus)
            ));


        $this->menus = $menus;
    }


    // Builds:

    /**
     * Build Menus
     *
     * @param Navigation $navigation
     */
    protected function _buildMenus($navigation)
    {
        $defaultSettings = $this->defaultSettings;


        foreach ($this->menus as $m)
        {
            $class    = $m;
            $order    = null;

            if (! $m instanceof Navigation )
            {
                if ($m instanceof \Traversable)
                    $m = StdTravers::of($m)->toArray(null, true);


                $m = array_merge($defaultSettings, $m);

                if (! isset($m['_instance']) )
                    throw new \InvalidArgumentException(
                        '"_instance" field on menu setting is required. given:(%s).'
                        , \Poirot\Std\flatten($m)
                    );


                // Order
                (!isset($m['_order'])) ?: $order = $m['_order'];
                unset($m['_order']);

                // Class
                $class = $m['_instance'];
                unset($m['_instance']);

                // Menus
                $settings = array_merge($m, ['default_settings' => $defaultSettings]);

                if (! is_object($class) )
                    $class = $this->_newMenuFromName($class, $settings);

                $class = static::of($settings, $class);
            }


            $navigation->addMenu($class, $order);
        }
    }

    protected function _newMenuFromName($class, $settings)
    {
        if (! class_exists($class) )
            throw new \InvalidArgumentException(sprintf(
                'Class (%s) Not Found.'
                , $class
            ));


        $menu = new $class($settings);
        return $menu;
    }
}
