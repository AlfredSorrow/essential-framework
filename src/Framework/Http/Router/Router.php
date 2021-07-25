<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Route\RegexpRouteCollection;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;

class Router
{
    private RouteCollection $routes;

    /**
     * Router constructor
     *
     * @param RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Finds matched route for request
     *
     * @param ServerRequestInterface $request
     * @return Result
     */
    public function match(ServerRequestInterface $request): Result
    {
        foreach ($this->routes->getRoutes() as $route) {
            if ($result = $route->match($request)) {
                return $result;
            }
        }

        throw new RequestNotMatchedException($request);
    }

    /**
     * Generating uri
     *
     * @param string $name
     * @param array $params
     * @return string
     */
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
