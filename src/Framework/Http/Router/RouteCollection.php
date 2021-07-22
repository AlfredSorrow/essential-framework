<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Route;

class RouteCollection
{
    private array $routes;

    public function get(string $name, string $pattern, callable $handler, array $tokens = [])
    {
        $this->routes[] =  new Route('GET', $name, $pattern, $handler, $tokens);
    }

    public function post(string $name, string $pattern, callable $handler, array $tokens = [])
    {
        $this->routes[] =  new Route('POST', $name, $pattern, $handler, $tokens);
    }

    public function any(string $name, string $pattern, callable $handler, array $tokens = [], $methods = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'])
    {
        foreach ($methods as $method) {
            $this->routes[] = new Route($method, $name, $pattern, $handler, $tokens);
        }  
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
