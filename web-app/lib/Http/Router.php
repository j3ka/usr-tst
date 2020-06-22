<?php

namespace Lib\Http;

use Lib\App\Config\ConfigInterface;

class Router
{
    /**
     * @var array
     */
    private array $routes;

    /**
     * @var Route
     */
    private Route $defaultRoute;

    private ?Route $matchedRoute;

    /**
     * @param RouteConfigInterface $config
     * @param Route $defaultRoute
     */
    public function __construct(RouteConfigInterface $config, Route $defaultRoute)
    {
        $this->routes = $config->getRoutesCollection();
        $this->defaultRoute = $defaultRoute;
        $this->matchedRoute = null;
    }

    public function getDefaultRoute(): Route
    {
        return $this->defaultRoute;
    }

    public function getMatchedRoute(string $path): Route
    {
        if (null !== $this->matchedRoute) {
            return $this->matchedRoute;
        }
        /** @var Route $route */
        foreach ($this->routes as $route) {
            if ($route->canHandle($path)) {
                $this->matchedRoute = $route;
                return $route;
            }
        }
        $this->matchedRoute = $this->getDefaultRoute();

        return $this->getDefaultRoute();
    }
}