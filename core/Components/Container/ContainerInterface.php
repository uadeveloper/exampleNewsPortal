<?php

/**
 * PSR container interface
 */

namespace SiteCore\Components\Container;

/**
 * Interface ContainerInterface
 * @package SiteCore\Components\Container
 */
interface ContainerInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param $id
     * @return mixed
     */
    public function has($id);
}