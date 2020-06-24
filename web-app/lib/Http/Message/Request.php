<?php

namespace Lib\Http\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends AbstractMessage implements RequestInterface
{
    /**
     * @var string
     */
    private string $requestTarget;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var UriInterface
     */
    private UriInterface $uri;

    public function __construct()
    {
        $this->headers = [
            'Accept' => $_SERVER['HTTP_ACCEPT'],
            'Accept-Charset' => $_SERVER['HTTP_ACCEPT_CHARSET'],
            'Accept-Encoding' => $_SERVER['HTTP_ACCEPT_ENCODING'],
            'Accept-Language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Connection' => $_SERVER['HTTP_CONNECTION'],
            'Host' => $_SERVER['HTTP_HOST'],
            'User-Agent' => $_SERVER['HTTP_USER_AGENT'],
        ];
        $this->protocolVersion = $_SERVER['SERVER_PROTOCOL'] ??self::DEFAULT_PROTOCOL;
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = preg_replace('/\?.+/','',$requestUri);
        $this->requestTarget = $requestUri;
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getRequestTarget()
    {
        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget)
    {
        $this->requestTarget = $requestTarget;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;
    }
}