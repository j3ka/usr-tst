<?php

namespace App;

use App\Controller\Error404Controller;
use Lib\App\Config\ConfigInterface;
use Lib\App\Container;
use Lib\App\KernelInterface;
use Lib\Http\HttpRequest;
use Lib\Http\JsonRouteConfig;
use Lib\Http\Message\Response;
use Lib\Http\Route;
use Lib\Http\Router;

class Kernel implements KernelInterface
{
    /**
     * @var string
     */
    private string $projectDir;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param string $projectDir
     * @param ConfigInterface $config
     */
    public function __construct(string $projectDir, ConfigInterface $config)
    {
        $this->projectDir = $projectDir;
        $this->config = $config;
    }

    public function handle()
    {
        $container = new Container($this, $this->config);

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

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return $this->projectDir;
    }
}