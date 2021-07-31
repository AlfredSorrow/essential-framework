<?php

namespace Test\Framework\Http\Router;

use PHPUnit\Framework\TestCase;
use Framework\Http\Router\SimpleRouter;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Framework\Http\Router\Pipeline;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Request;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;

class PipelineTest extends TestCase
{
    public function testPipes()
    {
        $first = ['middleware-1' => 'value-1'];
        $firstMiddleWare = $this->createMiddleware($first);
        $second = ['middleware-2' => 'value-2'];
        $secondMiddleware = $this->createMiddleware($second);

        $pipe = new Pipeline();
        $pipe->add($firstMiddleWare);
        $pipe->add($secondMiddleware);

        $content = $pipe(ServerRequestFactory::createFromGlobals(), function (ServerRequestInterface $request) {
            return (new Response())->withBody((new StreamFactory())->createStream(json_encode($request->getAttributes())));
        })->getBody()->getContents();

        $this->assertEquals(array_merge($first, $second), json_decode($content, true));
    }

    private function createMiddleware($params): callable
    {
        return function (ServerRequestInterface $request, callable $next) use ($params) {
            foreach ($params as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }

            return $next($request);
        };
    }
}
