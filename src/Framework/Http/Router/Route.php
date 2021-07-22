<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RouteNotFoundException;

class Route
{
    public string $method;
    public string $name;
    public string $pattern;
    public $handler;
    public array $tokens;

    public function __construct(string $method, string $name, string $pattern, callable $handler, array $tokens = [])
    {
        $this->method = $method;
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->tokens = $tokens;
    }

    public function match(ServerRequestInterface $request): ?Result
    {

        if ($this->method !== $request->getMethod()) {
            return null;
        }

        $pattern = preg_replace_callback('~:([^\/]+)~', function ($matches) {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^\/]+';
            return "(?P<{$argument}>{$replace})";
        }, $this->pattern);


        if (preg_match('~' . $pattern . '~', $request->getUri()->getPath(), $matches)) {
            return new Result(
                $this->name,
                $this->handler,
                array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)
            );
        }

        return null;
    }

    public function generateUri(string $name, array $arguments = []): string
    {
        if ($this->name !== $name) {
            return '';
        }

        $url = preg_replace_callback('~:([^\/]+)~', function ($matches) use ($arguments) {
            $argument = $matches[1];
            if (!array_key_exists($argument, $arguments)) {
                throw new \InvalidArgumentException();
            }

            return $arguments[$argument];
        }, $this->pattern);

        return $url;
    }
}
