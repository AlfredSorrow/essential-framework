<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;

class Router
{
    private RouteCollection $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function match(ServerRequestInterface $request): Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($result = $route->match($request)) {
                return $result;
            }
        }

        throw new RequestNotMatchedException($request);
    }

    public function generateUri(string $name, array $params = []): string
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($url = $route->generateUri($name, array_filter($params))) {
                return $url;
            }
        }

        throw new RouteNotFoundException($name, $params);
    }
}
