<?php

use Slim\Psr7\Response;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Framework\Http\Router\SimpleRouter;
use Framework\Http\ResponseSender;
use Framework\Http\Router\Result;
use Framework\Http\Router\RouteCollection;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));
ini_set('display_errors', '1');

require 'vendor/autoload.php';

### init
$request = ServerRequestFactory::createFromGlobals();

$patch = $request->getUri()->getPath();

$routes = new RouteCollection();
$routes->get(
    'home',
    '/',
    function (ServerRequestInterface $request) {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        $message = "Hello, {$name}!";
        return (new Response())->withBody((new StreamFactory())->createStream($message));
    }
);

$routes->get(
    'about',
    '/about',
    function () {
        $message = "About page";
        return (new Response())->withBody((new StreamFactory())->createStream($message));
    }
);

$routes->get(
    'post_show',
    '/post/:id',
    function (ServerRequestInterface $request) {
        $postId = $request->getAttribute('id');
        return (new Response())->withBody((new StreamFactory())->createStream($postId));
    },
    ['id' => '\d+']
);




$router = new SimpleRouter($routes);
$result = $router->match($request);
foreach ($result->getAttributes() as $name => $value) {
    $request = $request->withAttribute($name, $value);
}
$response = $result->getHandler()($request, $result->getName());

### postprocessing
$response = $response->withHeader('ASS', 'SAS');

### send
ResponseSender::send($response);
