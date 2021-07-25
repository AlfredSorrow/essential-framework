<?php

use Slim\Psr7\Response;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Framework\Http\Router\Router;
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
$routes->get('home', '/', function (ServerRequestInterface $request) {
    $name = $request->getQueryParams()['name'] ?? 'Guest';
    $message = "Hello, {$name}!";
    return (new Response())->withBody((new StreamFactory())->createStream($message));
});

$routes->get('about', '/about', function () {
    $message = "About page";
    return (new Response())->withBody((new StreamFactory())->createStream($message));
});



$router = new Router($routes);

$result = $router->match($request);
$response = $result->getHandler()($request, $result->getName(), $result->getAttributes());

### postprocessing
$response = $response->withHeader('ASS', 'SAS');

### send
ResponseSender::send($response);
