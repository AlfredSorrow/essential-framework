<?php

namespace Framework\Http\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;

class RouteNotFoundException extends \LogicException
{
    public function __construct(string $name, array $params)
    {
    }
}
