<?php

use App\Controller\Error404Controller;
use App\Repository\UserRepository;
use Lib\App\Config\JsonConfig;
use Lib\App\Container;
use Lib\App\ServiceResolver;
use Lib\Http\JsonRouteConfig;
use Lib\Http\Route;
use Lib\Http\Router;
use Lib\Http\HttpRequest;

require __DIR__ . '/../vendor/autoload.php';

$config = new JsonConfig(__DIR__.'/../config/app.json');
$resolver = new ServiceResolver($config);
$container = new Container($resolver);
/** @var UserRepository $repository */
$defaultRoute = new Route('404', Error404Controller::class);
$router = new Router(new JsonRouteConfig(__DIR__.'/../config/routes.json'), $defaultRoute);
$request = new HttpRequest();
$matchedRoute = $router->getMatchedRoute($request->getRequestTarget());
$controller = $container->get($matchedRoute->getHandlerClass());
echo $controller->handle($request)->getBody();
