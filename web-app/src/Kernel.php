<?php

namespace App;

use App\Controller\Error404Controller;
use Lib\App\Config\ConfigInterface;
use Lib\App\Container;
use Lib\App\KernelInterface;
use Lib\App\ServiceResolver;
use Lib\Http\HttpRequest;
use Lib\Http\JsonRouteConfig;
use Lib\Http\Message\Response;
use Lib\Http\Route;
use Lib\Http\Router;

class Kernel implements KernelInterface
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @var string
     */
    private string $projectDir;

    /**
     * @param string $projectDir
     * @param ConfigInterface $config
     */
    public function __construct(string $projectDir, ConfigInterface $config)
    {
        $this->config = $config;
        $this->projectDir = $projectDir;
    }

    public function handle()
    {
        $resolver = new ServiceResolver($this->config);
        $container = new Container($resolver);

        $router = new Router(
            new JsonRouteConfig($this->projectDir.'/config/routes.json'),
            new Route('404', Error404Controller::class)
        );

        $request = new HttpRequest();

        $matchedRoute = $router->getMatchedRoute($request->getRequestTarget());
        $controller = $container->get($matchedRoute->getHandlerClass());
        /** @var Response $response */
        $response = $controller->handle($request);
        foreach ($response->getHeaders() as $header) {
            header($header);
        }

        echo $response->getBody();
    }
}