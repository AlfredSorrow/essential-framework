<?php

namespace Framework\Http\Router;

class Result
{

    private string $name;
    private array $handlers;
    private array $attributes;

    public function __construct(string $name, array $handlers, array $attributes)
    {
        $this->name = $name;
        $this->handlers = $handlers;
        $this->attributes = $attributes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
