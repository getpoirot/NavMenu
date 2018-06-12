<?php
namespace Poirot\NavMenu;

use Poirot\Http\Interfaces\iHttpRequest;
use Poirot\Router\Interfaces\iRoute;
use Poirot\Router\Interfaces\iRouterStack;
use Poirot\Std\Type\StdTravers;


class MenuRoute
    extends aMenu
{
    /** @var iRouterStack */
    protected $router;
    /** @var iRoute */
    protected $matchedRoute;
    /** @var iHttpRequest */
    protected $request;
    /** @var string */
    protected $routeName;
    /** @var array */
    protected $routeParams = [];
    protected $isUsingMatchedParams = false;


    /**
     * MenuRoute constructor.
     *
     * @param iRouterStack $router
     * @param iRoute|null $matchedRoute
     * @param iHttpRequest $request
     */
    function __construct(
        iRouterStack $router
        , iRoute $matchedRoute = null
        , iHttpRequest $request = null
    )
    {
        $this->router       = $router;
        $this->request      = $request;
        $this->matchedRoute = $matchedRoute;
    }


    /**
     * Returns href for this menu
     *
     * @return string
     * @throws \Exception
     */
    function getHref()
    {
        $params = $this->getRouteParams();

        if ( $this->isUsingMatchedParams() && $this->getMatchedRoute() )
        {
            $tParams = StdTravers::of( $this->getMatchedRoute()->params() )->toArray();
            $params  = array_merge($tParams, $params);
        }


        if (! $router = $this->getRouter() )
            throw new \Exception('Router Not Set Yet!');

        $routeName = $this->getRouteName();
        if (! $route = $router->explore($routeName) )
            throw new \Exception(sprintf(
                'Cant explore to route name (%s).'
                , \Poirot\Std\flatten($routeName)
            ));


        $uri = $route->assemble($params);
        return (string) $uri;
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
        // TODO
        return $this->getMatchedRoute()->getName() == $this->getRouteName();
    }



    // Options:

    /**
     * Set Route Name
     *
     * @param string $name
     *
     * @return $this
     */
    function setRouteName($name)
    {
        $this->routeName = (string) $name;
        return $this;
    }

    /**
     * Get Route Name
     *
     * @return string
     */
    function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set Route Params
     *
     * @param array $params
     *
     * @return $this
     */
    function setRouteParams(array $params)
    {
        $this->routeParams = $params;
        return $this;
    }

    /**
     * Route Params
     *
     * @return array
     */
    function getRouteParams()
    {
       return $this->routeParams;
    }

    /**
     * Set Use Current Matched Route Params Also
     *
     * @param bool $bool
     *
     * @return $this
     */
    function setUsingMatchedParams($bool = true)
    {
        $this->isUsingMatchedParams = (bool) $bool;
        return $this;
    }

    /**
     * Is Using Matched Route Params To Assemble Route
     *
     * @return bool
     */
    function isUsingMatchedParams()
    {
        return $this->isUsingMatchedParams;
    }

    /**
     * Set Router
     *
     * @param iRouterStack $router
     *
     * @return $this
     */
    function setRouter(iRouterStack $router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * Get Router
     *
     * @return iRouterStack
     */
    function getRouter()
    {
        return $this->router;
    }

    /**
     * Set Matched Route
     *
     * @param iRoute $route
     *
     * @return $this
     */
    function setRouteMatch(iRoute $route)
    {
        $this->matchedRoute = $route;
        return $this;
    }

    /**
     * Get Route Matched
     *
     * @return iRoute
     */
    function getMatchedRoute()
    {
        if (! $this->matchedRoute && $req = $this->getRequest() )
        {
            $routeMatch = $this->getRouter()->match( $req );
            $this->setRouteMatch($routeMatch);
        }


        return $this->matchedRoute;
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
}
