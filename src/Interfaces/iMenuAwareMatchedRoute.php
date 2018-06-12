<?php
namespace Poirot\NavMenu\Interfaces;

use Poirot\Router\Interfaces\iRoute;


interface iMenuAwareMatchedRoute
{
    /**
     * Set Matched Route
     *
     * @param iRoute $route
     *
     * @return $this
     */
    function setRouteMatch(iRoute $route);
}
