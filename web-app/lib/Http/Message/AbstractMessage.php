<?php

namespace Lib\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class AbstractMessage implements MessageInterface
{
    protected const DEFAULT_PROTOCOL = '1.1';

    /**
     * @var array
     */
    protected array $headers;

    /**
     * @var string
     */
    protected string $protocolVersion;

    /**
     * @var StreamInterface
     */
    protected StreamInterface $body;

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     * @return $this|AbstractMessage
     */
    public function withProtocolVersion($version)
    {
        $this->protocolVersion = $version;

        return $this;
    }

    /**
     * @return array|\string[][]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name)
    {
        return isset($this->headers[$name]);
    }

    /**
     * @param string $name
     * @return false|string[]
     */
    public function getHeader($name)
    {
        $header = $this->getHeaderLine($name);

        return explode(';', $header);
    }

    /**
     * @param string $name
     * @return mixed|string|string[]|null
     */
    public function getHeaderLine($name)
    {
        return $this->getHeaders()[$name] ?? null;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this|AbstractMessage
     */
    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this|AbstractMessage
     */
    public function withAddedHeader($name, $value)
    {
        if ($this->hasHeader($name)) {
            $currentValue = $this->headers[$name];
            $currentValue .= ';'.$value;
            $this->headers[$name] = $currentValue;
        } else {
            $this->withHeader($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return $this|AbstractMessage
     */
    public function withoutHeader($name)
    {
        unset($this->headers[$name]);

        return $this;
    }

    /**
     * @return StreamInterface
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     * @return $this|AbstractMessage
     */
    public function withBody(StreamInterface $body)
    {
        $this->body = $body;

        return $this;
    }
}