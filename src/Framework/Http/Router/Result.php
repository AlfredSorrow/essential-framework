<?php

namespace Framework\Http\Router;

class Result
{

    private string $name;
    private $handler;
    private array $attributes;

    public function __construct(string $name, callable $handler, array $attributes)
    {
        $this->name = $name;
        $this->handler = $handler;
        $this->attributes = $attributes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): callable
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
