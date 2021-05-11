<?php

namespace SiteCore\Components\Routing;

/**
 * Class Route
 * @package SiteCore\Components\Routing
 */
class Route
{

    private $routeItems;
    private $resolvedRouteItem;

    /**
     * @param string $requestUri
     * @param string $requestMethod
     * @return RouteItem
     * @throws Exception\RouteNotFoundException
     */
    public function resolve(string $requestUri, string $requestMethod): RouteItem
    {
        $routeResolver = new RouteResolver($this->routeItems);
        $routeResolver->addItems($this->routeItems);

        $this->resolvedRouteItem = $routeResolver->resolve($requestUri, $requestMethod);

        return $this->resolvedRouteItem;
    }

    /*
        public function redirectToController() : void {

        }
    */
    public function getRouteItem(): ?RouteItem
    {
        return $this->resolvedRouteItem;
    }

    public function redirect($location = "/"): void
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $location);
        exit();
    }

    /**
     * @param string $match
     * @param string $classHandler
     * @param string $methodName
     * @param array $params
     */
    public function get(string $match, string $classHandler, string $methodName, array $params = []): void
    {
        $this->map(["get"], $match, $classHandler, $methodName, $params);
    }

    /**
     * @param string $match
     * @param string $classHandler
     * @param string $methodName
     * @param array $params
     */
    public function post(string $match, string $classHandler, string $methodName, array $params = []): void
    {
        $this->map(["post"], $match, $classHandler, $methodName, $params);
    }

    /**
     * @param string $match
     * @param string $classHandler
     * @param string $methodName
     * @param array $params
     */
    public function delete(string $match, string $classHandler, string $methodName, array $params = []): void
    {
        $this->map(["delete"], $match, $classHandler, $methodName, $params);
    }

    /**
     * @param string $match
     * @param string $classHandler
     * @param string $methodName
     * @param array $params
     */
    public function put(string $match, string $classHandler, string $methodName, array $params = []): void
    {
        $this->map(["put"], $match, $classHandler, $methodName, $params);
    }

    /**
     * @param string $match
     * @param string $classHandler
     * @param string $methodName
     * @param array $params
     */
    public function path(string $match, string $classHandler, string $methodName, array $params = []): void
    {
        $this->map(["path"], $match, $classHandler, $methodName, $params);
    }

    /**
     * @param array $methods
     * @param string $match
     * @param string $classHandler
     * @param string $methodName
     * @param array $params
     */
    public function map(array $methods, string $match, string $classHandler, string $methodName, array $params = []): void
    {
        $routerItem = new RouteItem();
        $routerItem->setMatch($match);
        $routerItem->setClassHandler($classHandler);
        $routerItem->setClassHandlerFunction($methodName);
        $routerItem->setMethods($methods);
        $routerItem->setParams($params);
        $this->routeItems[] = $routerItem;
    }

}
