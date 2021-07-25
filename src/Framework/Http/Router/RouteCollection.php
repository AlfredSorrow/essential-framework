<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Route\RegexpRoute;

class RouteCollection
{
    private array $routes;

    /**
     * Adding custom routes
     *
     * @param RegexpRoute $route
     * @return void
     */
    public function addRoute(RegexpRoute $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * Add GET route
     *
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $tokens
     * @return void
     */
    public function get(string $name, string $pattern, callable $handler, array $tokens = []): void
    {
        $this->addRoute(new RegexpRoute('GET', $name, $pattern, $handler, $tokens));
    }

    /**
     * Add POST route 
     *
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $tokens
     * @return void
     */
    public function post(string $name, string $pattern, callable $handler, array $tokens = []): void
    {
        $this->addRoute(new RegexpRoute('POST', $name, $pattern, $handler, $tokens));
    }

    /**
     * Add routes with any methods 
     *
     * @param string $name
     * @param string $pattern
     * @param callable $handler
     * @param array $tokens
     * @param array $methods
     * @return void
     */
    public function any(string $name, string $pattern, callable $handler, array $tokens = [], $methods = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE']): void
    {
        foreach ($methods as $method) {
            $this->addRoute(new RegexpRoute($method, $name, $pattern, $handler, $tokens));
        }
    }

    /**
     * @return RegexpRoute[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
