<?php

namespace Framework\Http\Router;

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
}
