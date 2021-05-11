<?php

namespace SiteCore\Components\Routing;

class RouteItem
{

    private $name = "";
    private $match = ""; # Example /news/one-([0-9]*)/comments
    private $methods = []; # GET, POST
    private $classHandler; # \App\Controller\NewsController::class
    private $classHandlerFunction; # newsOne
    private $params = [];

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMatch(): string
    {
        return $this->match;
    }

    /**
     * @param string $match
     */
    public function setMatch(string $match): void
    {
        $this->match = $match;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     */
    public function setMethods(array $methods): void
    {
        foreach($methods AS $method) {
            $this->methods[] = strtoupper($method);
        }
    }

    public function hasMethod(string $method): bool
    {
        return in_array(strtoupper($method), $this->methods, true);
    }

    /**
     * @return string
     */
    public function getClassHandler(): string
    {
        return $this->classHandler;
    }

    /**
     * @param string $classHandler
     */
    public function setClassHandler(string $classHandler): void
    {
        $this->classHandler = $classHandler;
    }

    /**
     * @return string
     */
    public function getClassHandlerFunction(): string
    {
        return $this->classHandlerFunction;
    }

    /**
     * @param string $classHandlerFunction
     */
    public function setClassHandlerFunction(string $classHandlerFunction): void
    {
        $this->classHandlerFunction = $classHandlerFunction;
    }

}