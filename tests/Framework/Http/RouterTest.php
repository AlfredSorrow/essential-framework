<?php

namespace Test\Framework\Http;

use PHPUnit\Framework\TestCase;
use Framework\Http\Router\SimpleRouter;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Request;
use InvalidArgumentException;


class RouterTest extends TestCase
{
    public function testCorrectMethod()
    {
        $routes = new RouteCollection();
        $routes->get($nameGet = 'blog', '/blog', $handlerGet = $this->createHandler());
        $routes->post($namePost = 'blog_edit', '/blog', $handlerPost = $this->createHandler());

        $router = new SimpleRouter($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        self::assertEquals($nameGet, $result->getName());
        self::assertEquals($handlerGet, $result->getHandlers());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        self::assertEquals($namePost, $result->getName());
        self::assertEquals($handlerPost, $result->getHandlers());
    }

    public function testCorrectMatch()
    {
        $routes = new RouteCollection();
        $routes->get($home = 'home', '/', $homeHandler = $this->createHandler());
        $routes->get($blog = 'blog', '/blog', $blogHandler = $this->createHandler());
        $routes->get($blogShow = 'blog_show', '/blog/:id/', $blogShowHandler = $this->createHandler(), ['id' => '\d+']);

        $router = new SimpleRouter($routes);

        $result = $router->match($this->buildRequest('GET', '/'));
        self::assertEquals($home, $result->getName());
        self::assertEquals($homeHandler, $result->getHandlers());


        $result = $router->match($this->buildRequest('GET', '/blog'));
        self::assertEquals($blog, $result->getName());
        self::assertEquals($blogHandler, $result->getHandlers());

        $result = $router->match($this->buildRequest('GET', '/blog/123/'));
        self::assertEquals($blogShow, $result->getName());
        self::assertEquals($blogShowHandler, $result->getHandlers());
    }

    public function testMissingMethod()
    {
        $routes = new RouteCollection();
        $routes->post('blog_edit', '/blog', $this->createHandler());

        $router = new SimpleRouter($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('GET', '/blog'));
    }

    public function testCorrectAttributes()
    {
        $routes = new RouteCollection();
        $routes->get($name = 'blog_show', '/blog/:id', $this->createHandler(), ['id' => '\d+']);

        $router = new SimpleRouter($routes);
        $result = $router->match($this->buildRequest('GET', '/blog/7'));

        self::assertEquals($name, $result->getName());
        self::assertEquals(['id' => '7'], $result->getAttributes());
    }

    public function testIncorrectAttributes()
    {
        $routes = new RouteCollection();
        $routes->get($name = 'blog_show', '/blog/:id', $this->createHandler(), ['id' => '\d+']);

        $router = new SimpleRouter($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('GET', '/blog/somebody'));
    }

    public function testGenerateUri()
    {
        $routes = new RouteCollection();
        $routes->get('blog_show', '/blog/:id', $this->createHandler(), ['id' => '\d+']);
        $routes->get('blog', '/blog', $this->createHandler());

        $router = new SimpleRouter($routes);

        self::assertEquals('/blog', $router->generateUri('blog'));
        self::assertEquals('/blog/8', $router->generateUri('blog_show', ['id' => '8']));
    }

    public function testGenerateUriMissingAttr()
    {
        $routes = new RouteCollection();
        $routes->get('blog_show', '/blog/:id', $this->createHandler(), ['id' => '\d+']);

        $router = new SimpleRouter($routes);

        $this->expectException(InvalidArgumentException::class);
        $router->generateUri('blog_show', ['slug' => 10]);
    }

    public function testGenerateUriRouteNotFound()
    {
        $router = new SimpleRouter(new RouteCollection);

        $this->expectException(RouteNotFoundException::class);
        $router->generateUri('asdad', [1 => 'awdawd', 2 => 'a', 'adwad' => 222]);
    }


    private function buildRequest(string $method, string $uri): Request
    {
        return (new ServerRequestFactory())->createServerRequest($method, $uri);
    }

    private function createHandler(): array
    {
        return [fn () => ''];
    }
}
