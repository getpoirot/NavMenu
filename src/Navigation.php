<?php
namespace Poirot\NavMenu;

use Poirot\NavMenu\Builder\BuildMenu;
use Poirot\Std\Exceptions\exImmutable;
use Poirot\Std\Struct\CollectionObject;


/**
 * Add Some Menu To Holder As Group Of Menus
 *
 */
class Navigation
    implements \Countable
    , \RecursiveIterator
{
    /** @var PriorityObjectCollection */
    protected $collection;
    /** @var BuildMenu */
    protected $builder;
    protected $defaultSettings = [];


    /**
     * Add Menu To Group Container
     *
     * @param aMenu $menu
     * @param int $order
     *
     * @return $this
     */
    function addMenu(aMenu $menu, $order = null)
    {
        ## Build Menu With Default Settings
        #
        $this->getBuilder()->of(
            $this->getDefaultSettings()
            , $menu
        );


        ## Add Menu To Collection
        #
        $this->_collection()->insert($menu, ['sort' => $order]);

        return $this;
    }

    /**
     * Add Menus At Once
     *
     * @param \Traversable $menus
     *
     * @return $this
     */
    function addMenus($menus)
    {
        foreach ($menus as $menu)
        {
            $order = null;
            if ( is_array($menu) ) {
                $menu  = $menu[0];
                $order = $menu[1];
            }

            $this->addMenu($menu, $order);
        }

        return $this;
    }

    /**
     * Menu Builder
     *
     * @param BuildMenu $builder
     *
     * @return $this
     */
    function giveMenuBuilder(BuildMenu $builder)
    {
        if ( $this->builder )
            throw new exImmutable('Builder Is Immutable.');

        $this->builder = $builder;
        return $this;
    }

    /**
     * Builder
     *
     * @return BuildMenu
     */
    function getBuilder()
    {
       if (! $this->builder )
           $this->builder = new BuildMenu;

        return $this->builder;
    }

    /**
     * Set Default Settings
     *
     * - some settings are default for container such as class, etc..
     *
     * @param array $settings
     *
     * @return $this
     */
    function giveDefaultSettings(array $settings)
    {
        if ( $this->defaultSettings )
            throw new exImmutable('Default Settings Is Immutable.');


        $this->defaultSettings = $settings;
        return $this;
    }

    /**
     * Get Default Settings
     *
     * @return array
     */
    function getDefaultSettings()
    {
        return $this->defaultSettings;
    }


    // ..

    /**
     * Collection Object
     *
     * @return CollectionObject
     */
    protected function _collection()
    {
        if (! $this->collection )
            $this->collection = new CollectionObject;

        return $this->collection;
    }


    // Implement RecursiveIterator:

    /**
     * @inheritdoc
     */
    function current()
    {
        return $this->_collection()->current();
    }

    /**
     * @inheritdoc
     */
    function next()
    {
        return $this->_collection()->next();
    }

    /**
     * @inheritdoc
     */
    function key()
    {
        return $this->_collection()->key();
    }

    /**
     * @inheritdoc
     */
    function valid()
    {
        return $this->_collection()->valid();
    }

    /**
     * @inheritdoc
     */
    function rewind()
    {
        $this->_collection()->rewind();
    }

    /**
     * @inheritdoc
     */
    function hasChildren()
    {
        return $this->valid() && $this->current()->count();
    }

    /**
     * @inheritdoc
     */
    function getChildren()
    {
        return $this->current()->current();
    }


    // Implement Countable:

    /**
     * @inheritdoc
     */
    function count()
    {
        $this->_collection()->count();
    }
}
