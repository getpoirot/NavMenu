<?php
namespace Poirot\NavMenu;
use Poirot\Std\Struct\CollectionObject;


/**
 * Add Some Menu To Holder As Group Of Menus
 *
 */
class GroupMenuHolder
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
        $this->collection->insert($menu, ['sort' => $order]);
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
        $this->collection->current();
    }

    /**
     * @inheritdoc
     */
    function next()
    {
        $this->collection->next();
    }

    /**
     * @inheritdoc
     */
    function key()
    {
        $this->collection->key();
    }

    /**
     * @inheritdoc
     */
    function valid()
    {
        $this->collection->valid();
    }

    /**
     * @inheritdoc
     */
    function rewind()
    {
        $this->collection->rewind();
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
        $this->collection->count();
    }
}
