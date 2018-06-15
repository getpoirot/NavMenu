<?php
namespace Poirot\NavMenu;

use Poirot\Std\ConfigurableSetter;
use Poirot\NavMenu\Collection\PriorityObjectCollection;


/**
 * Add Some Menu To Holder As Group Of Menus
 *
 */
class Navigation
    extends ConfigurableSetter
    implements \Countable
    , \RecursiveIterator
{
    /** @var PriorityObjectCollection */
    protected $collection;


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
    function setMenus($menus)
    {
        $this->_collection()->clean();

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


    // ..

    /**
     * Collection Object
     *
     * @return PriorityObjectCollection
     */
    protected function _collection()
    {
        if (! $this->collection )
            $this->collection = new PriorityObjectCollection;

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
        $this->_collection()->next();
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
        return $this->_collection()->count();
    }
}
