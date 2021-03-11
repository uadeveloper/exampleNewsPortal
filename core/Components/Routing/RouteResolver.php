<?php

namespace SiteCore\Components\Routing;

use SiteCore\Components\Routing\Exception\RouteNotFoundException;

class RouteResolver
{

    private $routeItems;

    /**
     * RouteResolver constructor.
     * @param array $routeItems \
     */
    public function __construct(?array $routeItems)
    {
        $this->routeItems = $routeItems;
    }

    /**
     * @param RouteItem $item
     */
    public function add(RouteItem $item)
    {
        $this->routeItems[] = $item;
    }

    /**
     * @param array $routeItems
     */
    public function addItems(array $routeItems)
    {
        $this->routeItems = $routeItems;
    }

    /**
     * Check route controller
     *
     * @param string $requestUri
     * @param string $requestMethod
     * @return RouteItem
     * @throws RouteNotFoundException
     */
    public function resolve(string $requestUri, string $requestMethod): RouteItem
    {

        $route = null;

        foreach ($this->routeItems as $routeItem) {

            $regex = $routeItem->getMatch();

            $regex = "/^" . str_replace(["\/", "/"], "\/", $regex) . "$/mi";

            if (preg_match($regex, $requestUri, $matches)) {

                if ($routeItem->hasMethod($requestMethod)) {
                    $routeItem->setParams(array_splice($matches, 1));
                    $route = $routeItem;
                    break;
                }

            }

        }

        if (!$route) {
            throw new RouteNotFoundException();
        }

        return $route;

    }

}