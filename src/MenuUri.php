<?php
namespace Poirot\NavMenu;

use Poirot\Http\Interfaces\iHttpRequest;


class MenuUri
    extends aMenu
{
    /** @var string */
    protected $href;
    /** @var iHttpRequest */
    protected $request;


    /**
     * MenuUri constructor.
     *
     * @param iHttpRequest $httpRequest
     */
    function __construct(iHttpRequest $httpRequest = null)
    {
        $this->setRequest($httpRequest);
    }


    /**
     * Returns href for this page
     *
     * @return string  the page's href
     */
    function getHref()
    {
        return $this->href;
    }

    /**
     * Returns whether page should be considered active or not
     *
     * This method will compare the page properties against the request uri.
     *
     * @param bool $recursive
     *
     * @return bool
     */
    function isActive($recursive = false)
    {
        if (! $this->active)
        {
            if ( $this->getRequest() )
            {
                $reqTarget = $this->_getPathOfUri( $this->getRequest()->getTarget() );
                $curTarget = $this->_getPathOfUri( $this->getHref() );
                if ($reqTarget == $curTarget ) {
                    $this->active = true;
                    return true;
                }
            }
        }

        return parent::isActive($recursive);
    }



    // Options:

    /**
     * Set Href Uri
     *
     * @param string $href
     *
     * @return $this
     */
    function setHref(string $href)
    {
        $this->href = $href;
        return $this;
    }

    /**
     * Get the request
     *
     * @return iHttpRequest
     */
    function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets request for assembling URLs
     *
     * @param iHttpRequest $request
     * @return $this
     */
    function setRequest(iHttpRequest $request = null)
    {
        $this->request = $request;
        return $this;
    }


    // ..

    private function _getPathOfUri($uri)
    {
        $reqTarget = $uri;
        $reqTarget = parse_url($reqTarget);
        $reqTarget = ($reqTarget['path']) ?? '';

        return $reqTarget;
    }
}
