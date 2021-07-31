<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * To add methods as callable handler
 */
final class Handler
{
    static public function method(string $class, string $method): callable
    {
        return fn (ServerRequestInterface $request, callable $next = null) => (new $class)->{$method}($request, $next);
    }
}
