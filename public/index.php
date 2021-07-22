<?php

use Slim\Psr7\Response;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Framework\Http\ResponseSender;

chdir(dirname(__DIR__));
ini_set('display_errors', '1');

require 'vendor/autoload.php';

### init
$request = ServerRequestFactory::createFromGlobals();

$patch = $request->getUri()->getPath();

### preprocessing


### action
$name = $request->getQueryParams()['name'] ?? 'Guest';
$body = (new StreamFactory())->createStream("Hello, {$patch}!");
$response = (new Response())->withBody($body);

### postprocessing
$response = $response->withHeader('ASS', 'SAS');

### send

ResponseSender::send($response);
