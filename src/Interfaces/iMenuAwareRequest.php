<?php
namespace Poirot\NavMenu\Interfaces;

use Poirot\Http\Interfaces\iHttpRequest;


interface iMenuAwareRequest
{
    /**
     * Sets request for assembling URLs
     *
     * @param iHttpRequest $request
     * @return $this
     */
    function setRequest(iHttpRequest $request = null);
}
