<?php

declare(strict_types=1);

namespace SiteCore;

use SiteCore\Components\Container\Container;

/**
 * Class AbstractController
 * @package SiteCore
 */
class AbstractController
{

    protected $container;

    /**
     * @param Container $container
     */
    final public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

}