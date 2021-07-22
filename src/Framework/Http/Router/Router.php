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
            if ($route->method !== $request->getMethod()) {
                continue;
            }

            $pattern = preg_replace_callback('~:([^\/]+)~', function ($matches) use ($route) {
                $argument = $matches[1];
                $replace = $route->tokens[$argument] ?? '[^\/]+';
                return "(?P<{$argument}>{$replace})";
            }, $route->pattern);


            if (preg_match('~' . $pattern . '~', $request->getUri()->getPath(), $matches)) {
                return new Result(
                    $route->name,
                    $route->handler,
                    array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)
                );
            }
        }

        throw new RequestNotMatchedException($request);
    }

    public function generateUri(string $name, array $params = []): string
    {
        $arguments = array_filter($params);
        foreach ($this->routes->getRoutes() as $route) {
            if ($route->name !== $name) {
                continue;
            }

            $url = preg_replace_callback('~:([^\/]+)~', function ($matches) use ($arguments) {
                $argument = $matches[1];
                if (!array_key_exists($argument, $arguments)) {
                    throw new \InvalidArgumentException();
                }

                return $arguments[$argument];
            }, $route->pattern);

            return $url;
        }

        throw new RouteNotFoundException($name, $params);
    }
}
