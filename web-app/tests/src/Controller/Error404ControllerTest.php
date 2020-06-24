<?php

namespace Tests\App\Controller;

use App\Controller\Error404Controller;
use App\Service\JsonSerializer;
use Lib\App\SerializerInterface;
use Lib\Http\HttpRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class Error404ControllerTest extends TestCase
{
    public function testResponseStatus()
    {
        $serizlier = new JsonSerializer();
        $controller = new Error404Controller($serizlier);
        $request = new HttpRequest();
        $response = $controller->handle($request);

        $this->assertTrue($response instanceof ResponseInterface);
        $this->assertTrue($response->getStatusCode() === 404);
    }
}