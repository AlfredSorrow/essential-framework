<?php

use App\Controller\About;
use App\Controller\Cabinet;
use App\Controller\Index;
use App\Controller\Post;
use App\Middleware\Authorization;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Framework\Http\Router\SimpleRouter;
use Framework\Http\ResponseSender;
use Framework\Http\Router\Handler;
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
    Handler::method(Index::class, 'main')
);

$routes->get(
    'about',
    '/about',
    Handler::class(About::class)
);

$routes->get(
    'post_show',
    '/post/:id',
    Handler::method(Post::class, 'show'),
    ['id' => '\d+']
);

$routes->get(
    'post_list',
    '/post',
    Handler::method(Post::class, 'show'),
);

$users = [
    'admin' => '1'
];
$routes->get(
    'cabinet',
    '/cabinet',
    function (ServerRequestInterface $request) use ($users) {
        return (new Authorization($users))($request, Handler::method(Cabinet::class, 'main'));
    }
);



$router = new SimpleRouter($routes);
$result = $router->match($request);
foreach ($result->getAttributes() as $name => $value) {
    $request = $request->withAttribute($name, $value);
}
$response = $result->getHandlers()[0]($request);

### postprocessing
$response = $response->withHeader('ASS', 'SAS');

### send
ResponseSender::send($response);
