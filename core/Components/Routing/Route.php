<?php

namespace SiteCore\Components\Routing;

class Route
{

    private $routeResolver;
    private $routeItems;
    private $resolvedRouteItem;

    public function resolve(string $requestUri, string $requestMethod): RouteItem
    {
        $this->routeResolver = new RouteResolver($this->routeItems);
        $this->routeResolver->addItems($this->routeItems);

        $this->resolvedRouteItem = $this->routeResolver->resolve($requestUri, $requestMethod);

        return $this->resolvedRouteItem;
    }

    public function redirectToController() {

    }

    public function getRouteItem() : ?RouteItem {
        return $this->resolvedRouteItem;
    }

    public function redirect($location = "/", $httpCode = 301) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $location);
        exit();
    }

    public function get(string $match, string $classHandler, string $methodName, array $params = [])
    {
        $this->map(["get"], $match, $classHandler, $methodName, $params);
    }

    public function post(string $match, string $classHandler, string $methodName, array $params = [])
    {
        $this->map(["post"], $match, $classHandler, $methodName, $params);
    }

    public function delete(string $match, string $classHandler, string $methodName, array $params = [])
    {
        $this->map(["delete"], $match, $classHandler, $methodName, $params);
    }

    public function put(string $match, string $classHandler, string $methodName, array $params = [])
    {
        $this->map(["put"], $match, $classHandler, $methodName, $params);
    }

    public function path(string $match, string $classHandler, string $methodName, array $params = [])
    {
        $this->map(["path"], $match, $classHandler, $methodName, $params);
    }

    public function map(array $methods, string $match, string $classHandler, string $methodName, array $params = [])
    {
        $routerItem = new RouteItem();
        $routerItem->setMatch($match);
        $routerItem->setClassHandler($classHandler);
        $routerItem->setClassHandlerFunction($methodName);
        $routerItem->setMethods($methods);
        $this->routeItems[] = $routerItem;
    }


}
