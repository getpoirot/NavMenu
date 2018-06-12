<?php
namespace Poirot\NavMenu;

use Poirot\Std\Type\StdString;


abstract class aMenu
    extends GroupMenuHolder
{
    /** @var string Menu ID */
    protected $id;
    /** @var string|null Menu label */
    protected $label;
    /** @var string More descriptive for menu */
    protected $title;
    /** @var string Css class */
    protected $class;
    /** @var string */
    protected $target;
    /** @var bool Is menu active? */
    protected $active = false;
    /** @var array Menu item  */
    protected $attributes = [];


    /**
     * Returns href for this menu
     *
     * @return string
     */
    abstract public function getHref();


    // Options:

    /**
     * Sets label
     *
     * @param string $label
     *
     * @return $this
     */
    function setLabel($label)
    {
        $this->label = (string) $label;
        return $this;
    }

    /**
     * Returns label
     *
     * @return string
     */
    function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets id
     *
     * @param string|null $id
     *
     * @return $this
     */
    function setId($id = null)
    {
        $this->id = null === $id ? $id : (string) $id;

        return $this;
    }

    /**
     * Returns id
     *
     * @return string|null  page id or null
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Sets page CSS class
     *
     * @param  string|null $class
     *
     * @return $this
     */
    function setClass($class = null)
    {
        $this->class = null === $class ? $class : (string) $class;
        return $this;
    }

    /**
     * Returns Css class
     *
     * @return string|null
     */
    function getClass()
    {
        return $this->class;
    }

    /**
     * Sets title
     *
     * @param  string $title
     *
     * @return $this
     */
    function setTitle($title = null)
    {
        $this->title = null === $title ? $title : (string) $title;
        return $this;
    }

    /**
     * Returns title
     *
     * @return string|null
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets page target
     *
     * @param  string|null $target
     *
     * @return $this
     */
    function setTarget($target = null)
    {
        $this->target = null === $target ? $target : (string) $target;
        return $this;
    }

    /**
     * Returns target
     *
     * @return string|null
     */
    function getTarget()
    {
        return $this->target;
    }

    /**
     * Sets whether page should be considered active or not
     *
     * @param  bool $active
     *
     * @return $this
     */
    function setActive($active = true)
    {
        $this->active = (bool) $active;
        return $this;
    }

    /**
     * Returns whether page should be considered active or not
     *
     * @param  bool $recursive
     *
     * @return bool
     */
    function isActive($recursive = false)
    {
        if (! $this->active && $recursive)
        {
            /** @var aMenu $page */
            foreach ($this as $page)
                if ( $page->isActive(true) )
                    return true;

            return false;
        }


        return $this->active;
    }

    /**
     * Set Attribute
     *
     * @param string $attr
     * @param mixed  $value
     *
     * @return $this
     */
    function setAttribute($attr, $value)
    {
        $attr = $this->_normalizeAttrKey($attr);
        $this->attributes[$attr] = $value;

        return $this;
    }

    /**
     * Set Attributes
     *
     * @param array $attributes
     *
     * @return $this
     */
    function setAttributes(array $attributes)
    {
        foreach ($attributes as $k => $v)
            $this->setAttribute($k, $v);


        return $this;
    }

    /**
     * Get Specific Attribute
     *
     * @param $attr
     *
     * @return mixed|null
     */
    function getAttribute($attr)
    {
        $attr = $this->_normalizeAttrKey($attr);
        return ($this->attributes[$attr]) ?? null;
    }

    /**
     * Get Attributes
     *
     * @return array
     */
    function getAttributes()
    {
        return $this->attributes;
    }


    // ..

    function _normalizeAttrKey($key)
    {
        return strtolower( StdString::of((string) $key)->under_score() );
    }
}
