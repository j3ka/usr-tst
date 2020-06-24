<?php

namespace Tests\Lib\Http;

use Lib\Http\Route;
use Lib\Http\RouteConfigInterface;
use Lib\Http\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private const DEFAULT_ROUTE_CLASS = 'SomeClass',
                  EXAMPLE_ROUTE_PATH  = 'test',
                  EXAMPLE_ROUTE_CLASS = 'AnotherClass';

    public function testDefaultRoute()
    {
        $router = $this->getRouter();

        $this->assertEquals(
            $router->getMatchedRoute('never-route')->getHandlerClass(),
            self::DEFAULT_ROUTE_CLASS
        );
    }

    public function testMatchedRoute()
    {
        $router = $this->getRouter();

        $this->assertEquals(
            $router->getMatchedRoute(self::EXAMPLE_ROUTE_PATH)->getHandlerClass(),
            self::EXAMPLE_ROUTE_CLASS
        );
    }

    private function getRouter(): Router
    {
        $config = $this->createMock(RouteConfigInterface::class);
        $config->method('getRoutesCollection')->willReturn([
            new Route(self::EXAMPLE_ROUTE_PATH, self::EXAMPLE_ROUTE_CLASS),
            new Route('a', 'AClass'),
            new Route('b', 'BClass'),
        ]);
        $defaultRoute = new Route('def', self::DEFAULT_ROUTE_CLASS);

        return new Router($config, $defaultRoute);
    }
}