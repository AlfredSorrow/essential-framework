<?php

namespace Framework\Http\Router;

use App\Controller\Controller;
use InvalidArgumentException;
use Middleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * To add methods and class names as callable handler (+ lazy class loading)
 */
final class Handler
{
    /**
     * Creates callable, which calls object method
     *
     * @param string $class
     * @param string $method
     * @return callable
     */
    static public function method(string $class, string $method): callable
    {
        self::validateArguments($class, Controller::class, $method);
        return fn (ServerRequestInterface $request, callable $next = null) => (new $class)->{$method}($request, $next);
    }

    /**
     * Creates callable, which calls object as callable
     *
     * @param string $class
     * @return void
     */
    static public function class(string $class): callable
    {
        self::validateArguments($class, Controller::class);
        return fn (ServerRequestInterface $request, callable $next = null) => (new $class)($request, $next);
    }

    /**
     * Creates callable, which calls object as callable
     *
     * @param string $class
     * @return callable
     */
    static public function middleware(string $class): callable
    {
        self::validateArguments($class, Middleware::class);
        return fn (ServerRequestInterface $request, callable $next = null) => (new $class)($request, $next);
    }

    /**
     * Check if can create handler
     *
     * @param string $class
     * @param string $interface
     * @param string $method
     * @return void
     */
    private static function validateArguments(string $class, string $interface, string $method = null): void
    {
        if (!is_a($class, $interface)) {
            throw new InvalidArgumentException("Class {$class} must implement \"{$interface}\"");
        }

        if ($method && !method_exists($class, $method)) {
            throw new InvalidArgumentException("Class {$class} must have public method \"{$method}\"");
        }
    }
}
