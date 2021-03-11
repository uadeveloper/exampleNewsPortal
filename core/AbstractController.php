<?php

declare(strict_types=1);

namespace SiteCore;

use SiteCore\Components\Container\Container;
use SiteCore\Components\Routing\RouteResolver;

class AbstractController
{

    protected $container;

    final public function setContainer(Container $container)
    {
        $this->container = $container;
    }

}