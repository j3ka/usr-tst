<?php

namespace Lib\Http;

class JsonRouteConfig implements RouteConfigInterface
{
    private array $routeMap;

    public function __construct(string $fileName)
    {
        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException('File '.$fileName.' does not exists');
        }
        $content = file_get_contents($fileName);
        $config = json_decode($content, true);
        if (null === $config) {
            throw new \RuntimeException(json_last_error_msg());
        }

        $this->routeMap = [];
        foreach ($config as $path => $handlerClass) {
            $this->routeMap[] = new Route($path, $handlerClass);
        }
    }

    public function getRoutesCollection(): array
    {
        return $this->routeMap;
    }
}