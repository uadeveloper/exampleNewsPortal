<?php

declare(strict_types=1);

namespace SiteCore;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use SiteCore\Components\Container\Container;
use SiteCore\Components\Routing\Exception\RouteNotFoundException;

/**
 * Class App
 * @package SiteCore
 */
class App
{

    private $container;

    /**
     * App constructor.
     * @param Container $container
     */
    public function __construct(
        Container $container
    )
    {
        $this->container = $container;
    }

    /**
     * @param string $requestUri
     * @param string $requestMethod
     * @throws ReflectionException
     * @throws Exception
     */
    public function run(string $requestUri, string $requestMethod): void
    {

        if (!$this->container->has("route")) {
            throw new Exception("Router component is not configured.");
        }

        $route = $this->container->get("route");

        try {
            $routeItem = $route->resolve($requestUri, $requestMethod);
        } catch (RouteNotFoundException $ex) {
                $route->redirect('/404.html');
                return;
        }

        $reflectionClass = new ReflectionClass($routeItem->getClassHandler());

        $parentClass = $reflectionClass->getParentClass();

        if (!$reflectionClass->isSubclassOf(AbstractController::class)) {
            throw new Exception("Controller " . $routeItem->getClassHandler() . " need extend parent " . AbstractController::class);
        }

        $constructorArgs = [];
        $constructor = $reflectionClass->getConstructor();
        if ($constructor) {

            $parameters = $constructor->getParameters();

            foreach ($parameters as $parameter) {

                if (!$this->container->has($parameter->getName())) {
                    throw new Exception("Controller " . $routeItem->getClassHandler() . " constructor not found class '" . $parameter->getName() . "'.");
                }

                $constructorArgs[] = $this->container->get($parameter->getName());

            }

        }

        $controller = $reflectionClass->newInstanceArgs($constructorArgs);
        $controller->setContainer($this->container);

        if (!$reflectionClass->hasMethod($routeItem->getClassHandlerFunction())) {
            throw new Exception("Controller " . $routeItem->getClassHandler() . " not have method '" . $routeItem->getClassHandlerFunction() . "'.");
        }

        $method = $reflectionClass->getMethod($routeItem->getClassHandlerFunction());

        $functionArgs = [];
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {

            if (!$this->container->has($parameter->getName())) {
                throw new Exception("Controller " . $routeItem->getClassHandler() . " function '" . $routeItem->getClassHandlerFunction() . "' not found class '" . $parameter->getName() . "'.");
            }

            $functionArgs[] = $this->container->get($parameter->getName());

        }

        $reflectionMethod = new ReflectionMethod($routeItem->getClassHandler(), $routeItem->getClassHandlerFunction());
        $reflectionMethod->invokeArgs($controller, $functionArgs);

    }

}