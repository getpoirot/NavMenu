<?php
namespace Poirot\NavMenu\Interfaces;

use Poirot\Router\Interfaces\iRouterStack;


interface iMenuAwareRouter
{
    /**
     * Set Router
     *
     * @param iRouterStack $router
     *
     * @return $this
     */
    function setRouter(iRouterStack $router);
}
