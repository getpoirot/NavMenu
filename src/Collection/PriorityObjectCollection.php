<?php
namespace Poirot\NavMenu;

use Poirot\Std\Struct\CollectionObject;


class PriorityObjectCollection
    extends CollectionObject
{
    protected $index = [];
    protected $_flagReIndex = false;


    /**
     * @inheritdoc
     */
    function insert($object, array $data = array())
    {
        static $order;
        if (! isset($order) )
            $order += 5;

        $eTag = parent::insert($object, $data);

        if ( isset($data['sort']) ) {
            $order = $data['sort'];
            $this->_flagReIndex = true; // re-index
        }

        $this->index[$eTag] = $order;
        return $eTag;
    }

    /**
     * @inheritdoc
     */
    function del($hashOrObject)
    {
        if ( $r = parent::del($hashOrObject) ) {
            $hash = $this->_attainHash($hashOrObject);
            unset($this->index[$hash]);
        }

        return $r;
    }


    // Implement Iterator:

    /**
     * @inheritdoc
     */
    function current()
    {
        $this->sort();
        current($this->index);
        $hash = key($this->index);

        return $this->find(['etag' => $hash]);
    }

    /**
     * @inheritdoc
     */
    function next()
    {
        $this->sort();
        next($this->index);
    }

    /**
     * @inheritdoc
     */
    function key()
    {
        $this->sort();
        return key($this->index);
    }

    /**
     * @inheritdoc
     */
    function valid()
    {
        $this->sort();
        return current($this->index) !== false;
    }

    /**
     * @inheritdoc
     */
    function rewind()
    {
        $this->sort();
        reset($this->index);
    }


    // ...

    /**
     * Sorts the page index according to page order
     *
     * @return void
     */
    function sort()
    {
        if (! $this->_flagReIndex)
            return;

        asort($this->index);
        $this->_flagReIndex = false;
    }

    /**
     * @param $object
     *
     * @throws \InvalidArgumentException
     */
    protected function doValidateObject($object)
    {
        parent::doValidateObject($object);

        if (! $object instanceof aMenu)
            throw new \InvalidArgumentException(sprintf(
                'Given Object Must Instance Of aMenu, given: (%s).'
                , \Poirot\Std\flatten($object)
            ));
    }
}
