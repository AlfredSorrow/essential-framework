<?php

namespace Framework\Http\Router\Exception;

use function Framework\Helper\jsonForPrint;

class RouteNotFoundException extends \LogicException
{
    public function __construct(string $name, array $params)
    {
        $params = jsonForPrint($params);
        $message = "NO ROUTE WITH NAME: {$name} AND PARAMS: {$params}";
        parent::__construct($message);
    }
}
