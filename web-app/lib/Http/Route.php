<?php

namespace Lib\Http;

class Route
{
    /**
     * @var string
     */
    private string $handlerClass;

    /**
     * @var string
     */
    private string $path;

    /**
     * Route constructor.
     * @param string $path
     * @param string $handlerClass
     */
    public function __construct(string $path, string $handlerClass)
    {
        $this->path = $path;
        $this->handlerClass = $handlerClass;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function canHandle(string $path): bool
    {
        return $this->path === $path;
    }

    public function getHandlerClass(): string
    {
        return $this->handlerClass;
    }
}