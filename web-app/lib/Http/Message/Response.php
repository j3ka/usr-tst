<?php

namespace Lib\Http\Message;

use Psr\Http\Message\ResponseInterface;

class Response extends AbstractMessage implements ResponseInterface
{

    /**
     * @var int
     */
    private int $statusCode;

    /**
     * @var string
     */
    private string $reasonPhrase;

    /**
     * @param $status
     * @param $body
     * @param array $headers
     * @param string $protocol
     */
    public function __construct(int $status, string $body, $headers=[], $protocol=self::DEFAULT_PROTOCOL)
    {
        $this->statusCode = $status;
        $this->headers = $headers;
        $this->protocolVersion = $protocol;

        $resource = \fopen('php://temp', 'rw+');
        \fwrite($resource, $body);
        $this->body = new Stream($resource);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return Response|void
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->statusCode = (int)$code;
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }
}