<?php

declare(strict_types=1);

namespace SiteCore\Components\Routing\Exception;

/**
 * Class RouteNotFoundException
 * @package SiteCore\Components\Routing\Exception
 */
class RouteNotFoundException extends \Exception
{

    protected $message = 'Route not found.';

}
