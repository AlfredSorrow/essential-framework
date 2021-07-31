<?php

namespace Framework\Http\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class Pipeline
{

    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue;
    }

    /**
     * Calls middlewars and controller, if no controller uses $default
     *
     * @param ServerRequestInterface $request
     * @param callable $default
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        return self::next(clone $this->queue, $request, $default);
    }

    /**
     * Adds handler
     *
     * @param callable $handler
     * @return void
     */
    public function add(callable $handler): void
    {
        $this->queue->enqueue($handler);
    }

    /**
     * Calls middlewars and controller, if no controller uses $default
     *
     * @param array $handlers
     * @return void
     */
    public function addCollection(array $handlers): void
    {
        foreach ($handlers as $handler) {
            $this->add($handler);
        }
    }

    /**
     * Undocumented function
     *
     * @param SplQueue $queue
     * @param ServerRequestInterface $request
     * @param callable $default
     * @return ResponseInterface
     */
    private static function next(SplQueue $queue, ServerRequestInterface $request, callable $default): ResponseInterface
    {
        if ($queue->isEmpty()) {
            return $default($request);
        }

        return $queue->dequeue()($request, static function (ServerRequestInterface $request) use ($default, $queue) {
            return self::next($queue, $request, $default);
        });
    }
}
