<?php
namespace Poirot\NavMenu\Builder;

use Poirot\NavMenu\NavigationMenu;
use Poirot\Std\ConfigurableSetter;
use Poirot\Std\Interfaces\Pact\ipBuilder;
use Poirot\Std\Interfaces\Pact\ipFactory;
use Poirot\Std\Type\StdTravers;


class BuildMenu
    extends ConfigurableSetter
    implements ipBuilder
    , ipFactory
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
     * @param NavigationMenu $navigation
     *
     * @return mixed
     */
    static function of($valuable = null, $navigation = null)
    {
        if ($navigation === null)
            $navigation = new NavigationMenu;

        $self = new self($valuable);
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
    function build(NavigationMenu $navigation)
    {
        $this->_buildDefaultSettings($navigation);

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
     * Build Default Settings
     *
     * @param NavigationMenu $navigation
     */
    protected function _buildDefaultSettings($navigation)
    {
        $settings = $this->defaultSettings;
        if (! is_array($settings) )
            $settings = StdTravers::of($settings)->toArray(null, true);


        $navigation->giveDefaultSettings($settings);
    }

    /**
     * Build Menus
     *
     * @param NavigationMenu $navigation
     */
    protected function _buildMenus($navigation)
    {
        foreach ($this->menus as $m)
        {
            $class    = $m;
            $order    = null;
            $settings = [];

            if (! $m instanceof NavigationMenu )
            {
                if ($m instanceof \Traversable)
                    $m = StdTravers::of($m)->toArray(null, true);

                if (! isset($m['class']) )
                    throw new \InvalidArgumentException(
                        '"_class" field on menu setting is required. given:(%s).'
                        , \Poirot\Std\flatten($m)
                    );


                // Order
                (!isset($m['order'])) ?: $order = $m['order'];

                // Class
                $class = $m['class'];
                if (! is_object($class) ) {
                    if (! class_exists($class) )
                        throw new \InvalidArgumentException(sprintf(
                            'Class (%s) Not Found.'
                            , $m
                        ));

                    $class = new $class;
                }

                $settings = $m['settings'] ?? [];
            }


            $class = self::of($settings, $class);
            $navigation->addMenu($class, $order);
        }
    }
}
