<?php

namespace Lib\Http;

use Lib\Http\Message\Request;
use Psr\Http\Message\ServerRequestInterface;

class HttpRequest extends Request implements ServerRequestInterface
{
    private array $cookieParams;
    private array $queryParams;
    private array $uploadedFiles;
    private array $parsedBody;
    private array $attributes;

    public function __construct()
    {
        parent::__construct();
        $this->cookieParams = $_COOKIE;
        $this->queryParams = $_GET;
        $this->uploadedFiles = $_FILES;
        $this->parsedBody = $_POST;
        $this->attributes = [];
    }

    public function getServerParams()
    {
        return $_SERVER;
    }

    public function getCookieParams()
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies)
    {
        $this->cookieParams = $cookies;

        return $this;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query)
    {
        $this->queryParams = $query;

        return $this;
    }

    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return $this;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data)
    {
        if (!empty($data)) {
            $this->parsedBody = $data;
        }

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function withoutAttribute($name)
    {
        unset($this->attributes[$name]);

        return $this;
    }
}