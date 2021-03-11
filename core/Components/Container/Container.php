<?php

namespace SiteCore\Components\Container;

/**
 * Class Container
 * @package SiteCore\Components\Container
 */
class Container implements ContainerInterface
{

    private $services = [];

    /**
     * @param string $serviceName
     * @param callable $serviceInstance
     * @throws \Exception
     */
    public function set(string $serviceName, callable $serviceInstance)
    {

        if ($serviceName == "") {
            throw new \Exception("Service invalid name");
        }

        $this->services[$serviceName] = call_user_func($serviceInstance);

    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \Exception("Service not found");
        }

        return $this->services[$id];
    }

    /**
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->services[$id]);
    }

}